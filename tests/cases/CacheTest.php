<?php

class CacheTest extends WP_UnitTestCase
{
    public function testPath()
    {
        $this->assertStringEndsWith('/.cache', \Bladerunner\Cache::path());
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
