<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $settings = collect();

            if (Schema::hasTable('settings')) {
                $settings = Setting::query()->pluck('value', 'key');
            }

            $locale = app()->getLocale();
            $direction = $locale === 'ar' ? 'rtl' : 'ltr';

            $view->with('globalSettings', $settings);
            $view->with('appLocale', $locale);
            $view->with('appDirection', $direction);
        });
    }
}