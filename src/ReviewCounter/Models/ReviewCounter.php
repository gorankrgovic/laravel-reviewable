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


namespace Gox\Laravel\Reviewable\ReviewCounter\Models;

use Gox\Contracts\Reviewable\ReviewCounter\Models\ReviewCounter as ReviewCounterContract;
use Gox\Laravel\Reviewable\UuidTrait\GenerateUuid;
use Illuminate\Database\Eloquent\Model;

class ReviewCounter extends Model implements ReviewCounterContract
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
    protected $table = 'review_counters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'count',
        'avg_rating'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'count' => 'integer',
        'avg_rating' => 'float'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|mixed
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

}