<?php

class aeg_NM_NoticeFactory {

	public function create( $message, $title = '' ) {
		return new aeg_NM_Notice( $message, $title );
	}

	public function create_advanced( $message, $title = '', $dismiss_mode = '', $dismiss_anchor = '', $status = '', $blank_content = '' ) {
		return new aeg_NM_Notice( $message, $title, $dismiss_mode, $dismiss_anchor, $status, $blank_content );
	}

	public function create_blank( $html_content ) {
		return new aeg_NM_Notice( '', '', '', '', '', $html_content );
	}
}