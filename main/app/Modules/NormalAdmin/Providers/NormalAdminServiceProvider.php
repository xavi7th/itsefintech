<?php

namespace App\Modules\NormalAdmin\Providers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use App\Modules\Admin\Http\Middleware\OnlyNormalAdmins;
use App\Modules\Admin\Http\Middleware\VerifiedNormalAdmins;

class NormalAdminServiceProvider extends ServiceProvider
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
		$this->loadMigrationsFrom(module_path('NormalAdmin', 'Database/Migrations'));


		/**** Register the modules middlewares *****/
		app()->make('router')->aliasMiddleware('normal_admins', OnlyNormalAdmins::class);
		app()->make('router')->aliasMiddleware('verified_normal_admins', VerifiedNormalAdmins::class);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(RouteServiceProvider::class);

		SessionGuard::macro('normalAdmin', function () {
			return NormalAdmin::find(Auth::guard('normal_admin')->id());
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
			module_path('NormalAdmin', 'Config/config.php') => config_path('normaladmin.php'),
		], 'config');
		$this->mergeConfigFrom(
			module_path('NormalAdmin', 'Config/config.php'),
			'normaladmin'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = resource_path('views/modules/normaladmin');

		$sourcePath = module_path('NormalAdmin', 'Resources/views');

		$this->publishes([
			$sourcePath => $viewPath
		], 'views');

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/normaladmin';
		}, \Config::get('view.paths')), [$sourcePath]), 'normaladmin');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = resource_path('lang/modules/normaladmin');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'normaladmin');
		} else {
			$this->loadTranslationsFrom(module_path('NormalAdmin', 'Resources/lang'), 'normaladmin');
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
			app(Factory::class)->load(module_path('NormalAdmin', 'Database/factories'));
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
