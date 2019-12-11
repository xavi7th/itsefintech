<?php

namespace App\Modules\CustomerSupport\Providers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\CustomerSupport\Http\Middleware\OnlyCustomerSupports;
use App\Modules\CustomerSupport\Http\Middleware\VerifiedCustomerSupports;

class CustomerSupportServiceProvider extends ServiceProvider
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
		$this->loadMigrationsFrom(module_path('CustomerSupport', 'Database/Migrations'));

		/**** Register the modules middlewares *****/
		app()->make('router')->aliasMiddleware('customer_supports', OnlyCustomerSupports::class);
		app()->make('router')->aliasMiddleware('verified_customer_supports', VerifiedCustomerSupports::class);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(RouteServiceProvider::class);

		SessionGuard::macro('customerSupport', function () {
			return CustomerSupport::find(Auth::guard('customer_support')->id());
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
			module_path('CustomerSupport', 'Config/config.php') => config_path('customersupport.php'),
		], 'config');
		$this->mergeConfigFrom(
			module_path('CustomerSupport', 'Config/config.php'),
			'customersupport'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = resource_path('views/modules/customersupport');

		$sourcePath = module_path('CustomerSupport', 'Resources/views');

		$this->publishes([
			$sourcePath => $viewPath
		], 'views');

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/customersupport';
		}, \Config::get('view.paths')), [$sourcePath]), 'customersupport');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = resource_path('lang/modules/customersupport');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'customersupport');
		} else {
			$this->loadTranslationsFrom(module_path('CustomerSupport', 'Resources/lang'), 'customersupport');
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
			app(Factory::class)->load(module_path('CustomerSupport', 'Database/factories'));
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
