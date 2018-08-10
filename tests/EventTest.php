<?php

namespace Parable\Event\Tests;

use Parable\Event\EventManager;
use Parable\Event\Exception;

class EventManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var EventManager */
    protected $eventManager;

    public function setUp()
    {
        $this->eventManager = new \Parable\Event\EventManager();
    }

    public function testListenToEventAndUpdate()
    {
        $this->eventManager->listen('test_event', function ($event, string &$payload) {
            $payload .= "-suffixed";
        });

        $payload = 'payload';

        $this->eventManager->update('test_event', $payload);

        self::assertSame('payload-suffixed', $payload);
    }

    public function testMultipleEvents()
    {
        $this->eventManager->listen('test_event', function ($event, string &$payload) {
            $payload .= "-suffixed";
        });
        $this->eventManager->listen('test_event', function ($event, string &$payload) {
            $payload .= "-twice!";
        });

        $payload = 'payload';

        $this->eventManager->update('test_event', $payload);

        self::assertSame('payload-suffixed-twice!', $payload);
    }

    public function testSameEventMultipleTimesGetsCalledOnce()
    {
        $closure = function ($event, string &$payload) {
            $payload .= "-suffixed";
        };

        $this->eventManager->listen('test_event', $closure);
        $this->eventManager->listen('test_event', $closure);

        $payload = 'payload';

        $this->eventManager->update('test_event', $payload);

        self::assertSame('payload-suffixed', $payload);
    }

    public function testGlobalListeners()
    {
        $this->eventManager->listenAll(function ($event, string &$payload) {
            $payload .= "-suffixed";
        });

        $payload = 'payload';

        $this->eventManager->update('once update', $payload);
        $this->eventManager->update('twice update', $payload);

        self::assertSame('payload-suffixed-suffixed', $payload);
    }

    public function testCannotTriggerGlobalEvent()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Cannot specifically trigger global event.');

        $this->eventManager->update('*');
    }
}
