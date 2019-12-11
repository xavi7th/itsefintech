<?php

namespace App\Modules\CardAdmin\Providers;

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Modules\CardAdmin\Models\CardAdmin;
use App\Modules\CardAdmin\Http\Middleware\OnlyCardAdmins;
use App\Modules\CardAdmin\Http\Middleware\VerifiedCardAdmins;

class CardAdminServiceProvider extends ServiceProvider
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
		$this->loadMigrationsFrom(module_path('CardAdmin', 'Database/Migrations'));

		/**** Register the modules middlewares *****/
		app()->make('router')->aliasMiddleware('account_officers', OnlyCardAdmins::class);
		app()->make('router')->aliasMiddleware('verified_account_officers', VerifiedCardAdmins::class);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(RouteServiceProvider::class);

		SessionGuard::macro('cardAdmin', function () {
			return CardAdmin::find(Auth::guard('card_admin')->id());
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
			module_path('CardAdmin', 'Config/config.php') => config_path('cardadmin.php'),
		], 'config');
		$this->mergeConfigFrom(
			module_path('CardAdmin', 'Config/config.php'),
			'cardadmin'
		);
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = resource_path('views/modules/cardadmin');

		$sourcePath = module_path('CardAdmin', 'Resources/views');

		$this->publishes([
			$sourcePath => $viewPath
		], 'views');

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/cardadmin';
		}, \Config::get('view.paths')), [$sourcePath]), 'cardadmin');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = resource_path('lang/modules/cardadmin');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'cardadmin');
		} else {
			$this->loadTranslationsFrom(module_path('CardAdmin', 'Resources/lang'), 'cardadmin');
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
			app(Factory::class)->load(module_path('CardAdmin', 'Database/factories'));
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
