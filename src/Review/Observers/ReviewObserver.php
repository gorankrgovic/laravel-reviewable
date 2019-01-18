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


namespace Gox\Laravel\Reviewable\Review\Observers;


use Gox\Contracts\Reviewable\Review\Models\Review as ReviewContract;
use Gox\Contracts\Reviewable\Reviewable\Services\ReviewableService as ReviewableServiceContract;
use Gox\Laravel\Reviewable\Review\Enums\ReviewStar;
use Gox\Laravel\Reviewable\Reviewable\Events\ReviewableWasReviewed;
use Gox\Laravel\Reviewable\Reviewable\Events\ReviewableWasUnreviewed;


/**
 * Class ReviewObserver
 * @package Gox\Laravel\Reviewable\Review\Observers
 */
class ReviewObserver
{
    /**
     * @param ReviewContract $review
     */
    public function created(ReviewContract $review)
    {
        event(new ReviewableWasReviewed($review->reviewable, $review->user_id));
        app(ReviewableServiceContract::class)->incrementReviewsCount($review->reviewable);
    }


    /**
     * @param ReviewContract $review
     */
    public function deleted(ReviewContract $review)
    {
        event(new ReviewableWasUnreviewed($review->reviewable, $review->user_id));
        app(ReviewableServiceContract::class)->decrementReviewsCount($review->reviewable);
    }
}