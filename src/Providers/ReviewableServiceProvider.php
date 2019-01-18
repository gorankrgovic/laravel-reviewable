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


namespace Gox\Laravel\Reviewable\Providers;

use Gox\Contracts\Reviewable\Review\Models\Review as ReviewContract;
use Gox\Contracts\Reviewable\ReviewCounter\Models\ReviewCounter as ReviewCounterContract;
use Gox\Contracts\Reviewable\Reviewable\Services\ReviewableService as ReviewableServiceContract;
use Gox\Laravel\Reviewable\Review\Models\Review;
use Gox\Laravel\Reviewable\Review\Observers\ReviewObserver;
use Gox\Laravel\Reviewable\Reviewable\Services\ReviewableService;
use Gox\Laravel\Reviewable\ReviewCounter\Models\ReviewCounter;
use Illuminate\Support\ServiceProvider;


class ReviewableServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerObservers();
        $this->registerPublishes();
        $this->registerMigrations();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerContracts();
    }


    /**
     * Register models observers.
     *
     * @return void
     */
    protected function registerObservers()
    {
        $this->app->make(ReviewContract::class)->observe(ReviewObserver::class);
    }

    /**
     * Register classes in the container.
     *
     * @return void
     */
    protected function registerContracts()
    {
        $this->app->bind(ReviewContract::class, Review::class);
        $this->app->bind(ReviewCounterContract::class, ReviewCounter::class);
        $this->app->singleton(ReviewableServiceContract::class, ReviewableService::class);
    }

    /**
     * Setup the resource publishing groups
     *
     * @return void
     */
    protected function registerPublishes()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'migrations');
        }
    }

    /**
     * Register the migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }

}