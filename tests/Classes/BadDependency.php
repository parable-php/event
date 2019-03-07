<?php declare(strict_types=1);

namespace Parable\Di\Tests\Classes;

class BadDependency
{
    public function __construct(
        string $nope
    ) {
    }
}
