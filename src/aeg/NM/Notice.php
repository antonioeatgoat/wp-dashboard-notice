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
	 * @var array
	 */
	private $defaults = array(
			'title'          => '',
			'dismiss_anchor' => '',
			'dismiss_mode'   => self::DISMISS_NONE,
			'show_close_btn' => false,
			'cta_anchor'     => '',
			'cta_href'       => '',
			'status'         => self::STATUS_INFO,
			'custom_class'   => ''
	);

	/**
	 * @var string A unique identifier for the notice message
	 */
	private $id;

	/**
	 * @var string
	 */
	private $message;

	/**
	 * aeg_NM_Notice constructor.
	 *
	 * @param string $id             An unique identified for the notice message
	 * @param string $message        The message of the notice.
	 * @param array $args {
	 *      Optional. An array of arguments.
	 *
	 *      @param string $title          The eventual title of the notice.
	 *      @param string $dismiss_mode   How an eventual dismiss button works [none|global|user].
	 *      @param string $dismiss_anchor The text of the dismiss button.
	 *      @param bool   $show_close_btn Show the button to close the notice (not permanent dismiss)
	 *      @param string $cta_href       The href attribute of an eventual CTA.
	 *      @param string $cta_anchor     The text of the CTA button.
	 *      @param string $status         The status of the notice [info|error|warning|success].
	 *      @param string $custom_class   A custom class to applu to the notice.
	 * }
	 */
	public function __construct( $id, $message, $args = array() ) {
		$this->id      = $id;
		$this->message = $message;

		$this->args = wp_parse_args( $args, $this->defaults );

		$this->args['dismiss_mode'] = $this->validate_dismiss_mode( $this->args['dismiss_mode'] );
		$this->args['status']       = $this->validate_status( $this->args['status'] );
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
	public function get_id() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->args['title'];
	}

	/**
	 * @return string
	 */
	public function get_dismiss_mode() {
		return $this->args['dismiss_mode'];
	}

	/**
	 * @return string
	 */
	public function get_dismiss_anchor() {
		return $this->args['dismiss_anchor'];
	}

	/**
	 * @return bool
	 */
	public function get_show_close_btn() {
		return (bool) $this->args['show_close_btn'];
	}

	/**
	 * @return string
	 */
	public function get_cta_anchor() {
		return $this->args['cta_anchor'];
	}

	/**
	 * @return string
	 */
	public function get_cta_href() {
		return $this->args['cta_href'];
	}

	/**
	 * @return string
	 */
	public function get_status() {
		return $this->args['status'];
	}

	/**
	 * @return string
	 */
	public function get_custom_class() {
		return $this->args['custom_class'];
	}

	/**
	 * @param string $dismiss_mode
	 *
	 * @return string
	 */
	public function validate_dismiss_mode( $dismiss_mode ) {
		return ( in_array( $dismiss_mode, self::available_dismiss_modes() ) ) ? $dismiss_mode : self::DISMISS_NONE;
	}

	/**
	 * @param string $status
	 *
	 * @return string
	 */
	public function validate_status( $status ) {
		return ( in_array( $status, self::available_statuses() ) ) ? $status : self::STATUS_INFO;
	}

	/**
	 * @return array
	 */
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