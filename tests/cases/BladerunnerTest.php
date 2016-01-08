<?php

class BladerunnerTest extends WP_UnitTestCase
{
	public function testPluginActivated()
	{
		$this->assertTrue(class_exists('\Bladerunner\Init'));
	}	
}
