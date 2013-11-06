<?php namespace Mednix\Login;

use Illuminate\Support\ServiceProvider;
use Mednix\Login\Login;
class LoginServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('mednix/login');

        include __DIR__.'/../../filters.php';
        include __DIR__.'/../../routes.php';

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['login']=$this->app->share(function($app){
            $layout=\Config::get('login.layout');
            return new Login($layout);
        });
        $this->app['login.install'] = $this->app->share(function($app)
        {


            return new Console\LoginInstallCommand($app['files']);
        });
        $this->app['login.uninstall'] = $this->app->share(function($app)
        {
            return new Console\LoginUninstallCommand($app['files'],$app['migration.repository'],$app['migrator']);
        });
        $this->commands('login.install','login.uninstall');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('login.install','login.uninstall');
	}

}