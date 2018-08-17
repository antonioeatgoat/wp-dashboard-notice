<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @var aeg_NM_Notice $notice
 */

$css_clases = 'notice-' . $notice->get_status();
$css_clases .= ' ' . $notice->get_custom_class();

if ( $notice->get_show_close_btn() ) {
	$css_clases .= ' is-dismissible';
}

?>

<div id="<?php echo esc_attr( $notice->get_id() ); ?>"
     class="aeg-notice notice <?php echo $css_clases; ?>">
    <p><?php echo $notice->get_message(); ?></p>

	<?php if ( $notice->get_cta_anchor() && $notice->get_cta_href() || $notice->get_dismiss_anchor() && 'none' !== $notice->get_dismiss_mode() ): ?>
        <p class="aeg-notice__buttons">

			<?php if ( $notice->get_cta_anchor() && $notice->get_cta_href() ): ?>
                <a href="<?php echo $notice->get_cta_href() ?>"
                   class="button-primary"><?php echo $notice->get_cta_anchor(); ?></a>
			<?php endif; ?>
			<?php if ( $notice->get_dismiss_anchor() && 'none' !== $notice->get_dismiss_mode() ): ?>
                <a class="button button-link"
                   href="<?php echo $notice->get_dismiss_url(); ?>"><?php echo $notice->get_dismiss_anchor(); ?></a>
			<?php endif; ?>

        </p>
	<?php endif; ?>

	<?php if ( $notice->get_show_close_btn() ): ?>
        <button type="button" class="notice-dismiss"><span
                    class="screen-reader-text"><?php _e( 'Dismiss this notice.' ); ?></span></button>
	<?php endif; ?>
</div>