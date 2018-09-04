<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The actual notice that will be displayed in the dashboard.
 */
class aeg_NM_NoticeRendererTest extends aeg_NM_UnitTestCase {

	private $defaults = array(
			'title'          => '',
			'dismiss_text' => '',
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

	public function test_render() {
		$notice_mock = Mockery::mock( 'aeg_NM_Notice')->makePartial();
		$notice_mock->shouldReceive( 'is_dismissed')->andReturnFalse();

		$test = new aeg_NM_NoticeRenderer( $notice_mock );

		$this->assertTrue( $test->render() );
	}

	public function test_render_dismissed() {
		$notice_mock = Mockery::mock( 'aeg_NM_Notice' )->makePartial();
		$notice_mock->shouldReceive( 'is_dismissed' )->andReturnTrue();

		$test = new aeg_NM_NoticeRenderer( $notice_mock );

		$this->assertFalse( $test->render() );
	}

	public function test_print_notice_html() {
		$notice_mock = Mockery::mock( 'aeg_NM_Notice')->makePartial();
		$notice_mock->shouldReceive( 'is_dismissed')->andReturnTrue();

		$notice = ( new aeg_NM_NoticeFactory() )->create(
				'notice-test',
				'Hello World',
				array(
						'title' => 'Title'
				));

		$test = new aeg_NM_NoticeRenderer( $notice );

		ob_start();
		$test->print_notice_html();
		$stirng = ob_get_clean();

		$this->assertStringEqualsFile(dirname(__FILE__) . '/../templates/test_print_notice_html.php', $stirng );
	}

	public function test_print_notice_html_not_valid_template() {
		$notice_mock = Mockery::mock( 'aeg_NM_Notice')->makePartial();
		$notice_mock->shouldReceive( 'is_dismissed')->andReturnTrue();

		$notice = ( new aeg_NM_NoticeFactory() )->create(
				'notice-test',
				'Hello World',
				array(
						'title' => 'Title'
				));

		$test = new aeg_NM_NoticeRenderer( $notice, 10, 'fake' );

		ob_start();
		$test->print_notice_html();
		$stirng = ob_get_clean();

		$this->assertEmpty( $stirng );
	}
}