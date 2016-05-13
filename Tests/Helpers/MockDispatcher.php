<?php
/**
 * Created by PhpStorm.
 * User: tzookb
 * Date: 22/03/16
 * Time: 11:37
 */

namespace Tzookb\TBMsg\Tests\Helpers;


use Illuminate\Contracts\Events\Dispatcher;

class MockDispatcher implements Dispatcher
{

    /**
     * Register an event listener with the dispatcher.
     *
     * @param  string|array $events
     * @param  mixed $listener
     * @param  int $priority
     * @return void
     */
    public function listen($events, $listener, $priority = 0)
    {
        // TODO: Implement listen() method.
    }

    /**
     * Determine if a given event has listeners.
     *
     * @param  string $eventName
     * @return bool
     */
    public function hasListeners($eventName)
    {
        // TODO: Implement hasListeners() method.
    }

    /**
     * Register an event and payload to be fired later.
     *
     * @param  string $event
     * @param  array $payload
     * @return void
     */
    public function push($event, $payload = [])
    {
        // TODO: Implement push() method.
    }

    /**
     * Register an event subscriber with the dispatcher.
     *
     * @param  object|string $subscriber
     * @return void
     */
    public function subscribe($subscriber)
    {
        // TODO: Implement subscribe() method.
    }

    /**
     * Fire an event until the first non-null response is returned.
     *
     * @param  string $event
     * @param  array $payload
     * @return mixed
     */
    public function until($event, $payload = [])
    {
        // TODO: Implement until() method.
    }

    /**
     * Flush a set of pushed events.
     *
     * @param  string $event
     * @return void
     */
    public function flush($event)
    {
        // TODO: Implement flush() method.
    }

    /**
     * Fire an event and call the listeners.
     *
     * @param  string|object $event
     * @param  mixed $payload
     * @param  bool $halt
     * @return array|null
     */
    public function fire($event, $payload = [], $halt = false)
    {
        // TODO: Implement fire() method.
    }

    /**
     * Get the event that is currently firing.
     *
     * @return string
     */
    public function firing()
    {
        // TODO: Implement firing() method.
    }

    /**
     * Remove a set of listeners from the dispatcher.
     *
     * @param  string $event
     * @return void
     */
    public function forget($event)
    {
        // TODO: Implement forget() method.
    }

    /**
     * Forget all of the queued listeners.
     *
     * @return void
     */
    public function forgetPushed()
    {
        // TODO: Implement forgetPushed() method.
    }
}