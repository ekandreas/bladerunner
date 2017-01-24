<?php
namespace Bladerunner;

use Illuminate\Container\Container as BaseContainer;

class Container extends BaseContainer
{
    /**
     * Get the bladerunner container.
     *
     * @param string $abstract
     * @param array $parameters
     * @param ContainerContract $container
     * @return ContainerContract|mixed
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function current($abstract = null, $parameters = [], ContainerContract $container = null)
    {
        $container = $container ?: \Bladerunner\Container::getInstance();
        if (!$abstract) {
            return $container;
        }
        return $container->bound($abstract)
            ? $container->make($abstract, $parameters)
            : $container->make("bladerunner.{$abstract}", $parameters);
    }
}
