<?php
/**
 * Plugin Name: Rivier Scholarships Example
 * Plugin URI: http://wordpress.org/plugins
 * Description: Example plugin from our generator for Rivier Scholarships
 * Version: 0.1.0
 * Author: Lucas Stark @ Ellucian
 * Author URI: 
 * Text Domain: rivier
 * Domain Path: /languages
 */


namespace Rivier\Scholarships {


	use Rivier\Scholarships\Core\Settings;

	class Plugin {

		/**
		 * @var \Rivier\Scholarships\Plugin;
		 */
		private static $instance;

		public static function register() {
			if ( self::$instance == null ) {
				self::$instance = new Plugin();
			}
		}

		/**
		 * @return \Rivier\Scholarships\Plugin
		 */
		public static function instance() {
			return self::$instance;
		}

		private $_dir;
		private $_url;


		/**
		 * @var string The current db version for the plugin.
		 */
		public $db_version;


		/**
		 * @var string The current assets version, used for forcing cache refresh of css and js files.
		 */
		public $assets_version;

		/**
		 * @var \Rivier\Scholarships\Core\Templates Template Manager.
		 */
		public $templates;

		/**
		 * @var \Rivier\Scholarships\Server\Server;
		 */
		public $server;


		private function __construct() {
			$this->_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
			$this->_url = untrailingslashit( plugins_url( '/', __FILE__ ) );

			$this->db_version     = '1.0.0';
			$this->assets_version = '1.0.0';

			require $this->dir() . '/inc/Core/Settings.php';

			//Self contained controller classes.
			require $this->dir() . '/inc/Controllers/Taxonomy.php';
			Controllers\Taxonomy::register();


			if ( is_admin() ) {
				require $this->dir() . '/inc/Controllers/Admin.php';
				Controllers\Admin::register();
			}

			add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
			add_action('init', array($this, 'on_init'));

		}

		public function on_init(){
			//Include all the ACF Fields.
			if (!is_network_admin() && function_exists('acf_add_local_field_group')) {
				require $this->dir() . '/acf/include.php';
			}
		}

		public function on_plugins_loaded() {

			require $this->dir() . '/inc/Core/Templates.php';
			$this->templates = new Core\Templates();

		}



		/** Helper Functions for the plugins */

		/**
		 * @return string Absolute path to the plugin directory.
		 */
		public function dir() {
			return $this->_dir;
		}

		/**
		 * @return string URL to the plugin directory.
		 */
		public function url() {
			return $this->_url;
		}

		public function template_directory() {
			return 'rivier-scholarships/';
		}

	}


	Plugin::register();

}

namespace {

	/**
	 * @return \Rivier\Scholarships\Plugin
	 */
	function Rivier_Scholarships() {
		return \Rivier\Scholarships\Plugin::instance();
	}

}


