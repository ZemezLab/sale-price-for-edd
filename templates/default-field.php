<?php
/**
 * Price settings template
 */
?>
<div class="edd-sale-price">
	<span class="edd-sale-price__title"><?php
		esc_html_e( 'Sale Price', 'sale-price-for-edd' );
	?></span>
	<div>
		<?php if ( 'before' === $currency_position ) : ?>
			<span><?php echo edd_currency_filter( '' ); ?></span>
		<?php endif; ?>
		<input type="text" name="_sale_price" value="<?php echo $sale_price; ?>" class="edd-price-field">
		<?php if ( 'before' !== $currency_position ) : ?>
			<span><?php echo edd_currency_filter( '' ); ?></span>
		<?php endif; ?>
	</div>
</div>