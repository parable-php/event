<?php declare(strict_types=1);

namespace Parable\Di\Tests\Classes;

use Parable\Di\Container;

class DiAsDependency
{
    public $container;

    public function __construct(
        Container $container
    ) {
        $this->container = $container;
    }
}
