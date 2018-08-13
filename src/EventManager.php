<?php

namespace Parable\Event;

class EventManager
{
    private const GLOBAL_EVENT = '*';
    /**
     * @var callable[][]
     */
    protected $listeners = [];

    /**
     * Add the callable to the listeners for this event.
     */
    public function listen(string $event, callable $listener): void
    {
        $this->listeners[$event][spl_object_hash((object)$listener)] = $listener;
    }

    /**
     * Add the callable to the global listener list.
     */
    public function listenAll(callable $listener): void
    {
        $this->listen(self::GLOBAL_EVENT, $listener);
    }

    /**
     * Trigger the event and run all callables associated with it. If the
     * payload is an object, listeners may have changed its state during runtime.
     *
     * If you want to pass a scalar value and have it modifiable, define the
     * payload as a reference in the listener callback parameter list..
     *
     * @param null|mixed $payload
     */
    public function trigger(string $event, &$payload = null)
    {
        if ($event === self::GLOBAL_EVENT) {
            throw new Exception('Cannot specifically trigger global event.');
        }

        foreach ($this->getListeners($event) as $listener) {
            $listener($event, $payload);
        }
    }

    /**
     * Return the callables associated with the event.
     *
     * @return callable[][]
     */
    protected function getListeners(string $event): array
    {
        $listeners       = $this->listeners[$event]             ?? [];
        $globalListeners = $this->listeners[self::GLOBAL_EVENT] ?? [];

        return array_merge($listeners, $globalListeners);
    }
}
