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


namespace Gox\Contracts\Reviewable\Reviewable\Services;

use Gox\Contracts\Reviewable\Reviewable\Models\Reviewable as ReviewableContract;

interface ReviewableService
{

    /**
     * Add a review to reviewable model by user.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param int $star
     * @param string $comment
     * @param string $userId
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     */
    public function addReviewTo(ReviewableContract $reviewable, $star, $comment, $userId);


    /**
     * Remove a review to reviewable model by user.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param null|string|int $userId
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     */
    public function removeReviewFrom(ReviewableContract $reviewable, $userId);


    /**
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param null|string|int $userId
     * @return bool
     */
    public function isReviewed(ReviewableContract $reviewable, $userId): bool;


    /**
     * Decrement the total review count and avg stored in the counter.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @return void
     */
    public function decrementReviewsCount(ReviewableContract $reviewable);


    /**
     * Increment the total review count stored in the counter.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @return void
     */
    public function incrementReviewsCount(ReviewableContract $reviewable);


    /**
     * Remove all reviews from reviewable model.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     */
    public function removeModelReviews(ReviewableContract $reviewable);


    /**
     * Get collection of users who reviews entity.
     *
     * @todo Do we need to rely on the Laravel Collections here?
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @return \Illuminate\Support\Collection
     */
    public function collectReviewersOf(ReviewableContract $reviewable);

    /**
     * Fetch reviews counters data.
     *
     * @param string $reviewableType
     * @param string $reviewStar
     * @return array
     *
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     */
    public function fetchReviewsCounters($reviewableType, $reviewStar): array;

}