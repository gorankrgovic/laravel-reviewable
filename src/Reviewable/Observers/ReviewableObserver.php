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


namespace Gox\Laravel\Reviewable\Reviewable\Observers;

use Gox\Contracts\Reviewable\Reviewable\Models\Reviewable as ReviewableContract;

/**
 * Class ReviewableObserver
 * @package Gox\Laravel\Reviewable\Reviewable\Observers
 */
class ReviewableObserver
{

    /**
     * Handle method on delete
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @return void
     */
    public function deleted(ReviewableContract $reviewable)
    {
        if (!$this->removeReviewsOnDelete($reviewable)) {
            return;
        }

        $reviewable->removeReviews();
    }

    /**
     * Review on delete
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @return bool
     */
    private function removeReviewsOnDelete(ReviewableContract $reviewable): bool
    {
        return $reviewable->removeReviewsOnDelete ?? true;
    }

}