<?php

class CacheTest extends WP_UnitTestCase
{
    public function testPath()
    {
        $this->assertContains('/.cache', EkAndreas\Bladerunner\Cache::path());
    }

    public function testRemoveAllViews()
    {
        $this->assertNull(EkAndreas\Bladerunner\Cache::removeAllViews());
    }

    public function testConstructor()
    {
        $cache = new EkAndreas\Bladerunner\Cache();
        $this->assertInstanceOf('EkAndreas\Bladerunner\Cache', $cache);
    }
}
