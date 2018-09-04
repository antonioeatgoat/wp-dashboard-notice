<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The actual notice that will be displayed in the dashboard.
 */
class aeg_NM_NoticesManagerTest extends aeg_NM_UnitTestCase {

	private $defaults = array(
			'title'          => '',
			'dismiss_text'   => '',
			'dismiss_mode'   => aeg_NM_Notice::DISMISS_NONE,
			'show_close_btn' => false,
			'cta_text'     => '',
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

	public function tearDown() {
		aeg_NM_NoticesManager::destroy();
		parent::tearDown();
	}

	public function test_get_set_notice() {
		$test = aeg_NM_NoticesManager::init();

		$this->assertNull( $test->get_notice( 'this-notice-not-exists' ) );

		$notice = ( new aeg_NM_NoticeFactory() )->create( 'hello-world', 'Hello World' );

		$test->register_notice( $notice );

		$this->assertEquals( $notice, $test->get_notice( 'hello-world' ) );
	}

	public function test_notices_dismiss_listener() {
		$_GET['aeg-notice-manager-dismiss'] = 'notice-test';
		\WP_Mock::userFunction( 'check_admin_referer', array( 'return' => true ) );

		$test = aeg_NM_NoticesManager::init();

		$notice_mock = Mockery::spy( 'aeg_NM_Notice' )->shouldIgnoreMissing();
		$notice_mock->shouldReceive( 'dismiss' )->andReturnTrue();
		$notice_mock->shouldReceive( 'is_dismissed' )->andReturnTrue();
		$notice_mock->shouldReceive( 'get_id' )->andReturn( 'notice-test' );

		$test->register_notice( $notice_mock );

		$this->assertTrue( $test->notices_dismiss_listener() );
	}

	/**
	 * @dataProvider provider_notices_dismiss_listener_false
	 */
	public function test_notices_dismiss_listener_false( $get_array, $check_admin_referer_return ) {
		$_GET = $get_array;
		\WP_Mock::userFunction( 'check_admin_referer', array( 'return' => $check_admin_referer_return ) );

		$test = aeg_NM_NoticesManager::init();

		$notice_mock = Mockery::spy( 'aeg_NM_Notice' )->shouldIgnoreMissing();
		$notice_mock->shouldReceive( 'dismiss' )->andReturnTrue();
		$notice_mock->shouldReceive( 'is_dismissed' )->andReturnTrue();
		$notice_mock->shouldReceive( 'get_id' )->andReturn( 'notice-test' );

		$test->register_notice( $notice_mock );

		$this->assertFalse( $test->notices_dismiss_listener() );
	}

	public function provider_notices_dismiss_listener_false() {
		return array(
				array( array( 'aeg-notice-manager-dismiss' => 'fake-id' ), true ),
				array( array( 'aeg-notice-manager-dismiss' => 'notice-test' ), false ),
				array( array( 'aeg-notice-manager-dismiss' => 'fake-id' ), false ),
		);
	}
}