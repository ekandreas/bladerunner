<?php
class ViewTest extends WP_UnitTestCase
{
    public function testBladerunnerExists()
    {
        $result = bladerunner('index', [], false);
        $this->assertNotNull($result);
    }

    public function testViewExists()
    {
        $result = view('index', []);
        $this->assertNotNull($result);
    }
}
