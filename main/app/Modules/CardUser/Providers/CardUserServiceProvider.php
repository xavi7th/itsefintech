<?php

namespace App\Modules\CardUser\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\CardUser\Http\Middleware\OnlyCardUsers;
use App\Modules\CardUser\Http\Middleware\OnlyVerifiedCardUsers;
use App\Modules\CardUser\Http\Middleware\OnlyUnverifiedCardUsers;

class CardUserServiceProvider extends ServiceProvider
{
	/**
	 * Boot the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//        $this->registerTranslations();
		//        $this->registerConfig();
		$this->registerViews();
		$this->registerFactories();
		$this->loadMigrationsFrom(module_path('CardUser', 'Database/Migrations'));

		/**** Register the modules middlewares *****/
		app()->make('router')->aliasMiddleware('card_users', OnlyCardUsers::class);
		app()->make('router')->aliasMiddleware('verified_card_users', OnlyVerifiedCardUsers::class);
		app()->make('router')->aliasMiddleware('unverified_card_users', OnlyUnverifiedCardUsers::class);
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
			module_path('CardUser', 'Config/config.php') => config_path('carduser.php'),
		], 'config');
		$this->mergeConfigFrom(
			module_path('CardUser', 'Config/config.php'),
			'carduser'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = resource_path('views/modules/carduser');

		$sourcePath = module_path('CardUser', 'Resources/views');

		$this->publishes([
			$sourcePath => $viewPath
		], 'views');

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/carduser';
		}, \Config::get('view.paths')), [$sourcePath]), 'carduser');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = resource_path('lang/modules/carduser');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'carduser');
		} else {
			$this->loadTranslationsFrom(module_path('CardUser', 'Resources/lang'), 'carduser');
		}
	}

	/**
	 * Register an additional directory of factories.
	 *
	 * @return void
	 */
	public function registerFactories()
	{
		if (!app()->environment('production') && $this->app->runningInConsole()) {
			app(Factory::class)->load(module_path('CardUser', 'Database/factories'));
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
