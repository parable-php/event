# Parable Event

## 1.0.0

Just a re-release locking the interface in place. First final release!

## 0.4.0

_Changes_
- Add static analysis using psalm.
- `EventManager` has been renamed to `Events`.
- `Exception` has been renamed to `EventsException` for clarity.

## 0.3.0

_Changes_
- Dropped support for php7, php8 only from now on.

## 0.2.2

_Changes_

- Removed type hint for payload on `trigger`.

## 0.2.1

_Changes_

- `EventManager::GLOBAL_EVENT` was private, but through developing other Parable packages, the decision was made to never be more restrictive than protected.
- All files define strict types now.
- Code clean-up.

## 0.2.0

_Changes_

- Rename `update()` to `trigger()`.
- Update README to show the correct API.

## 0.1.0

_Changes_

- First release.
