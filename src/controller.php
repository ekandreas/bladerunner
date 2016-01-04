<?php
namespace Bladerunner;

class Controller {

	function __construct() {

		$model = new model();
		add_action( 'template_include', [ $model, 'getPath' ], 999 );
		add_filter( 'index_template', function() { return 'index.blade.php'; } );
		//add_filter( 'page_template', [ $model, 'getPath' ] );
		//add_filter( 'bp_template_include', [ $model, 'getPath' ] );
		
		add_action( 'admin_init', '\\Bladerunner\Controller::check_writeable_upload' );
		
	}

	static function check_writeable_upload() {

		$cache_dir = Model::get_cache_dir();
		if( !file_exists( $cache_dir ) ) {
			add_action( 'admin_notices', '\\Bladerunner\Controller::dont_exists' ); 
		}
		else if( !is_writable( $cache_dir ) ) {
			add_action( 'admin_notices', '\\Bladerunner\Controller::not_writable' ); 
		}

	}

	static function dont_exists() {
        echo"<div class=\"error\"> <p><strong>Bladerunner cache error!</strong></p><p>.cache -folder is missing in uploads. Please create the folder and make it writable!</p></div>"; 
    }

	static function not_writable() {
        echo"<div class=\"error\"> <p><strong>Bladerunner writable error!</strong></p><p>.cache -folder not writable. Please make the folder writable for your web process!</p></div>"; 
    }

}

new Controller();
