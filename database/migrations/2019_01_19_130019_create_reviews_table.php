<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('reviewable_id')->nullable();
            $table->uuid('reviewable_type')->nullable();
            $table->uuid('user_id')->nullable()->index();
            $table->tinyInteger('star')->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique([
                'reviewable_type',
                'reviewable_id',
                'user_id',
            ], 'review_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
