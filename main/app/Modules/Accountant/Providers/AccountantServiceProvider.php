<?php

namespace App\Modules\Accountant\Providers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\Accountant\Http\Middleware\OnlyAccountants;
use App\Modules\Accountant\Http\Middleware\VerifiedAccountants;

class AccountantServiceProvider extends ServiceProvider
{
	/**
	 * Boot the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// $this->registerTranslations();
		// $this->registerConfig();
		$this->registerViews();
		$this->registerFactories();
		$this->loadMigrationsFrom(module_path('Accountant', 'Database/Migrations'));

		/**** Register the modules middlewares *****/
		app()->make('router')->aliasMiddleware('accountants', OnlyAccountants::class);
		app()->make('router')->aliasMiddleware('verified_accountants', VerifiedAccountants::class);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(RouteServiceProvider::class);

		SessionGuard::macro('accountant', function () {
			return Accountant::find(Auth::guard('accountant')->id());
		});
	}

	/**
	 * Register config.
	 *
	 * @return void
	 */
	protected function registerConfig()
	{
		$this->publishes([
			module_path('Accountant', 'Config/config.php') => config_path('accountant.php'),
		], 'config');
		$this->mergeConfigFrom(
			module_path('Accountant', 'Config/config.php'),
			'accountant'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = resource_path('views/modules/accountant');

		$sourcePath = module_path('Accountant', 'Resources/views');

		$this->publishes([
			$sourcePath => $viewPath
		], 'views');

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/accountant';
		}, \Config::get('view.paths')), [$sourcePath]), 'accountant');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = resource_path('lang/modules/accountant');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'accountant');
		} else {
			$this->loadTranslationsFrom(module_path('Accountant', 'Resources/lang'), 'accountant');
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
			app(Factory::class)->load(module_path('Accountant', 'Database/factories'));
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
