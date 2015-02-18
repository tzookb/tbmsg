<?php
/**
 * This awesome class written by: tzookb
 * Date: 11/02/15
 */

class TestDispatcher implements \Illuminate\Contracts\Events\Dispatcher {

    /**
     * Register an event listener with the dispatcher.
     *
     * @param  string|array $events
     * @param  mixed $listener
     * @param  int $priority
     * @return void
     */
    public function listen($events, $listener, $priority = 0) {
        // TODO: Implement listen() method.
    }

    /**
     * Determine if a given event has listeners.
     *
     * @param  string $eventName
     * @return bool
     */
    public function hasListeners($eventName) {
        // TODO: Implement hasListeners() method.
    }

    /**
     * Fire an event until the first non-null response is returned.
     *
     * @param  string $event
     * @param  array $payload
     * @return mixed
     */
    public function until($event, $payload = array()) {
        // TODO: Implement until() method.
    }

    /**
     * Fire an event and call the listeners.
     *
     * @param  string $event
     * @param  mixed $payload
     * @param  bool $halt
     * @return array|null
     */
    public function fire($event, $payload = array(), $halt = false) {
        // TODO: Implement fire() method.
    }

    /**
     * Get the event that is currently firing.
     *
     * @return string
     */
    public function firing() {
        // TODO: Implement firing() method.
    }

    /**
     * Remove a set of listeners from the dispatcher.
     *
     * @param  string $event
     * @return void
     */
    public function forget($event) {
        // TODO: Implement forget() method.
    }

    /**
     * Forget all of the queued listeners.
     *
     * @return void
     */
    public function forgetPushed() {
        // TODO: Implement forgetPushed() method.
    }


}