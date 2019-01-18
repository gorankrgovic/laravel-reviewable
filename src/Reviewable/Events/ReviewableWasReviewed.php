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


namespace Gox\Laravel\Reviewable\Reviewable\Events;

use Gox\Contracts\Reviewable\Reviewable\Models\Reviewable as ReviewableContract;

class ReviewableWasReviewed
{

    /**
     * The reviewed Reviewable model
     *
     * @var \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable
     */
    public $reviewable;

    /**
     * A reviewer id
     *
     * @var string
     */
    public $reviewerId;


    /**
     * Create a new event instance
     *
     * ReviewableWasReviewed constructor.
     * @param \Gox\Contracts\Reviewable\Reviewable\Models\Reviewable $reviewable
     * @param string $reviewerId
     * @return void
     */
    public function __construct(ReviewableContract $reviewable, $reviewerId)
    {
        $this->reviewerId = $reviewerId;
        $this->reviewable = $reviewable;
    }

}