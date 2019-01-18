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


namespace Gox\Contracts\Reviewable\Reviewable\Exceptions;

use RuntimeException;

/**
 * Class InvalidReviewable
 * @package Gox\Contracts\Reviewable\Reviewable\Exceptions
 */
class InvalidReviewable extends RuntimeException
{

    /**
     * @param string $type
     * @return InvalidReviewable
     */
    public static function notExists(string $type)
    {
        return new static("{$type} class or morph map not found");
    }


    /**
     * @param string $type
     * @return InvalidReviewable
     */
    public static function notImplementInterface(string $type)
    {
        return new static ("[{$type}] must implement `\Gox\Contracts\Reviewable\Reviewable\Models\Reviewable` contract");
    }
}