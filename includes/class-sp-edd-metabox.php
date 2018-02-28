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
			add_action( 'edd_meta_box_price_fields', array( $this, 'add_default_price_field' ) );
			add_action( 'edd_download_price_option_row', array( $this, 'add_variable_price_fields' ), 0, 3 );
			add_filter( 'edd_price_row_args', array( $this, 'add_variable_price_args' ), 10, 2 );
			add_action( 'edd_save_download', array( $this, 'save_default_price' ), 10, 2 );
		}

		/**
		 * Save default slae price
		 *
		 * @return void
		 */
		public function save_default_price( $post_id, $post ) {
			$sale_price = isset( $_POST['_sale_price'] ) ? $_POST['_sale_price'] : false;

			if ( ! $sale_price ) {
				delete_post_meta( $post_id, '_sale_price' );
			} else {
				update_post_meta( $post_id, '_sale_price', edd_format_amount( $sale_price ) );
			}

		}

		/**
		 * Add default sale price field
		 */
		public function add_default_price_field( $post_id ) {

			$currency_position = edd_get_option( 'currency_position', 'before' );
			$sale_price        = get_post_meta( $post_id, '_sale_price', true );
			$template          = sp_edd_plugin()->plugin_path( 'templates/default-field.php' );

			include $template;

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
