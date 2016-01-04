<?php
namespace Bladerunner;

class Controller {

	function __construct() {

		$model = new model();
		add_action( 'template_include', [ $model, 'getPath' ], 999 );
		add_filter( 'index_template', function() { return 'index.blade.php'; } );
		//add_filter( 'page_template', [ $model, 'getPath' ] );
		//add_filter( 'bp_template_include', [ $model, 'getPath' ] );
		
	}

}

new Controller();
