<?php
/*
 * This file is part of Laravel Reviewable.
 *
 * (c) Goran Krgovic <gorankrgovic1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace Gox\Laravel\Reviewable\Reviewable\Services;

use Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar;
use Gox\Contracts\Reviewable\Review\Models\Review as ReviewContract;
use Gox\Contracts\Reviewable\Reviewable\Models\Reviewable as ReviewableContract;
use Gox\Contracts\Reviewable\ReviewCounter\Models\ReviewCounter as ReviewCounterContract;
use Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer;
use Gox\Contracts\Reviewable\Reviewable\Services\ReviewableService as ReviewableServiceContract;
use Gox\Contracts\Reviewable\Reviewer\Models\Reviewer as ReviewerContract;
use Gox\Laravel\Reviewable\Review\Enums\ReviewStar;
use Illuminate\Support\Facades\DB;

class ReviewableService implements ReviewableServiceContract
{
    /**
     * @param ReviewableContract $reviewable
     * @param int $star
     * @param string $comment
     * @param string $userId
     */
    public function addReviewTo(ReviewableContract $reviewable, $star, $comment, $userId)
    {
        $userId = $this->getReviewerUserId($userId);

        $review = $reviewable->reviews()->where([
            'user_id' => $userId,
        ])->first();

        if (!$review) {
            $reviewable->reviews()->create([
                'user_id' => $userId,
                'star' => $this->getReviewStar($star),
                'comment' => $comment
            ]);

            return;
        }

        if ($review->star == $this->getReviewStar($star)) {
            return;
        }
        $review->delete();


        $reviewable->reviews()->create([
            'user_id' => $userId,
            'star' => $this->getLikeStar($star),
            'comment' => $comment
        ]);
    }


    /**
     * Remove a review from
     *
     * @param ReviewableContract $reviewable
     * @param int|string|null $userId
     */
    public function removeReviewFrom(ReviewableContract $reviewable, $userId)
    {
        $review = $reviewable->reviews()->where([
            'user_id' => $this->getReviewerUserId($userId),
        ])->first();

        if (!$review) {
            return;
        }
        $review->delete();
    }


    public function isReviewed(ReviewableContract $reviewable, $userId): bool
    {
        // TODO: (?) Use `$userId = $this->getReviewerUserId($userId);`
        if ($userId instanceof ReviewerContract) {
            $userId = $userId->getKey();
        }

        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if (!$userId) {
            return false;
        }

        return $reviewable->reviews()->where([
            'user_id' => $userId
        ])->exists();
    }


    /**
     * @param ReviewableContract $reviewable
     */
    public function decrementReviewsCount(ReviewableContract $reviewable)
    {
        $counter = $reviewable->reviewsCounter()->first();

        if (!$counter) {
            return;
        }

        $counter->decrement('count');

        $average = app(ReviewContract::class)
            ->avg([
                'star',
            ])
            ->where('reviewable_type', $counter->reviewable_type)
            ->where('reviewable_id', $counter->reviewable_id);

        $counter->avg_rating = $average;
        $counter->save();
    }

    /**
     * @param ReviewableContract $reviewable
     */
    public function incrementReviewsCount(ReviewableContract $reviewable)
    {
        $counter = $reviewable->reviewsCounter()->first();

        if (!$counter) {
            $counter = $reviewable->reviewsCounter()->create([
                'count' => 0,
            ]);
        }
        $counter->increment('count');

        $average = app(ReviewContract::class)
            ->avg([
                'star',
            ])
            ->where('reviewable_type', $counter->reviewable_type)
            ->where('reviewable_id', $counter->reviewable_id);

        $counter->avg_rating = $average;
        $counter->save();
    }


    /**
     * @param ReviewableContract $reviewable
     */
    public function removeModelReviews(ReviewableContract $reviewable)
    {
        app(ReviewContract::class)->where([
            'reviewable_id' => $reviewable->getKey(),
            'reviewable_type' => $reviewable->getMorphClass(),
        ])->delete();

        app(ReviewCounterContract::class)->where([
            'reviewable_id' => $reviewable->getKey(),
            'reviewable_type' => $reviewable->getMorphClass(),
        ])->delete();
    }


    /**
     * @param ReviewableContract $reviewable
     * @return mixed
     */
    public function collectReviewersOf(ReviewableContract $reviewable)
    {
        $userModel = $this->resolveUserModel();

        $reviewersIds = $reviewable->reviews->pluck('user_id');

        return $userModel::whereKey($reviewersIds)->get();
    }


    /**
     * Fetch the counters by star or all
     *
     * @param $reviewableType
     * @param $reviewStar
     * @return array
     */
    public function fetchReviewsCounters($reviewableType, $reviewStar): array
    {
        /** @var \Illuminate\Database\Eloquent\Builder $likesCount */
        $reviewsCount = app(ReviewContract::class)
            ->select([
                DB::raw('COUNT(*) AS count'),
                'reviewable_type',
                'reviewable_id',
                'star',
            ])
            ->where('reviewable_type', $reviewableType);

        if (!is_null($reviewStar)) {
            $reviewsCount->where('star', $this->getReviewStar($reviewStar));
        }

        $reviewsCount->groupBy('reviewable_id', 'star');

        return $reviewsCount->get()->toArray();
    }



    /**
     * Retrieve User's model class name.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    private function resolveUserModel()
    {
        return config('auth.providers.users.model');
    }


    /**
     * Get the user id
     *
     * @param $userId
     * @return string
     */
    public function getReviewerUserId($userId): string
    {
        if ($userId instanceof ReviewerContract) {
            return $userId->getKey();
        }

        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if (!$userId) {
            throw InvalidReviewer::notDefined();
        }

        return $userId;
    }

    /**
     * Fetch the primary ID of the currently logged in user.
     *
     * @return null|string
     */
    protected function loggedInUserId()
    {
        return auth()->id();
    }

    /**
     * Remove a star
     *
     * @param $star
     * @return mixed
     */
    public function getReviewStar($star)
    {
        switch ( (int) $star )
        {
            case 1:
                $cst = 'RATE_POOR';
                break;

            case 2:
                $cst = 'RATE_BAD';
                break;

            case 3:
                $cst = 'RATE_OK';
                break;

            case 4:
                $cst = 'RATE_GOOD';
                break;

            case 5:
                $cst = 'RATE_GREAT';
                break;

            default:
                $cst = 'RATE_OK';
                break;
        }

        if (!defined("\\Gox\\Laravel\\Reviewable\\Review\\Enums\\ReviewStar::{$cst}")) {
            throw InvalidReviewStar::notExists($star);
        }

        return constant("\\Gox\\Laravel\\Reviewable\\Review\\Enums\\ReviewStar::{$cst}");
    }

}