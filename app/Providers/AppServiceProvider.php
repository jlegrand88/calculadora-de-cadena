<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\ICalculatorService;
use App\Services\CalculatorService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ICalculatorService::class, CalculatorService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
