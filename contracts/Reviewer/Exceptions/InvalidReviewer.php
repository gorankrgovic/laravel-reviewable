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


namespace Gox\Contracts\Reviewable\Reviewer\Exceptions;

use RuntimeException;

/**
 * Class InvalidReviewer
 * @package Gox\Contracts\Reviewable\Reviewer\Exceptions
 */
class InvalidReviewer extends RuntimeException
{
    public static function notDefined()
    {
        return new static('Reviewer not defined.');
    }
}