<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class aeg_NM_NoticeRenderer {

	/**
	 * @var aeg_NM_Notice
	 */
	private $notice;

	/**
	 * aeg_NM_NoticeRenderer constructor.
	 *
	 * @param aeg_NM_Notice $notice
	 * @param int           $priority
	 * @param string        $template
	 */
	public function __construct( aeg_NM_Notice $notice, $priority = 10, $template = '' ) {
		$this->notice   = $notice;
		$this->priority = (int) $priority;
		$this->template = ( empty ( $template ) ) ? AEG_NM_DIRPATH . '/templates/notice.php' : (string) $template;
	}

	/**
	 * Hooks the notice message to WordPress
	 */
	public function render() {
		add_action( 'admin_notices', array( $this, 'print_notice_html' ), $this->priority );
	}

	/**
	 * Prints the HTML of ot notice message
	 */
	public function print_notice_html() {
	    if( ! $this->is_valid_template() ) {
	        return;
        }

		$notice = $this->notice;

		include $this->template;
	}

	/**
     * Checks if a template exists
     *
	 * @return bool
	 */
	private function is_valid_template() {
	    return ( file_exists( $this->template ) );
    }
}