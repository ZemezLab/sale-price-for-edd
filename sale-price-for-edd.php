<?php
/**
 * Plugin Name: Sale Price for Easy Digital Downloads
 * Plugin URI:
 * Description: Sale prices manager for Easy Digital Downloads
 * Version:     1.0.0
 * Author:      Zemez
 * Author URI:  https://zemez.io/wordpress/
 * Text Domain: sale-price-for-edd
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'SP_EDD_Plugin' ) ) {

	/**
	 * Define SP_EDD_Plugin class
	 */
	class SP_EDD_Plugin {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		public $metabox;
		public $manager;

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'load_files' ), 0 );
			add_action( 'init', array( $this, 'init' ), 1 );
		}

		/**
		 * Load required files
		 *
		 * @return void
		 */
		public function load_files() {
			require $this->plugin_path( 'includes/class-sp-edd-metabox.php' );
			require $this->plugin_path( 'includes/class-sp-edd-manager.php' );
		}

		/**
		 * Initialize plugin parts
		 *
		 * @return void
		 */
		public function init() {
			$this->metabox = new SP_EDD_Metabox();
			$this->manager = new SP_EDD_Manager();
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}
		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of SP_EDD_Plugin
 *
 * @return object
 */
function sp_edd_plugin() {
	return SP_EDD_Plugin::get_instance();
}

sp_edd_plugin();
