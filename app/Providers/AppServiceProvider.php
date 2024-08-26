<?php

namespace App\Providers;

use App\Interfaces\GameStateInterface;
use App\Services\GameStateService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(GameStateInterface::class, GameStateService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        JsonResource::withoutWrapping();
    }
}
