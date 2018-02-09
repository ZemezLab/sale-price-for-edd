<?php
/**
 * Adds Sale Price meta fields.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'SP_EDD_Metabox' ) ) {

	/**
	 * Define SP_EDD_Metabox class
	 */
	class SP_EDD_Metabox {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_action( 'edd_download_price_option_row', array( $this, 'add_variable_price_fields' ), 0, 3 );
			add_filter( 'edd_price_row_args', array( $this, 'add_variable_price_args' ), 10, 2 );
		}

		/**
		 * Add variable price fields
		 */
		public function add_variable_price_fields( $post_id, $key, $args ) {

			$template          = sp_edd_plugin()->plugin_path( 'templates/variable-field.php' );
			$currency_position = edd_get_option( 'currency_position', 'before' );
			$sale_price        = isset( $args['sale_price'] ) ? $args['sale_price'] : '';

			include $template;
		}

		/**
		 * Add varaible price arguments.
		 */
		public function add_variable_price_args( $args, $meta ) {
			$args['sale_price'] = isset( $meta['sale_price'] ) ? $meta['sale_price'] : '';
			return $args;
		}

	}

}
