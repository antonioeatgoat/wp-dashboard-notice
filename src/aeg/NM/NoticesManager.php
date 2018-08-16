<?php

final class aeg_NM_NoticesManager {

	private $notices = [];

	public static function init() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new aeg_NM_NoticesManager();
		}

		return $instance;
	}

	private function __construct() {
		// todo add permanent dismiss hooks here

		add_action( 'admin_print_styles', array( $this, 'print_notices' ) );
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

	public function print_notices() {

		// todo add all displaying checks here

		foreach( $this->notices as $notice_data ) {
			$rendered = new aeg_NM_NoticeRenderer( $notice_data['notice'], $notice_data['priority'], $notice_data['template'] );
			$rendered->render();
		}
	}
}