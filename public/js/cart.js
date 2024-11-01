jQuery(document).ready(function() {
	function init() {
		jQuery('.hist-add-cart').click(addToCart);
	}

	function addToCart(e) {
		e.preventDefault();
		if(jQuery(this).attr('disabled'))
			return;

		var id = jQuery(this).data('id');
		var quantity = jQuery('#' + id + '_quantity').val();
		var nonce = jQuery('#_wpnonce').val();
		var query = '?add-to-cart=' + id + '&quantity=' + quantity;
		jQuery(this).attr('disabled', true);
		var _button = this;
		jQuery.get(query, function(res) {
			jQuery(_button).attr('disabled', false);
			jQuery('.woocommerce-message').show();
			setTimeout(function() {
				jQuery('.woocommerce-message').hide();
			},3000);
		}).error(function(err) {
		});
	}


	init();
});
