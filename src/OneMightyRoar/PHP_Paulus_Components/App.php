<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components;

use \Paulus\Paulus;
use \Paulus\FileArrayLoader;

/**
 * App
 *
 * Base application class that loads and
 * initializes our Paulus application
 *
 * @uses \Paulus\Paulus
 * @package OneMightyRoar\PHP_Paulus_Components
 */
class App extends Paulus {

	/**
	 * The base namespace of the application
	 *
	 * @var string
	 * @access protected
	 */
	protected $app_namespace;

	/**
	 * The base path of the application
	 *
	 * @var string
	 * @access protected
	 */
	protected $app_base_path;


	/**
	 * Constructor
	 *
	 * {@inheritdoc}
	 *
	 * @see \Paulus\Paulus::__construct()
	 * @param string $app_namespace     The name of the application's namespace, to be used in the autoloader
	 * @param string $base_path         The bash path to load and define constants from
	 * @param array $config             A configuration array that matches Paulus' config pattern
	 * @access public
	 */
	public function __construct( $app_namespace = null, $base_path = __DIR__, array $config = null ) {
		// Quickly define some constants
		$this->define_global_constants();

		// Fall back to a default config path
		$this->app_base_path = $base_path;

		// Load our config if we didn't pass one
		$config = $config ?: ( new FileArrayLoader( $this->app_base_path . '/../configs/', null, 'load_config' ) )->load();

		if ( !is_null( $app_namespace ) ) {
			$this->app_namespace = $app_namespace;

			// Register our autoloader
			spl_autoload_register( array( $this, 'app_autoloader' ) );
		}

		// Call our parent constructor
		parent::__construct( $config );
	}

	/**
	 * Define our global constants
	 * 
	 * @access private
	 * @return void
	 */
	private function define_global_constants() {
		if ( !defined( 'PAULUS_APP_DIR' ) ) {
			define( 'PAULUS_APP_DIR',  $this->app_base_path . DIRECTORY_SEPARATOR );
		}
		if ( !defined( 'PAULUS_EXTERNAL_LIB_DIR' ) ) {
			define( 'PAULUS_EXTERNAL_LIB_DIR', $this->app_base_path . '/../vendor' );
		}
	}

	/**
	 * App autoloader
	 *
	 * For use when not auto-loading through composer
	 *
	 * @param string $classname     The name of the class (with namespace)
	 * @access protected
	 * @return void
	 */
	protected function app_autoloader( $classname ) {
		// Only handle our PSR-0 style classes/namespaces that match our app's namespace
		if ( strpos( ltrim( $classname, '\\' ), $this->app_namespace ) === 0 ) {
			// Convert the namespace to a sub-directory path
			if ( strpos( $classname, '\\' ) !== false) {
				$classname = str_replace( '\\', DIRECTORY_SEPARATOR, $classname );
			}

			// Define our file path
			$file_path = $this->app_base_path . '/../' . $classname . '.php';

			// If the file is readable
			if ( is_readable($file_path) ) {
				// Require... just once. ;)
				require_once( $file_path );
			}
		}
	}

} // End class App
