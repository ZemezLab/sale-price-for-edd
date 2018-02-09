<?php
/**
 * Adds front-end related hooks.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'SP_EDD_Manager' ) ) {

	/**
	 * Define SP_EDD_Manager class
	 */
	class SP_EDD_Manager {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		private $current_price = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_filter( 'edd_price_option_output', array( $this, 'rewrite_price_output' ), 10, 6 );
			add_filter( 'edd_get_price_option_amount', array( $this, 'rewrite_price_option_amount' ), 10, 3 );
			add_filter( 'edd_add_to_cart_item', array( $this, 'update_cart_item' ) );
			add_filter( 'edd_cart_item_price', array( $this, 'update_cart_price' ), 10, 3 );
		}

		/**
		 * Update cart price
		 *
		 * @return string
		 */
		public function update_cart_price( $price, $download_id, $options ) {
			return ! empty( $options['sale_price'] ) ? $options['sale_price'] : $price;
		}

		/**
		 * Update item before adding to cart
		 *
		 * @param  [type] $item [description]
		 * @return [type]       [description]
		 */
		public function update_cart_item( $item ) {

			if ( empty( $item['options']['price_id'] ) ) {
				return $item;
			}

			$sale_price = $this->get_sale_price( $item['id'], $item['options']['price_id'] );
			$item['options']['sale_price'] = $sale_price;

			return $item;
		}

		/**
		 * Apply sale price to amount
		 *
		 * @param  [type] $amount      [description]
		 * @param  [type] $download_id [description]
		 * @param  [type] $price_id    [description]
		 * @return [type]              [description]
		 */
		public function rewrite_price_option_amount( $amount, $download_id, $price_id ) {

			$sale_price = $this->get_sale_price( $download_id, $price_id );

			if ( $sale_price ) {
				return $sale_price;
			} else {
				return $amount;
			}

		}

		/**
		 * Get sale price for current download and price ID
		 *
		 * @param  [type] $download_id [description]
		 * @param  [type] $price_id    [description]
		 * @return [type]              [description]
		 */
		public function get_sale_price( $download_id, $price_id ) {

			$prices = edd_get_variable_prices( $download_id );
			$price  = $prices[ $price_id ];

			return ! empty( $price['sale_price'] ) ? edd_sanitize_amount( $price['sale_price'] ) : '';
		}

		/**
		 * Rewrite price output
		 *
		 * @param  [type] $price_output [description]
		 * @param  [type] $download_id  [description]
		 * @param  [type] $key          [description]
		 * @param  [type] $price        [description]
		 * @param  [type] $form_id      [description]
		 * @param  [type] $item_prop    [description]
		 * @return [type]               [description]
		 */
		public function rewrite_price_output( $price_output, $download_id, $key, $price, $form_id, $item_prop ) {

			if ( empty( $price['sale_price'] ) ) {
				return $price_output;
			}

			$this->current_price = $price;
			$price_output = preg_replace_callback(
				'/<span class="edd_price_option_price">(.*?)<\/span>/',
				array( $this, '_rewrite_price_output' ),
				$price_output
			);
			$this->current_price = null;

			return $price_output;
		}

		/**
		 * Rewrite price output
		 *
		 * @return string
		 */
		public function _rewrite_price_output( $matches = array() ) {

			return sprintf(
				'<span class="edd_price_option_price"><del>%1$s</del><ins>%2$s</ins></span>',
				$matches[1],
				edd_currency_filter( edd_format_amount( $this->current_price['sale_price'] ) )
			);

		}

	}

}
