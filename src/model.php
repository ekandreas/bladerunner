<?php
namespace Bladerunner;
use Bladerunner\Blade;

class Model {

	protected $path;

	function getPath( $template ) {

		if( $this->path )
			return $this->path;

		if( ! $template )
			return $template;

		$template = apply_filters( 'bladerunner/get_post_template', $template );

		$views = get_stylesheet_directory();

		$basedir = wp_upload_dir()['basedir'];
		$cache = $basedir . '/.cache';
		if( !file_exists($cache) ) {
			wp_mkdir_p( $cache );
		}

		$search = [ $views, '/', '.blade', '.php', ];
		$replace = [ '', '.', '', '', ];
		$file = str_replace( $search, $replace, $template );
		$file = trim( $file, '.' );

		$blade = new Blade($views, $cache);

		/*
		$blade->getCompiler()->directive( 'papi', function( $expression )
		{
			$expression = preg_replace( '#\((.*)\)#', '$1', $expression );
		    return "<?php echo papi_get_field( \$module->ID, $expression ); ?>";
		});
		*/
	
		$view = $blade->view()->make($file);

		$content = $view->render();

		ob_start();
		echo $content;
		$content = ob_get_contents();
		ob_end_clean();

		$pathToCompiled = $cache . '/' . md5( $view->getPath() ) .'.compiled.php';

		if( !file_exists($pathToCompiled) || md5_file( $pathToCompiled ) != md5( $content ) ) {
			file_put_contents( $pathToCompiled, $content );
		}

		$this->path = $pathToCompiled;
		
		return $this->path;

	}

}


