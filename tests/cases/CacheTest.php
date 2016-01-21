<?php

class CacheTest extends WP_UnitTestCase
{
    public function testPath()
    {
        $this->assertContains('/.cache', EkAndreas\Bladerunner\Cache::path());
    }
}
