<?php
use Illuminate\Contracts\Container\Container as ContainerContract;

function bladerunner($view, $data = [])
{
    echo view($view, $data);
}

function view($view, $data = [])
{
    return \Bladerunner\Container::current('blade')->render($view, $data);
}
