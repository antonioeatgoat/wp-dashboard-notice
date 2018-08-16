<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @var aeg_NM_Notice $notice
 */
?>

<div id="<?php echo esc_attr( $notice->get_id() ); ?>" class="aeg-notice-manaeger <?php echo $notice->get_custom_class(); ?>">
	<p><?php echo $notice->get_message(); ?></p>
</div>