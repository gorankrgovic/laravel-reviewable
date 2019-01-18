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


namespace Gox\Laravel\Reviewable\Reviewer\Models\Traits;

use Gox\Contracts\Reviewable\Reviewable\Models\Reviewable as ReviewableContract;
use Gox\Contracts\Reviewable\Reviewable\Services\ReviewableService as ReviewableServiceContract;
use Gox\Laravel\Reviewable\Review\Enums\ReviewStar;


trait Reviewer
{

    /**
     * Ability for a reviewer to review
     *
     * @param ReviewableContract $reviewable
     * @param $star
     * @param null $comment
     */
    public function review(ReviewableContract $reviewable, $star, $comment = null)
    {
        app(ReviewableServiceContract::class)->addReviewTo($reviewable, $star, $comment, $this);
    }


    /**
     * @param ReviewableContract $reviewable
     * @param null $comment
     */
    public function reviewPoor(ReviewableContract $reviewable, $comment = null)
    {
        $this->review($reviewable, ReviewStar::RATE_POOR, $comment);
    }

    /**
     * @param ReviewableContract $reviewable
     * @param null $comment
     */
    public function reviewBad(ReviewableContract $reviewable, $comment = null)
    {
        $this->review($reviewable, ReviewStar::RATE_BAD, $comment);
    }

    /**
     * @param ReviewableContract $reviewable
     * @param null $comment
     */
    public function reviewOk(ReviewableContract $reviewable, $comment = null)
    {
        $this->review($reviewable, ReviewStar::RATE_OK, $comment);
    }

    /**
     * @param ReviewableContract $reviewable
     * @param null $comment
     */
    public function reviewGood(ReviewableContract $reviewable, $comment = null)
    {
        $this->review($reviewable, ReviewStar::RATE_GOOD, $comment);
    }

    /**
     * @param ReviewableContract $reviewable
     * @param null $comment
     */
    public function reviewGreat(ReviewableContract $reviewable, $comment = null)
    {
        $this->review($reviewable, ReviewStar::RATE_GREAT, $comment);
    }

    /**
     * Unreview a model (delete the review :) )
     *
     * @param ReviewableContract $reviewable
     */
    public function unreview(ReviewableContract $reviewable) {
        app(ReviewableServiceContract::class)->removeReviewFrom($reviewable, $this);
    }

    /**
     * @param ReviewableContract $reviewable
     * @return bool
     */
    public function hasReviewed(ReviewableContract $reviewable): bool
    {
        return app(ReviewableServiceContract::class)->isReviewed($reviewable, $this);
    }
}