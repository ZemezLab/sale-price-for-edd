<?php
/**
 * Price settings template
 */
?>
<div class="edd-sale-price-options edd-custom-price-option-section">
	<span class="edd-custom-price-option-section-title"><?php
		esc_html_e( 'Sale Price', 'sale-price-for-edd' );
	?></span>
	<div>
		<?php if ( 'before' === $currency_position ) : ?>
			<span><?php echo edd_currency_filter( '' ); ?></span>
		<?php endif; ?>
		<input type="text" name="edd_variable_prices[<?php echo $key; ?>][sale_price]" value="<?php echo $sale_price; ?>">
		<?php if ( 'before' !== $currency_position ) : ?>
			<span><?php echo edd_currency_filter( '' ); ?></span>
		<?php endif; ?>
	</div>
</div>