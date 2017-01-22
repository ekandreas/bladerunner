<?php
use Illuminate\Contracts\Container\Container as ContainerContract;

function bladerunner($view, $data = [], $echo = true)
{
    $result = view($view, $data);
    if ($echo) {
        echo $result;
    }
    return $result;
}

function view($view, $data = [])
{
    return \Bladerunner\Container::current('blade')->render($view, $data);
}
