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


namespace Gox\Contracts\Reviewable\Reviewer\Models;

use Gox\Contracts\Reviewable\Reviewable\Models\Reviewable;

/**
 * Interface Reviewer
 * @package Gox\Contracts\Reviewable\Reviewer\Models
 */
interface Reviewer
{

    /**
     * Add a review for the Reviewable model.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param $star
     * @param $comment
     * @return void
     */
    public function review(Reviewable $reviewable, $star, $comment = null);


    /**
     * Add a review for the Reviewable model.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param $comment
     * @return void
     */
    public function reviewPoor(Reviewable $reviewable, $comment = null);

    /**
     * Add a review for the Reviewable model.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param $comment
     * @return void
     */
    public function reviewBad(Reviewable $reviewable, $comment = null);

    /**
     * Add a review for the Reviewable model.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param $comment
     * @return void
     */
    public function reviewOk(Reviewable $reviewable, $comment = null);


    /**
     * Add a review for the Reviewable model.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param $comment
     * @return void
     */
    public function reviewGood(Reviewable $reviewable, $comment = null);


    /**
     * Add a review for the Reviewable model.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param $comment
     * @return void
     */
    public function reviewGreat(Reviewable $reviewable, $comment = null);

    /**
     * Remove a review
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $likeable
     * @return void
     */
    public function unreview(Reviewable $likeable);


    /**
     * Determine if Reviewer has reviewed Reviewable model.
     *
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @return bool
     */
    public function hasReviewed(Reviewable $reviewable): bool;

}