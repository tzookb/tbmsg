<?php namespace Tzookb\TBMsg;

use Illuminate\Support\ServiceProvider;
use Tzookb\TBMsg\Domain\Repositories\ConversationRepository;
use Tzookb\TBMsg\Domain\Repositories\MessageStatusRepository;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentConversationRepository;
use Tzookb\TBMsg\Persistence\Eloquent\EloquentMessageStatusRepository;


class TBMsgServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    public function boot() {
        $this->publishes([
            __DIR__.'/../../config/tbmsg.php' => config_path('tbmsg.php'),
        ], 'config');

        // Publish your migrations
        $this->publishes([
            __DIR__.'/../../migrations/' => base_path('/database/migrations')
        ], 'migrations');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $usersTable = \Config::get('tbmsg.usersTable', 'users');
        $usersTableKey = \Config::get('tbmsg.usersTableKey', 'id');
        $tablePrefix = \Config::get('tbmsg.tablePrefix', '');

        $this->app->bind(ConversationRepository::class, EloquentConversationRepository::class);
        $this->app->bind(MessageStatusRepository::class, EloquentMessageStatusRepository::class);

        // Register 'tbmsg'
        $this->app['tbmsg'] = $this->app->share(function($app) {
            return new TBMsg(
                $app['Tzookb\TBMsg\Repositories\Contracts\iTBMsgRepository'],
                $app['Illuminate\Contracts\Events\Dispatcher'] //Illuminate\Events\Dispatcher
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