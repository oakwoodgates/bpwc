<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="woocommerce">
	<?php
	$customer_memberships = wc_memberships_get_user_memberships();

	if ( ! empty( $customer_memberships ) ) {

		wc_get_template( 'myaccount/my-memberships.php', array(
			'customer_memberships' => $customer_memberships,
			'user_id'              => get_current_user_id(),
		) );
	}
	?>
</div>