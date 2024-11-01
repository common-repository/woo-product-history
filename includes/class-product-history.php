<?php


class ProductHistory{

	protected $path;
	protected $url;

	public function __construct($path, $url) {
		$this->path = $path;
		$this->url = $url;
		add_action('init', array($this, 'add_endpoint'));
		add_filter('query_vars', array($this, 'add_query_var'));
		add_filter('woocommerce_account_menu_items', array($this, 'add_menu_item'));
		add_action('woocommerce_account_product-history_endpoint', array($this, 'render_tab'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_action('wp_ajax_history_add_cart', array($this, 'history_add_cart'));
		add_action('wp_ajax_nopriv_history_add_cart', array($this, 'history_add_cart'));
	}

	function add_endpoint() {
   	 	add_rewrite_endpoint( 'product-history', EP_ROOT | EP_PAGES );
	}
 
	function add_query_var( $vars ) {
		$vars[] = 'premium-support';
		return $vars;
	}

	function add_menu_item($items) {
		$items['product-history'] = 'Product History';
		return $items;
	}

	function render_tab() {
		wp_enqueue_script('product-history');
		$products = $this->get_user_products($user);
		$page = get_query_var('product-history') != '' ? get_query_var('product-history') : 1;
		$account_url = get_permalink( get_option('woocommerce_myaccount_page_id')) . 'product-history/';
		$products = wc_get_products(array(
			'include' => $products,
			'paged'	=> $page,
			'limit' => 1,
			'paginate' => true,
			'status'   => 'publish'
		));
		ob_start();
		require($this->path . 'templates/product-history.php');
		$contents = ob_get_clean();
		echo $contents;
	}

	private function get_user_products($user) {
		$customer_orders = get_posts( array(
			'numberposts' => -1,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types(),
			'post_status' => array_keys( wc_get_order_statuses() ),
		) );
		$customer_orders = wp_list_pluck($customer_orders, 'ID');

		$order_list='('.join(',', $customer_orders).')';//let us make a list for query
 
		 global $wpdb;
		 $query_select_order_items="SELECT order_item_id as id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_id IN {$order_list}";

		 $query_select_product_ids="SELECT DISTINCT meta_value as product_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key=%s AND order_item_id IN ($query_select_order_items)";	
		 $products=$wpdb->get_col($wpdb->prepare($query_select_product_ids,'_product_id'));
		return $products;
	}
	
	function enqueue_scripts() {
		wp_register_script('product-history',$this->url . 'public/js/cart.js',array('jquery'));
		wp_localize_script('product-history', 'historyAjax', array('url' => admin_url('admin-ajax.php')));
	}

	function history_add_cart() {

		check_ajax_referer('history_order', 'nonce');
		if(!isset($_POST['id']) || empty($_POST['id']))
			return;
	}
}
