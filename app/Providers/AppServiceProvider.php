<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once(__DIR__ . '/../Support/helpers.php');

        Collection::macro('onlyAttr', function ($keys) {
            $keys = is_array($keys)
                ? $keys
                : [$keys];

            return $this->map(function ($value) use ($keys) {
                return collect($value)
                    ->only($keys)
                    ->toArray();
            });
        });
    }
}
