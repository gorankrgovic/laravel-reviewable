<?php

/*
 * This file is part of Laravel Love.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateLoveLikeCountersTable.
 */
class CreateReviewCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_counters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('reviewable_id')->nullable();
            $table->uuid('reviewable_type')->nullable();
            $table->integer('count')->unsigned()->default(0);
            $table->float('avg_rating', 5, 2)->unsigned()->default(0);
            $table->timestamps();

            $table->unique([
                'reviewable_id',
                'reviewable_type'
            ], 'review_counter_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_counters');
    }
}
