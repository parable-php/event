<?php declare(strict_types=1);

namespace Parable\Event;

class Events
{
    protected const GLOBAL_EVENT = '*';

    /** @var callable[][] */
    protected array $listeners = [];

    public function listen(string $event, callable $listener): void
    {
        $this->listeners[$event][spl_object_hash((object)$listener)] = $listener;
    }

    public function listenAll(callable $listener): void
    {
        $this->listen(self::GLOBAL_EVENT, $listener);
    }

    /**
     * Trigger the event and run all callables associated with it. If the
     * payload is an object, listeners may have changed its state during runtime.
     *
     * If you want to pass a scalar value and have it modifiable, define the
     * payload as a reference in the listener callback parameter list.
     *
     * Example: trigger('event', function (string $event, &$payload) { ... }
     */
    public function trigger(string $event, &$payload = null)
    {
        if ($event === self::GLOBAL_EVENT) {
            throw new EventsException('Cannot trigger global event, only listen to it.');
        }

        foreach ($this->getListeners($event) as $listener) {
            $listener($event, $payload);
        }
    }

    /**
     * Return the callables associated with the event.
     *
     * @return callable[]
     */
    protected function getListeners(string $event): array
    {
        $listeners = $this->listeners[$event] ?? [];
        $globalListeners = $this->listeners[self::GLOBAL_EVENT] ?? [];

        return array_merge($listeners, $globalListeners);
    }
}
