<?php

class aeg_NM_NoticeFactory {

	public function create( $message, $args = array() ) {
		return new aeg_NM_Notice( $message, $args );
	}

	public function create_blank( $html_content ) {
		return new aeg_NM_Notice( '', array( 'blank_content' => $html_content) );
	}
}