<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class aeg_NM_NoticeFactory {

	/**
	 * Creates a Notice object, see the documentation of the object to have more details on the parameters
	 *
	 * @param string $id
	 * @param string $message
	 * @param array  $args
	 *
	 * @return aeg_NM_Notice
	 */
	public function create( $id, $message, $args = array() ) {
		return new aeg_NM_Notice( $id, $message, $args );
	}

}