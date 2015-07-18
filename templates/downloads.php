<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
wc_print_notices(); ?>
<div class="woocommerce woocommerce-account">
	<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>
</div>