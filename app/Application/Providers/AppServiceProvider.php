<?php

namespace App\Application\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(\App\Service\Model\Auth\AuthService::class, function ($app) {
            return new \App\Service\Model\Auth\AuthService(
                $app->make(\Optimus\ApiConsumer\Router::class),
                $app->make(\App\Domain\Service\User\AccountService),
                $app->make(\App\Domain\Model\Authentication\User\UserRepository)
            );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
