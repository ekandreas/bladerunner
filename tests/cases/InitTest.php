<?php

class InitTest extends WP_UnitTestCase
{
	public function testActions()
	{
		$this->assertSame(10, has_action('admin_init', '\Bladerunner\Init::checkWriteableUpload'));
	}
}
