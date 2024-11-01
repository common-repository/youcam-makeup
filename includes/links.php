<?php

defined( 'ABSPATH' ) || exit;

class YouCam_Makeup_Admin_Links {

	protected static $_instance;

	function __construct() {
		add_filter( 'plugin_action_links_' . YOUCAM_MAKEUP_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
	}

	public function add_action_links( $links ) {
		$links[] = '<a target="_blank" href="' . esc_url( YOUCAM_MAKEUP_PLAN_URL ) . '">' . esc_html__( 'Plan', 'youcam-makeup' ) . '</a>';
		$links[] = '<a target="_blank" href="' . esc_url( YOUCAM_MAKEUP_DOCUMENTATION_URL ) . '">' . esc_html__( 'Documentation', 'youcam-makeup' ) . '</a>';
		$links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=ycm' ) ) . '">' . esc_html__( 'Settings', 'youcam-makeup' ) . '</a>';

		return $links;
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

}

YouCam_Makeup_Admin_Links::instance();
