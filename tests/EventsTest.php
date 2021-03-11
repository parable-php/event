<?php declare(strict_types=1);

namespace Parable\Event\Tests;

use Parable\Event\Events;
use Parable\Event\EventsException;
use PHPUnit\Framework\TestCase;

class EventsTest extends TestCase
{
    protected Events $events;

    public function setUp(): void
    {
        $this->events = new Events();
    }

    public function testListenToEventAndUpdate(): void
    {
        $this->events->listen('test_event', static function ($event, string &$payload) {
            $payload .= "-suffixed";
        });

        $payload = 'payload';

        $this->events->trigger('test_event', $payload);

        self::assertSame('payload-suffixed', $payload);
    }

    public function testMultipleEvents(): void
    {
        $this->events->listen('test_event', static function ($event, string &$payload) {
            $payload .= "-suffixed";
        });
        $this->events->listen('test_event', static function ($event, string &$payload) {
            $payload .= "-twice!";
        });

        $payload = 'payload';

        $this->events->trigger('test_event', $payload);

        self::assertSame('payload-suffixed-twice!', $payload);
    }

    public function testSameEventMultipleTimesGetsCalledOnce(): void
    {
        $closure = static function ($event, string &$payload) {
            $payload .= "-suffixed";
        };

        $this->events->listen('test_event', $closure);
        $this->events->listen('test_event', $closure);

        $payload = 'payload';

        $this->events->trigger('test_event', $payload);

        self::assertSame('payload-suffixed', $payload);
    }

    public function testGlobalListeners(): void
    {
        $this->events->listenAll(static function ($event, string &$payload) {
            $payload .= "-suffixed";
        });

        $payload = 'payload';

        $this->events->trigger('once update', $payload);
        $this->events->trigger('twice update', $payload);

        self::assertSame('payload-suffixed-suffixed', $payload);
    }

    public function testCannotTriggerGlobalEvent(): void
    {
        $this->expectException(EventsException::class);
        $this->expectExceptionMessage('Cannot trigger global event, only listen to it.');

        $this->events->trigger('*');
    }
}
