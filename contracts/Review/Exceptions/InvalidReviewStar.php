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

namespace Gox\Contracts\Reviewable\Review\Exceptions;

use RuntimeException;

/**
 * Class InvalidReviewStar
 * @package Gox\Contracts\Reviewable\Review\Exceptions
 */
class InvalidReviewStar extends RuntimeException
{
    /**
     * Invalid star provided
     *
     * @param int $star
     * @return InvalidReviewStar
     */
    public static function notExists(int $star)
    {
        return new static("Review star `{$star}` not allowed.");
    }
}