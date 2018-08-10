# Parable DI Container

[![Build Status](https://travis-ci.org/parable-php/event.svg?branch=master)](https://travis-ci.org/parable-php/event)
[![Latest Stable Version](https://poser.pugx.org/parable-php/event/v/stable)](https://packagist.org/packages/parable-php/event)
[![Latest Unstable Version](https://poser.pugx.org/parable-php/event/v/unstable)](https://packagist.org/packages/parable-php/event)
[![License](https://poser.pugx.org/parable-php/event/license)](https://packagist.org/packages/parable-php/event)

Parable Event is a straightforward event system that gets the job done.

## Install

Php 7.1+ and [composer](https://getcomposer.org) are required.

```bash
$ composer require parable-php/event
```

## Usage

Events are very simple. You add listeners to events (`string` values) and then trigger an update with those events. You 
can pass payloads into the update calls, which will get passed to all relevant listeners. 

```php
use \Parable\Event\EventManager;

$eventManager = new EventManager();

$eventManager->listen('event_number_one', function (string $event, string &$payload) {
    $payload .= '-updated!';
});

$payload = 'event';

$eventManager->trigger('event_number_one', $payload);

echo $payload;

// output: 'event-updated!'
```

The above example handily shows how to make scalar values modifiable by defining the parameter to the callable as a
reference. Passing objects is generally advisable, but sometimes it's the in-place alteration of string values you
need.

It's also possible to have a listener trigger on every single event update.

```php
$eventManager->listenAll(function (string $event, $payload) {
    echo $event . PHP_EOL;
});
``` 

The above example would simply log all events being updated. This can be handy for debugging, but can also be handy
to listen to a specific subset of events by matching the event, rather than adding a single listener to all individual
events. 

## API

- `listen(string $event, callable $$listener): void` - add listener to an event
- `listenAll(string $event): void` - add listener for all events
- `update(string $event): void` - trigger an update for an event

## Contributing

Any suggestions, bug reports or general feedback is welcome. Use github issues and pull requests, or find me over at [devvoh.com](https://devvoh.com).

## License

All Parable components are open-source software, licensed under the MIT license.