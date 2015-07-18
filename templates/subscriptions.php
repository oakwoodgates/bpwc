<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php // WC_Subscriptions::get_my_subscriptions_template(); ?>

<?php
	$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions();
	$user_id = get_current_user_id();
	$all_actions = array();

	foreach ( $subscriptions as $subscription_key => $subscription_details ) {

		$actions = array();

		if ( $subscription_details['status'] == 'trash' ) {
			unset( $subscriptions[ $subscription_key ] );
			continue;
		}

		$admin_with_suspension_disallowed = ( current_user_can( 'manage_woocommerce' ) && 0 == get_option( WC_Subscriptions_Admin::$option_prefix . '_max_customer_suspensions', 0 ) ) ? true : false;
		if ( WC_Subscriptions_Manager::can_subscription_be_changed_to( 'on-hold', $subscription_key, $user_id ) && WC_Subscriptions_Manager::current_user_can_suspend_subscription( $subscription_key ) && ! $admin_with_suspension_disallowed ) {
			$actions['suspend'] = array(
				'url'  => WC_Subscriptions_Manager::get_users_change_status_link( $subscription_key, 'on-hold' ),
				'name' => __( 'Suspend', 'woocommerce-subscriptions' )
			);
		} elseif ( WC_Subscriptions_Manager::can_subscription_be_changed_to( 'active', $subscription_key, $user_id ) && ! WC_Subscriptions_Manager::subscription_requires_payment( $subscription_key, $user_id ) ) {
			$actions['reactivate'] = array(
				'url'  => WC_Subscriptions_Manager::get_users_change_status_link( $subscription_key, 'active' ),
				'name' => __( 'Reactivate', 'woocommerce-subscriptions' )
			);
		}

		if ( WC_Subscriptions_Renewal_Order::can_subscription_be_renewed( $subscription_key, $user_id ) ) {
			$actions['renew'] = array(
				'url'  => WC_Subscriptions_Renewal_Order::get_users_renewal_link( $subscription_key ),
				'name' => __( 'Renew', 'woocommerce-subscriptions' )
			);
		}

		$renewal_orders = WC_Subscriptions_Renewal_Order::get_renewal_orders( $subscription_details['order_id'], 'ID' );

		$last_order_id = end( $renewal_orders );

		if ( $last_order_id ) {

			$renewal_order = new WC_Order( $last_order_id );

			if ( WC_Subscriptions_Manager::can_subscription_be_changed_to( 'active', $subscription_key, $user_id ) && in_array( $renewal_order->status, array( 'pending', 'failed' ) ) && ! is_numeric( get_post_meta( $renewal_order->id, '_failed_order_replaced_by', true ) ) ) {
				$actions['pay'] = array(
					'url'  => $renewal_order->get_checkout_payment_url(),
					'name' => __( 'Pay', 'woocommerce-subscriptions' )
				);
			}

		} else { // Check if the master order still needs to be paid

			$order = new WC_Order( $subscription_details['order_id'] );

			if ( 'pending' == $order->status && WC_Subscriptions_Manager::can_subscription_be_changed_to( 'active', $subscription_key, $user_id ) ) {
				$actions['pay'] = array(
					'url'  => $order->get_checkout_payment_url(),
					'name' => __( 'Pay', 'woocommerce-subscriptions' )
				);
			}
		}

		// Show button for subscriptions which can be cancelled and aren't for just the one payment which has was processed on sign-up (i.e. didn't have a free trial)
		if ( WC_Subscriptions_Manager::can_subscription_be_changed_to( 'cancelled', $subscription_key, $user_id ) && ( $subscription_details['interval'] != $subscription_details['length'] || 0 != $subscription_details['trial_expiry_date'] ) ) {
			$actions['cancel'] = array(
				'url'  => WC_Subscriptions_Manager::get_users_change_status_link( $subscription_key, 'cancelled' ),
				'name' => __( 'Cancel', 'woocommerce-subscriptions' )
			);
		}

		$all_actions[ $subscription_key ] = $actions;
	}

	$all_actions = apply_filters( 'woocommerce_my_account_my_subscriptions_actions', $all_actions, $subscriptions );

	// Get slug for WooCommerce My Account page. We will use this later.
	$account_id = wc_get_page_id( 'myaccount' );
	$account_slug = get_post_field( 'post_name', $account_id );
?>
<div class="woocommerce woocommerce-account woocommerce_account_subscriptions">

	<?php wc_print_notices(); ?>

	<h2><?php _e( 'My Subscriptions', 'woocommerce-subscriptions' ); ?></h2>

	<?php if ( ! empty( $subscriptions ) ) : ?>
	<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

		<thead>
			<tr>
				<th class="subscription-order-number"><span class="nobr"><?php _e( 'Order', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-title"><span class="nobr"><?php _e( 'Subscription', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-status"><span class="nobr"><?php _e( 'Status', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-next-payment"><span class="nobr"><?php _e( 'Next Payment', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-end"><span class="nobr"><?php _e( 'End Date', 'woocommerce-subscriptions' ); ?></span></th>
				<th class="subscription-actions"><span class="nobr"><?php _e( 'Actions', 'woocommerce-subscriptions' ); ?></th>
			</tr>
		</thead>

		<tbody>
		<?php foreach ( array_reverse( $subscriptions ) as $subscription_key => $subscription_details ) : ?>
			<?php $order = new WC_Order( $subscription_details['order_id'] ); ?>
			<tr class="order">
				<td class="order-number" data-title="<?php _e( 'Order', 'woocommerce-subscriptions' ); ?>">
					<?php if ( method_exists( $order, 'get_view_order_url' ) ) : // WC 2.1+ ?>
						<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>"><?php echo $order->get_order_number(); ?></a>
					<?php else : ?>
						<a href="<?php echo esc_url( add_query_arg( 'order', $subscription_details['order_id'], get_permalink( woocommerce_get_page_id( 'view_order' ) ) ) ); ?>"><?php echo $order->get_order_number(); ?></a>
					<?php endif; ?>
				</td>
				<td class="subscription-title" data-title="<?php _e( 'Subscription', 'woocommerce-subscriptions' ); ?>">
					<?php $product = get_product( $subscription_details['product_id'] ); ?>
					<?php if ( false !== $product ) : // Link to the product's page if it hasn't been deleted ?>
					<a href="<?php echo get_post_permalink( $subscription_details['product_id'] ); ?>">
					<?php endif; ?>
						<?php echo WC_Subscriptions_Order::get_item_name( $subscription_details['order_id'], $subscription_details['product_id'] ); ?>
					<?php if ( false !== $product ) : ?>
					</a>
					<?php endif; ?>
					<?php $order_item = WC_Subscriptions_Order::get_item_by_product_id( $order, $subscription_details['product_id'] ); ?>
					<?php $item_meta = new WC_Order_Item_Meta( $order_item['item_meta'], $product ); ?>
					<?php $meta_to_display = $item_meta->display( true, true ); ?>
					<?php if ( ! empty( $meta_to_display ) ) : ?>
					<p>
					<?php echo $meta_to_display ; ?>
					</p>
					<?php endif; ?>
				</td>
				<td class="subscription-status" style="text-align:left; white-space:nowrap;" data-title="<?php _e( 'Status', 'woocommerce-subscriptions' ); ?>">
					<?php echo WC_Subscriptions_Manager::get_status_to_display( $subscription_details['status'], $subscription_key, $user_id ); ?>
				</td>
				<td class="subscription-next-payment" data-title="<?php _e( 'Next Payment', 'woocommerce-subscriptions' ); ?>">
					<?php $next_payment_timestamp = WC_Subscriptions_Manager::get_next_payment_date( $subscription_key, $user_id, 'timestamp' ); ?>
					<?php if ( $next_payment_timestamp == 0 ) : ?>
						-
					<?php else : ?>
						<?php $time_diff = $next_payment_timestamp - gmdate( 'U' ); ?>
						<?php if ( $time_diff > 0 && $time_diff < 7 * 24 * 60 * 60 ) : ?>
							<?php $next_payment = sprintf( __( 'In %s', 'woocommerce-subscriptions' ), human_time_diff( $next_payment_timestamp ) ); ?>
						<?php else : ?>
							<?php $next_payment = date_i18n( woocommerce_date_format(), $next_payment_timestamp ); ?>
						<?php endif; ?>
					<time title="<?php echo esc_attr( $next_payment_timestamp ); ?>">
						<?php echo $next_payment; ?>
					</time><br/>
						<?php if ( ! empty ( $order->recurring_payment_method_title ) ) : ?>
							<?php $payment_method_to_display = sprintf( __( 'Via %s', 'woocommerce-subscriptions' ), $order->recurring_payment_method_title ); ?>
					<small><?php echo apply_filters( 'woocommerce_my_subscriptions_recurring_payment_method', $payment_method_to_display, $subscription_details, $order ) ; ?></small>
						<?php endif; ?>
					<?php endif; ?>
				</td>
				<td class="subscription-end" data-title="<?php _e( 'End Date', 'woocommerce-subscriptions' ); ?>">
					<?php if ( $subscription_details['expiry_date'] == 0 && ! in_array( $subscription_details['status'], array( 'cancelled', 'switched' ) ) ) : ?>
							<?php _e( 'When Cancelled', 'woocommerce-subscriptions' ); ?>
					<?php else : ?>
						<?php if ( in_array( $subscription_details['status'], array( 'cancelled', 'switched' ) ) ) : ?>
							<?php $end_of_prepaid_term = wc_next_scheduled_action( 'scheduled_subscription_end_of_prepaid_term', array( 'user_id' => (int)$user_id, 'subscription_key' => $subscription_key ) ); ?>
							<?php if ( false === $end_of_prepaid_term ) : ?>
								<?php $end_timestamp = strtotime( $subscription_details['end_date'] ); ?>
							<?php else : ?>
								<?php $end_timestamp = $end_of_prepaid_term; ?>
							<?php endif; ?>
						<?php else : ?>
							<?php $end_timestamp = strtotime( $subscription_details['expiry_date'] ); ?>
						<?php endif; ?>
						<?php $time_diff = $end_timestamp - gmdate( 'U' ); ?>
						<?php if ( absint( $time_diff ) > 0 && absint( $time_diff ) < 7 * 24 * 60 * 60 ) : ?>
							<?php if ( $time_diff > 0 ) : // In the future ?>
								<?php $expiry = sprintf( __( 'In %s', 'woocommerce-subscriptions' ), human_time_diff( $end_timestamp ) ); ?>
							<?php else : // In the past ?>
								<?php $expiry = sprintf( __( '%s ago', 'woocommerce-subscriptions' ), human_time_diff( $end_timestamp ) ); ?>
							<?php endif; ?>
						<?php else : ?>
							<?php $expiry = date_i18n( woocommerce_date_format(), $end_timestamp ); ?>
						<?php endif; ?>
						<time title="<?php echo esc_attr( $end_timestamp ); ?>">
							<?php echo $expiry; ?>
						</time>
					<?php endif; ?>
				</td>
				<td class="subscription-actions order-actions" data-title="<?php _e( 'Actions', 'woocommerce-subscriptions' ); ?>">
					<?php // foreach( $actions[ $subscription_key ] as $key => $action ) : ?>
					<?php foreach( $all_actions[ $subscription_key ] as $key => $action ) : ?>
						<?php 
							$var = $action['url'];
							$url = ( $var[0] != '/' ) ? $action['url'] : '/' . $account_slug . $action['url'];
						?>
					<a href="<?php echo esc_url( $url ); ?>" class="button <?php echo sanitize_html_class( $key ) ?>"><?php echo esc_html( $action['name'] ); ?></a>
					<?php endforeach; ?>					
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>

	</table>


	<?php else : ?>

		<p class="no_subscriptions"><?php printf( __( 'You have no active subscriptions. Find your first subscription in the %sstore%s.', 'woocommerce-subscriptions' ), '<a href="' . apply_filters( 'woocommerce_subscriptions_message_store_url', get_permalink( woocommerce_get_page_id( 'shop' ) ) ) . '">', '</a>' ); ?></p>

	<?php endif; ?>

</div>
