<?php

class CacheTest extends WP_UnitTestCase
{
    public function testPath()
    {
        $path = Bladerunner\Cache::path();
        //$this->assertStringEndsWith('/.cache', $path);
    }

    public function testRemoveAllViews()
    {
        $this->assertNull(\Bladerunner\Cache::removeAllViews());
    }

    public function testConstructor()
    {
        $cache = new \Bladerunner\Cache();
        $this->assertInstanceOf('\Bladerunner\Cache', $cache);
    }
}
