<?php namespace Tzookb\TBMsg;

use Illuminate\Support\ServiceProvider;
use Tzookb\TBMsg\Repositories\EloquentTBMsgRepository;


class TBMsgServiceProvider extends ServiceProvider {

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
		$this->package('tzookb/tbmsg');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $usersTable = \Config::get('tbmsg::config.usersTable', 'users');
        $usersTableKey = \Config::get('tbmsg::config.usersTableKey', 'id');
        $tablePrefix = \Config::get('tbmsg::config.tablePrefix', '');

        $this->app->bind('Tzookb\TBMsg\Repositories\Contracts\iTBMsgRepository',
            function($app) use($tablePrefix, $usersTable, $usersTableKey) {
				$db = $app->make('Illuminate\Database\DatabaseManager');
                return new EloquentTBMsgRepository($tablePrefix, $usersTable, $usersTableKey, $db);
            });

        // Register 'tbmsg'
        $this->app['tbmsg'] = $this->app->share(function($app) {
            return new TBMsg(
                $app['Tzookb\TBMsg\Repositories\Contracts\iTBMsgRepository'],
                $app['events'] //Illuminate\Events\Dispatcher
            );
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}