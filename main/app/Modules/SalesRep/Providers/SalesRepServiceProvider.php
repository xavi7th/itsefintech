<?php

namespace App\Modules\SalesRep\Providers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\SalesRep\Http\Middleware\OnlySalesReps;
use App\Modules\SalesRep\Http\Middleware\VerifiedSalesReps;

class SalesRepServiceProvider extends ServiceProvider
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
		$this->loadMigrationsFrom(module_path('SalesRep', 'Database/Migrations'));

		/**** Register the modules middlewares *****/
		app()->make('router')->aliasMiddleware('sales_reps', OnlySalesReps::class);
		app()->make('router')->aliasMiddleware('verified_sales_reps', VerifiedSalesReps::class);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(RouteServiceProvider::class);

		SessionGuard::macro('salesRep', function () {
			return SalesRep::find(Auth::guard('sales_rep')->id());
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
			module_path('SalesRep', 'Config/config.php') => config_path('salesrep.php'),
		], 'config');
		$this->mergeConfigFrom(
			module_path('SalesRep', 'Config/config.php'),
			'salesrep'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = resource_path('views/modules/salesrep');

		$sourcePath = module_path('SalesRep', 'Resources/views');

		$this->publishes([
			$sourcePath => $viewPath
		], 'views');

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/salesrep';
		}, \Config::get('view.paths')), [$sourcePath]), 'salesrep');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = resource_path('lang/modules/salesrep');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'salesrep');
		} else {
			$this->loadTranslationsFrom(module_path('SalesRep', 'Resources/lang'), 'salesrep');
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
			app(Factory::class)->load(module_path('SalesRep', 'Database/factories'));
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
