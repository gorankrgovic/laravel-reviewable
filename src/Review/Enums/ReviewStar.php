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


namespace Gox\Laravel\Reviewable\Review\Enums;

/**
 * Class ReviewStar
 * @package Gox\Laravel\Reviewable\Review\Enums
 */
class ReviewStar
{
    const RATE_POOR = 1;
    const RATE_BAD = 2;
    const RATE_OK = 3;
    const RATE_GOOD = 4;
    const RATE_GREAT = 5;
}