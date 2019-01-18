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


namespace Gox\Laravel\Reviewable\Review\Models;

use Gox\Contracts\Reviewable\Review\Models\Review as ReviewContract;
use Gox\Laravel\Reviewable\UuidTrait\GenerateUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 * @package Gox\Laravel\Reviewable\Review\Models
 */
class Review extends Model implements ReviewContract
{

    use GenerateUuid;


    /**
     * We are using uuids..
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'star',
        'comment'
    ];

    /**
     * Reviewable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

}