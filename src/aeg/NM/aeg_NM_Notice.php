<?php

class aeg_NM_Notice {

	const STATUS_INFO = 'info';
	const STATUS_ERROR = 'error';
	const STATUS_SUCCESS = 'success';
	const STATUS_WARNING = 'warning';

	const DISMISS_NONE = 'none';
	const DISMISS_GLOBAL = 'global';
	const DISMISS_USER = 'user';

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @var string
	 */
	private $dismiss_mode;

	/**
	 * @var string
	 */
	private $dismiss_anchor;

	/**
	 * @var string
	 */
	private $status;

	/**
	 * @var string
	 */
	private $blank_content = '';

	/**
	 * aeg_NM_Notice constructor.
	 *
	 * @param string $message        The message of the notice.
	 * @param string $title          The eventual title of the notice.
	 * @param string $dismiss_mode   How an eventual dismiss link has to work [none|global|user].
	 * @param string $dismiss_anchor The text of the dismiss link.
	 * @param string $status         The status of the notice [info|error|warning|success].
	 * @param string $blank_content  An eventual HTML output that will be printed instead of the standard notice.
	 *                               message. This will basically make useless all other properties.
	 */
	public function __construct( $message, $title = '', $dismiss_mode = '', $dismiss_anchor = '', $status = '', $blank_content = '' ) {
		$this->message        = $message;
		$this->title          = $title;
		$this->dismiss_anchor = $dismiss_anchor;
		$this->blank_content  = $blank_content;

		$this->set_dismiss_mode( $dismiss_mode );
		$this->set_status( $status );
	}

	/**
	 * @return string
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_dismiss_mode() {
		return $this->dismiss_mode;
	}

	/**
	 * @return string
	 */
	public function get_dismiss_anchor() {
		return $this->dismiss_anchor;
	}

	/**
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	public function get_blank_content() {
		return $this->blank_content;
	}

	/**
	 * @param string $dismiss_mode
	 *
	 * @return aeg_NM_Notice
	 */
	public function set_dismiss_mode( $dismiss_mode ) {
		$dismiss_mode       = ( in_array( $dismiss_mode, self::available_dismiss_modes() ) ) ? $dismiss_mode : self::DISMISS_NONE;
		$this->dismiss_mode = $dismiss_mode;

		return $this;
	}

	/**
	 * @param string $anchor
	 *
	 * @return aeg_NM_Notice
	 */
	public function set_dismiss_anchor( $anchor ) {
		$this->dismiss_anchor = $anchor;

		return $this;
	}

	/**
	 * @param string $mode
	 * @param string $anchor
	 *
	 * @return aeg_NM_Notice
	 */
	public function set_dismiss( $mode, $anchor ) {
		$this->set_dismiss_mode( $mode );
		$this->set_dismiss_anchor( $anchor );

		return $this;
	}

	/**
	 * @param string $status
	 *
	 * @return aeg_NM_Notice
	 */
	public function set_status( $status ) {
		$status       = ( in_array( $status, self::available_statuses() ) ) ? $status : self::STATUS_INFO;
		$this->status = $status;

		return $this;
	}

	private static function available_statuses() {
		return array(
			self::STATUS_INFO,
			self::STATUS_ERROR,
			self::STATUS_SUCCESS,
			self::STATUS_WARNING
		);
	}

	/**
	 * @return array
	 */
	private static function available_dismiss_modes() {
		return array(
			self::DISMISS_NONE,
			self::DISMISS_GLOBAL,
			self::DISMISS_USER
		);
	}
}