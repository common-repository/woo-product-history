<?php if($products): ?>

<h2>Product History</h2>
	<div class="woocommerce-message message-wrapper message" style="display:none;">
    		<div class="message-container container success-color medium-text-center">
		<i class="icon-checkmark"></i>  
		Product added to cart
	 	 </div>
	</div>
	<table class="shop_table shop_table_responsive my_account_orders">
		<?php wp_nonce_field('history_order'); ?>
		<thead>
			<tr>
				<th>Product</th>
				<th>Price</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($products->products as $key => $product): ?>
			<tr class="order">
				<td><?php echo $product->get_name(); ?></td>
				<td><?php echo $product->get_price_suffix() . ''. $product->get_price(); ?></td>
				<td>
					<div class="quantity buttons_added">
					<input type="button" value="-" class="minus button is-form">
					<input type="number" class="input-text qty text" step="1" min="1" max="9999" id='<?php echo $product->get_id(); ?>_quantity' name="quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
					<input  type="button" value="+" class="plus button is-form">
					
					</div>
				<a data-id="<?php echo $product->get_id(); ?>"class='button hist-add-cart'>Add to Cart</a></td>

			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php 
echo paginate_links( array(
	'base' => $account_url . '%#%',
	'format' => '%#%',
	'current' => max( 1, get_query_var('product-history') ),
	'total' => $products->max_num_pages
) );
endif; ?>
