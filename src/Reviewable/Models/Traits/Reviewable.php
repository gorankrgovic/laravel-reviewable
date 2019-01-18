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


namespace Gox\Laravel\Reviewable\Reviewable\Models\Traits;

use Gox\Contracts\Reviewable\Review\Models\Review as ReviewContract;
use Gox\Contracts\Reviewable\ReviewCounter\Models\ReviewCounter as ReviewCounterContract;
use Gox\Contracts\Reviewable\Reviewable\Services\ReviewableService as ReviewableServiceContract;
use Gox\Laravel\Reviewable\Review\Enums\ReviewStar;
use Gox\Laravel\Reviewable\Reviewable\Observers\ReviewableObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

/**
 * Trait Reviewable
 * @package Gox\Laravel\Reviewable\Reviewable\Models\Traits
 */
trait Reviewable
{
    /**
     * Observer at launch
     */
    public static function bootReviewable()
    {
        static::observe(ReviewableObserver::class);
    }

    /**
     * @return mixed
     */
    public function reviews()
    {
        return $this->morphMany(app(ReviewContract::class), 'reviewable');
    }

    /**
     * @return mixed
     */
    public function reviewsCounter()
    {
        return $this->morphOne(app(ReviewCounterContract::class), 'reviewable');
    }

    /**
     * @return mixed
     */
    public function collectReviewers()
    {
        return app(ReviewableServiceContract::class)->collectReviewersOf($this);
    }

    /**
     * Model likesCount attribute.
     *
     * @return int
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviewsCounter ? $this->reviewsCounter->count : 0;
    }

    /**
     * @return float
     */
    public function getReviewsAverageAttribute(): float
    {
        return $this->reviewsCounter ? $this->reviewsCounter->avg_rating: 0;
    }

    /**
     * Did the currently logged in user reviewed this model.
     *
     * @return bool
     */
    public function getReviewedAttribute(): bool
    {
        return $this->isReviewedBy();
    }

    /**
     * @param $star
     * @param null $comment
     * @param null $userId
     */
    public function reviewBy($star, $comment = null, $userId = null)
    {
        app(ReviewableServiceContract::class)->addLikeTo($this, $star, $comment, $userId);
    }


    /**
     * @param null $comment
     * @param null $userId
     */
    public function reviewPoorBy($comment = null, $userId = null)
    {
        $this->reviewBy(ReviewStar::RATE_POOR, $comment, $userId);
    }

    /**
     * @param null $comment
     * @param null $userId
     */
    public function reviewBadBy($comment = null, $userId = null)
    {
        $this->reviewBy(ReviewStar::RATE_BAD, $comment, $userId);
    }

    /**
     * @param null $comment
     * @param null $userId
     */
    public function reviewOkBy($comment = null, $userId = null)
    {
        $this->reviewBy(ReviewStar::RATE_OK, $comment, $userId);
    }

    /**
     * @param null $comment
     * @param null $userId
     */
    public function reviewGoodBy($comment = null, $userId = null)
    {
        $this->reviewBy(ReviewStar::RATE_GOOD, $comment, $userId);
    }

    /**
     * @param null $comment
     * @param null $userId
     */
    public function reviewGreatBy($comment = null, $userId = null)
    {
        $this->reviewBy(ReviewStar::RATE_GREAT, $comment, $userId);
    }


    /**
     * @param null $userId
     */
    public function unreviewBy($userId = null)
    {
        app(ReviewableServiceContract::class)->removeReviewFrom($this, $userId);
    }

    /**
     * Remove all reviews from a model
     */
    public function removeReviews()
    {
        app(ReviewableServiceContract::class)->removeModelReviews($this);
    }

    /**
     * @param null $userId
     * @return bool
     */
    public function isReviewedBy($userId = null): bool
    {
        return app(ReviewableServiceContract::class)->isReviewed($this, $userId);
    }

    /**
     * @param Builder $query
     * @param null $userId
     * @return Builder
     */
    public function scopeWhereReviewedBy(Builder $query, $userId = null): Builder
    {
        return $this->applyScopeWhereReviewedBy($query, $userId);
    }

    /**
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    public function scopeOrderByReviewsCount(Builder $query, string $direction = 'desc'): Builder
    {
        return $this->applyScopeOrderByReviewsCount($query, $direction);
    }


    /**
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    public function scopeOrderByReviewsAvg(Builder $query, string $direction = 'desc'): Builder
    {
        return $this->applyScopeOrderByReviewsAvg($query, $direction);
    }


    /**
     * @param Builder $query
     * @param $userId
     * @return Builder
     */
    private function applyScopeWhereReviewedBy(Builder $query, $userId): Builder
    {
        $service = app(ReviewableServiceContract::class);
        $userId = $service->getReviewerUserId($userId);

        return $query->whereHas('reviews', function (Builder $innerQuery) use ($userId) {
            $innerQuery->where('user_id', $userId);
        });
    }


    /**
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    private function applyScopeOrderByReviewsCount(Builder $query, string $direction): Builder
    {
        $reviewable = $query->getModel();

        return $query
            ->select($reviewable->getTable() . '.*', 'review_counters.count')
            ->leftJoin('review_counters', function (JoinClause $join) use ($reviewable) {
                $join
                    ->on('review_counters.reviewable_id', '=', "{$reviewable->getTable()}.{$reviewable->getKeyName()}")
                    ->where('review_counters.reviewable_type', '=', $reviewable->getMorphClass());
            })
            ->orderBy('review_counters.count', $direction);
    }


    /**
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    private function applyScopeOrderByReviewsAvg(Builder $query, string $direction): Builder
    {
        $reviewable = $query->getModel();

        return $query
            ->select($reviewable->getTable() . '.*', 'review_counters.avg_rating')
            ->leftJoin('review_counters', function (JoinClause $join) use ($reviewable) {
                $join
                    ->on('review_counters.reviewable_id', '=', "{$reviewable->getTable()}.{$reviewable->getKeyName()}")
                    ->where('review_counters.reviewable_type', '=', $reviewable->getMorphClass());
            })
            ->orderBy('review_counters.avg_rating', $direction);
    }

}