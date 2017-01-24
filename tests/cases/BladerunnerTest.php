<?php

class BladerunnerTest extends WP_UnitTestCase
{
    public function testPluginActivated()
    {
        $this->assertTrue(class_exists('\Bladerunner\Container'));
        $this->assertTrue(class_exists('\Bladerunner\Blade'));
        $this->assertTrue(class_exists('\Bladerunner\Config'));
        $this->assertTrue(class_exists('\Bladerunner\Repository'));
    }
}
