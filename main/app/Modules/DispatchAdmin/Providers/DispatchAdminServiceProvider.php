<?php

namespace App\Modules\DispatchAdmin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class DispatchAdminServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('DispatchAdmin', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('DispatchAdmin', 'Config/config.php') => config_path('dispatchadmin.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('DispatchAdmin', 'Config/config.php'), 'dispatchadmin'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/dispatchadmin');

        $sourcePath = module_path('DispatchAdmin', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/dispatchadmin';
        }, \Config::get('view.paths')), [$sourcePath]), 'dispatchadmin');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/dispatchadmin');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'dispatchadmin');
        } else {
            $this->loadTranslationsFrom(module_path('DispatchAdmin', 'Resources/lang'), 'dispatchadmin');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('DispatchAdmin', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
