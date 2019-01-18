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

namespace Gox\Contracts\Reviewable\Reviewable\Models;


interface Reviewable
{

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();


    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass();


    /**
     * Collection of the reviews on this record.
     *
     * @return mixed
     */
    public function reviews();


    /**
     * Counter is a record that stores the total reviews for the morphed record.
     *
     * @return mixed
     */
    public function reviewsCounter();



    /**
     * Fetch users who reviewed entity.
     *
     * @todo Do we need to rely on the Laravel Collections here?
     * @return \Illuminate\Support\Collection
     */
    public function collectReviewers();


    /**
     * Add a review for model by the given user.
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @param int $star The rating star number.
     * @param int $comment The comment associated with the review.
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     */
    public function reviewBy($star, $comment = null, $userId = null);



    /**
     * Review helper by user - poor
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @param int $comment The comment associated with the review.
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     */
    public function reviewPoorBy($comment = null, $userId = null);


    /**
     * Review helper by user - bad
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @param int $comment The comment associated with the review.
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     */
    public function reviewBadBy($comment = null, $userId = null);

    /**
     * Review helper by user - OK
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @param int $comment The comment associated with the review.
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     */
    public function reviewOkBy($comment = null, $userId = null);


    /**
     * Review helper by user - good
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @param int $comment The comment associated with the review.
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     */
    public function reviewGoodBy($comment = null, $userId = null);


    /**
     * Review helper by user - great
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @param int $comment The comment associated with the review.
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     * @throws \Gox\Contracts\Reviewable\Review\Exceptions\InvalidReviewStar
     */
    public function reviewGreatBy($comment = null, $userId = null);


    /**
     * Remove a review from this record for the given user.
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @return void
     *
     * @throws \Gox\Contracts\Reviewable\Reviewer\Exceptions\InvalidReviewer
     */
    public function unreviewBy($userId = null);

    /**
     * Delete reviews related to the current record.
     *
     * @return void
     */
    public function removeReviews();


    /**
     * Has the user already reviewed reviewable model.
     *
     * @param null|string|int $userId
     * @return bool
     */
    public function isReviewedBy($userId = null): bool;

}