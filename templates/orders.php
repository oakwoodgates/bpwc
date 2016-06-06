<?php

	$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
		'meta_key'    => '_customer_user',
		'meta_value'  => get_current_user_id(),
		'post_type'   => wc_get_order_types( 'view-orders' ),
        'post_status' => array_keys( wc_get_order_statuses() )
	) ) );

?>
<div class="woocommerce woocommerce-account">
<?php
	wc_print_notices();
	if ( $customer_orders ){
		wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => '' ) );		
	}else{
	    printf( __( 'You have no orders yet. Check out our products in the %sstore%s.', 'woocommerce-subscriptions' ), '<a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '">', '</a>' );
	}
?>
</div>
