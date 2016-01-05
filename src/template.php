<?php
namespace Bladerunner;
use Bladerunner\Blade;

/**
 * Handles the template include for blade templates
 */
class Template {

	/**
	 * Saves the path in case of double object instance
	 * @var [type]
	 */
	protected $path;

	/**
	 * [__construct description]
	 */
	function __construct() {

		add_filter( 'template_include', [ $this, 'path' ] );
		add_filter( 'index_template', function() { return 'index.blade.php'; } );
		//add_filter( 'page_template', [ $model, 'getPath' ] );
		//add_filter( 'bp_template_include', [ $model, 'getPath' ] );
		
	}

	/**
	 * The hook for template_include to override blade templating
	 * @param  [type] $template [description]
	 * @return [type]           [description]
	 */
	function path( $template ) {

		if( $this->path )
			return $this->path;

		if( ! $template )
			return $template;

		$template = apply_filters( 'bladerunner/get_post_template', $template );

		$views = get_stylesheet_directory();

		$cache = Template::cache();
		if( !file_exists($cache) ) {
			return $template;
		}

		$search = [ $views, '/', '.blade', '.php', ];
		$replace = [ '', '.', '', '', ];
		$file = str_replace( $search, $replace, $template );
		$file = trim( $file, '.' );

		if( !file_exists( get_stylesheet_directory() . '/' . $file . '.blade.php' ) ) return $template;

		$blade = new Blade($views, $cache);

		$view = $blade->view()->make($file);

		$pathToCompiled = $cache . '/' . md5( $view->getPath() ) .'.compiled.php';

		$content = $view->render();

		if( !file_exists($pathToCompiled) || md5_file( $pathToCompiled ) != md5( $content ) ) {
			ob_start();
			echo $content;
			$content = ob_get_contents();
			ob_end_clean();

			file_put_contents( $pathToCompiled, $content );
		}

		$this->path = $pathToCompiled;
		
		return $this->path;

	}

	/**
	 * Gets the cache folder for Bladerunner
	 * @return [type] [description]
	 */
	static function cache() {
		$result = wp_upload_dir()['basedir'];
		$result .= '/.cache';
		return apply_filters('bladerunner/cache', $result);
	}

}


