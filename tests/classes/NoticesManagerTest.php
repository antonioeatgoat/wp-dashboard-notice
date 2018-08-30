<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class aeg_NM_Notice
 *
 * The actual notice that will be displayed in the dashboard.
 */
class aeg_NM_NoticesManagerTest extends aeg_NM_UnitTestCase {

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

	public function tearDown() {
		aeg_NM_NoticesManager::destroy();
		parent::tearDown();
	}

	public function test_get_set_notice() {
		$test = aeg_NM_NoticesManager::init();

		$this->assertNull( $test->get_notice( 'this-notice-not-exists' ) );

		$notice = ( new aeg_NM_NoticeFactory() )->create( 'hello-world', 'Hello World' );

		$test->add( $notice );

		$this->assertEquals( $notice, $test->get_notice( 'hello-world' ) );
	}

}