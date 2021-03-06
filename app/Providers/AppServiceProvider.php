<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Orchid\Screen\Field;

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

        Blueprint::macro('money', function ($column) {
            return $this->decimal($column, 40, 25);
        });

        // Schema::defaultStringLength(191);

        Field::macro('addAttribute', function ($name, $value) {
            $this->inlineAttributes[] = $name;

            $this->attributes[$name] = $value;

            return $this;
        });
    }
}
