<?php

final class aeg_NM_NoticesManager {

	const DISMISS_QUERY_ARG = 'aeg-notice-manager-dismiss';

	const DISMISSED_NOTICES_OPTION = 'aeg-dismissed-notices';

	private $notices = [];

	public static function init() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new aeg_NM_NoticesManager();
		}

		return $instance;
	}

	private function __construct() {
		add_action( 'admin_head', array( $this, 'notices_dismiss_listener' ) );
		add_action( 'admin_head', array( $this, 'print_notices' ) );
	}

	/**
	 * @param aeg_NM_Notice $notice
	 * @param int           $priority
	 * @param string        $template
	 */
	public function add( aeg_NM_Notice $notice, $priority = 10, $template = '' ) {
		$this->notices[ $notice->get_id() ] = [
				'notice'   => $notice,
				'priority' => $priority,
				'template' => $template
		];
	}

	/**
	 * Print all the notices that have to be printed
	 */
	public function print_notices() {
		foreach ( $this->notices as $notice_data ) {
			if ( ! $this->is_dismissed( $notice_data['notice'] ) ) {
				$rendered = new aeg_NM_NoticeRenderer( $notice_data['notice'], $notice_data['priority'], $notice_data['template'] );
				$rendered->render();
			}
		}
	}

	/**
	 * Register dismissal of admin notices.
	 *
	 * Acts on the dismiss link in the admin nag messages.
	 * If clicked, the admin notice disappears and will no longer be visible to this user.
	 */
	public function notices_dismiss_listener() {
		if ( ! isset( $_GET[ self::DISMISS_QUERY_ARG ] ) || ! check_admin_referer( self::DISMISS_QUERY_ARG . '-' . get_current_user_id() ) ) {
			return;
		}

		$notice = $this->get_notice( $_GET[ self::DISMISS_QUERY_ARG ] );

		if ( ! $notice instanceof aeg_NM_Notice ) {
			return;
		}

		if ( aeg_NM_Notice::DISMISS_GLOBAL === $notice->get_dismiss_mode() ) {
			$this->dismiss_global_notice( $notice->get_id() );
		} else if ( aeg_NM_Notice::DISMISS_USER === $notice->get_dismiss_mode() ) {
			$this->dismiss_user_notice( $notice->get_id() );
		}

	}

	/**
	 * @param string $notice_id
	 *
	 * @return null|aeg_NM_Notice
	 */
	private function get_notice( $notice_id ) {
		if ( isset( $this->notices[ $notice_id ] ) ) {
			return $this->notices[ $notice_id ]['notice'];
		}

		return null;
	}

	/**
	 * @param string $notice_id
	 */
	private function dismiss_global_notice( $notice_id ) {
		$dismissed_notices = get_option( self::DISMISSED_NOTICES_OPTION );

		if ( ! $dismissed_notices ) {
			$new_option = array( $notice_id );
		} else {
			$new_option = array_merge( $dismissed_notices, array( $notice_id ) );
		}

		update_option( self::DISMISSED_NOTICES_OPTION, $new_option, false );
	}

	/**
	 * @param string $notice_id
	 */
	private function dismiss_user_notice( $notice_id ) {
		update_user_meta( get_current_user_id(), self::DISMISSED_NOTICES_OPTION, $notice_id );
	}

	/**
	 * Checks if a given notice can be showed to the current user
	 *
	 * @param aeg_NM_Notice $notice
	 *
	 * @return bool
	 */
	private function is_dismissed( aeg_NM_Notice $notice ) {
		$is_dismissed      = false;
		$dismissed_notices = [];

		if ( aeg_NM_Notice::DISMISS_GLOBAL === $notice->get_dismiss_mode() ) {
			$dismissed_notices = get_option( self::DISMISSED_NOTICES_OPTION );
		} else if ( aeg_NM_Notice::DISMISS_USER === $notice->get_dismiss_mode() ) {
			$dismissed_notices = get_user_meta( get_current_user_id(), self::DISMISSED_NOTICES_OPTION );
		}

		if ( is_array( $dismissed_notices ) && in_array( $notice->get_id(), $dismissed_notices ) ) {
			$is_dismissed = true;
		}

		return apply_filters( 'aeg_nm_is_notice_dismissed', $is_dismissed );
	}
}