<?php declare(strict_types=1);

namespace Parable\Event\Tests;

use Parable\Event\EventManager;
use Parable\Event\Exception;
use PHPUnit\Framework\TestCase;

class EventManagerTest extends TestCase
{
    protected EventManager $eventManager;

    public function setUp(): void
    {
        $this->eventManager = new EventManager();
    }

    public function testListenToEventAndUpdate(): void
    {
        $this->eventManager->listen('test_event', static function ($event, string &$payload) {
            $payload .= "-suffixed";
        });

        $payload = 'payload';

        $this->eventManager->trigger('test_event', $payload);

        self::assertSame('payload-suffixed', $payload);
    }

    public function testMultipleEvents(): void
    {
        $this->eventManager->listen('test_event', static function ($event, string &$payload) {
            $payload .= "-suffixed";
        });
        $this->eventManager->listen('test_event', static function ($event, string &$payload) {
            $payload .= "-twice!";
        });

        $payload = 'payload';

        $this->eventManager->trigger('test_event', $payload);

        self::assertSame('payload-suffixed-twice!', $payload);
    }

    public function testSameEventMultipleTimesGetsCalledOnce(): void
    {
        $closure = static function ($event, string &$payload) {
            $payload .= "-suffixed";
        };

        $this->eventManager->listen('test_event', $closure);
        $this->eventManager->listen('test_event', $closure);

        $payload = 'payload';

        $this->eventManager->trigger('test_event', $payload);

        self::assertSame('payload-suffixed', $payload);
    }

    public function testGlobalListeners(): void
    {
        $this->eventManager->listenAll(static function ($event, string &$payload) {
            $payload .= "-suffixed";
        });

        $payload = 'payload';

        $this->eventManager->trigger('once update', $payload);
        $this->eventManager->trigger('twice update', $payload);

        self::assertSame('payload-suffixed-suffixed', $payload);
    }

    public function testCannotTriggerGlobalEvent(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot specifically trigger global event.');

        $this->eventManager->trigger('*');
    }
}
