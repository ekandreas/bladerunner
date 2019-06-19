<?php

namespace Bladerunner;

class BladeCompiler extends \Illuminate\View\Compilers\BladeCompiler
{
    public function isExpired($path)
    {
        $forceCompile = apply_filters('bladerunner\cache\disable', WP_DEBUG, $path);

        if ($forceCompile) {
            return true;
        }

        return parent::isExpired($path);
    }
}
