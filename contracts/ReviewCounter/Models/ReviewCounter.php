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


namespace Gox\Contracts\Reviewable\ReviewCounter\Models;

/**
 * Interface ReviewCounter
 * @package Gox\Contracts\Reviewable\ReviewCounter\Models
 */
interface ReviewCounter
{
    /**
     * Reviewable relation
     *
     * @return mixed
     */
    public function reviewable();
}