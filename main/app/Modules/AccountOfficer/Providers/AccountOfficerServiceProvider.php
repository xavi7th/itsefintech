<?php

namespace App\Modules\AccountOfficer\Providers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\AccountOfficer\Models\AccountOfficer;
use App\Modules\AccountOfficer\Http\Middleware\OnlyAccountOfficers;
use App\Modules\AccountOfficers\Http\Middleware\VerifiedAccountOfficers;

class AccountOfficerServiceProvider extends ServiceProvider
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
		$this->loadMigrationsFrom(module_path('AccountOfficer', 'Database/Migrations'));


		/**** Register the modules middlewares *****/
		app()->make('router')->aliasMiddleware('account_officers', OnlyAccountOfficers::class);
		app()->make('router')->aliasMiddleware('verified_account_officers', VerifiedAccountOfficers::class);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(RouteServiceProvider::class);

		SessionGuard::macro('accountOfficer', function () {
			return AccountOfficer::find(Auth::guard('account_officer')->id());
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
			module_path('AccountOfficer', 'Config/config.php') => config_path('accountofficer.php'),
		], 'config');
		$this->mergeConfigFrom(
			module_path('AccountOfficer', 'Config/config.php'),
			'accountofficer'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = resource_path('views/modules/accountofficer');

		$sourcePath = module_path('AccountOfficer', 'Resources/views');

		$this->publishes([
			$sourcePath => $viewPath
		], 'views');

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/accountofficer';
		}, \Config::get('view.paths')), [$sourcePath]), 'accountofficer');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = resource_path('lang/modules/accountofficer');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'accountofficer');
		} else {
			$this->loadTranslationsFrom(module_path('AccountOfficer', 'Resources/lang'), 'accountofficer');
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
			app(Factory::class)->load(module_path('AccountOfficer', 'Database/factories'));
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
