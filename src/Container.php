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
     * @param Container $container
     * @return Container|mixed
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function current($abstract = null, $parameters = [], Container $container = null)
    {
        $container = $container ?: Container::getInstance();
        if (!$abstract) {
            return $container;
        }
        return $container->bound($abstract)
            ? $container->make($abstract, $parameters)
            : $container->make("bladerunner.{$abstract}", $parameters);
    }
}
