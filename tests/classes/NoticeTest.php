<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class aeg_NM_Notice
 *
 * The actual notice that will be displayed in the dashboard.
 */
class aeg_NM_NoticeTest extends aeg_NM_UnitTestCase {

	private $defaults = array(
			'title'          => '',
			'dismiss_anchor' => '',
			'dismiss_mode'   => aeg_NM_Notice::DISMISS_NONE,
			'show_close_btn' => false,
			'cta_anchor'     => '',
			'cta_href'       => '',
			'status'         => aeg_NM_Notice::STATUS_INFO,
			'custom_class'   => ''
	);

	public function setUp() {
		parent::setUp();

		\WP_Mock::userFunction( 'wp_parse_args', array(
				'return' => function ( $args ) {
					return array_merge( $this->defaults, $args );
				}
		) );
	}

	public function test_get_id() {
		$expected = 'notice-test';
		$test     = ( new aeg_NM_NoticeFactory() )->create( $expected, 'Hello World' );

		$this->assertEquals( $expected, $test->get_id() );
	}

	public function test_get_message() {
		$expected = 'Hello World';
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', $expected );

		$this->assertEquals( $expected, $test->get_message() );
	}

	public function test_get_title() {
		$expected = 'This is a title';
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'title' => $expected ] );

		$this->assertEquals( $expected, $test->get_title() );
	}

	public function test_get_dismiss_anchor() {
		$expected = 'This is a dismiss anchor';
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'dismiss_anchor' => $expected ] );

		$this->assertEquals( $expected, $test->get_dismiss_anchor() );
	}

	public function test_get_show_close_btn() {
		$test = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'show_close_btn' => true ] );
		$this->assertTrue( $test->get_show_close_btn() );
	}

	public function test_get_cta_anchor() {
		$expected = 'This is a CTA';
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'cta_anchor' => $expected ] );

		$this->assertEquals( $expected, $test->get_cta_anchor() );
	}

	public function test_get_cta_href() {
		$expected = '#';
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'cta_href' => $expected ] );

		$this->assertEquals( $expected, $test->get_cta_href() );
	}

	public function test_get_custom_class() {
		$expected = 'custom-class';
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'custom_class' => $expected ] );

		$this->assertEquals( $expected, $test->get_custom_class() );
	}

	/**
	 * @dataProvider provider_get_dismiss_mode
	 *
	 * @param string $mode
	 * @param string $expected
	 */
	public function test_get_dismiss_mode( $mode, $expected ) {
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'dismiss_mode' => $mode ] );

		$this->assertEquals( $expected, $test->get_dismiss_mode() );
	}

	/**
	 * @return array
	 */
	public function provider_get_dismiss_mode() {
		return [
				[ 'global', 'global' ],
				[ 'user', 'user' ],
				[ 'none', 'none' ],
				[ 'fake', 'none' ]
		];
	}

	/**
	 * @dataProvider provider_get_status
	 *
	 * @param string $status
	 * @param string $expected
	 */
	public function test_get_status( $status, $expected ) {
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'status' => $status ] );

		$this->assertEquals( $expected, $test->get_status() );
	}

	/**
	 * @return array
	 */
	public function provider_get_status() {
		return [
				[ 'info', 'info' ],
				[ 'success', 'success' ],
				[ 'warning', 'warning' ],
				[ 'error', 'error' ],
				[ 'fake', 'info' ]
		];
	}

	public function test_get_dismiss_url() {
		WP_Mock::passthruFunction( 'wp_nonce_url' );

		\WP_Mock::userFunction( 'get_current_user_id', array( 'return' => 1 ) );
		\WP_Mock::userFunction( 'add_query_arg', array(
				'times' => 1,
				'args' => array( 'aeg-notice-manager-dismiss', 'notice-test' ),
				'return' => 'aeg-notice-manager-dismiss=notice-test'
		) );

		$expected = 'aeg-notice-manager-dismiss=notice-test';
		$test     = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message' );

		$this->assertContains( $expected, $test->get_dismiss_url() );
	}

	public function test_useless_dismiss() {
		$test = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message' );

		$this->assertEquals( 0, $test->dismiss() );
	}

	public function test_dismiss_by_user() {
		$test1 = ( new aeg_NM_NoticeFactory() )->create( 'notice-test-1', 'Message', [ 'dismiss_mode' => 'user' ] );
		$test2 = ( new aeg_NM_NoticeFactory() )->create( 'notice-test-2', 'Message', [ 'dismiss_mode' => 'user' ] );

		\WP_Mock::userFunction( 'get_current_user_id', array( 'return' => 1 ) );

		\WP_Mock::userFunction( 'get_user_meta', array(
				'return_in_order' => array( false, [ 'notice-test-1' ] )
		) );

		\WP_Mock::userFunction( 'update_user_meta', array(
				'times' => 2,
				'return' => true
		) );

		$this->assertTrue( $test1->dismiss() );
		$this->assertTrue( $test2->dismiss() );
	}

	public function test_dismiss_by_global() {
		$test1 = ( new aeg_NM_NoticeFactory() )->create( 'notice-test-1', 'Message', [ 'dismiss_mode' => 'global' ] );
		$test2 = ( new aeg_NM_NoticeFactory() )->create( 'notice-test-2', 'Message', [ 'dismiss_mode' => 'global' ] );

		\WP_Mock::userFunction( 'get_current_user_id', array( 'return' => 1 ) );

		\WP_Mock::userFunction( 'get_option', array(
				'return_in_order' => array( false, [ 'notice-test-1' ] )
		) );

		WP_Mock::userFunction( 'update_option', array(
				'times' => 2,
				'return' => true
		) );

		$this->assertTrue( $test1->dismiss() );

		$this->assertTrue( $test2->dismiss() );
	}

	public function test_is_dismiss_by_user() {
		$test = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'dismiss_mode' => 'user' ] );

		\WP_Mock::userFunction( 'get_current_user_id', array( 'return' => 1 ) );

		\WP_Mock::userFunction( 'get_user_meta', array(
				'return_in_order' => array( false, [ 'notice-test' ] )
		) );

		\WP_Mock::userFunction( 'update_user_meta', array(
				'times' => 1,
				'return' => true
		) );

		$this->assertFalse( $test->is_dismissed() );

		$test->dismiss();

		$this->assertTrue( $test->is_dismissed() );
	}

	public function test_is_dismiss_by_global() {
		$test = ( new aeg_NM_NoticeFactory() )->create( 'notice-test', 'Message', [ 'dismiss_mode' => 'global' ] );

		\WP_Mock::userFunction( 'get_current_user_id', array( 'return' => 1 ) );

		\WP_Mock::userFunction( 'get_option', array(
				'return_in_order' => array( false, [ 'notice-test' ] )
		) );

		\WP_Mock::userFunction( 'update_option', array(
				'times' => 1,
				'return' => true
		) );

		$this->assertFalse( $test->is_dismissed() );

		$test->dismiss();

		$this->assertTrue( $test->is_dismissed() );
	}
}