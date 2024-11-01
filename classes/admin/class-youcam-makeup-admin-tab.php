<?php

defined( 'ABSPATH' ) || exit;

class YouCam_Makeup_Admin extends YouCam_Makeup {

	function __construct() {
		$this->init();
	}

	//Set up base actions
	public function init(): void {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_post_ycm_api_connect', array( $this, 'api_connect' ) );
		add_action( 'admin_post_ycm_api_disconnect', array( $this, 'api_disconnect' ) );
		add_action( 'admin_post_ycm_settings', array( $this, 'settings' ) );
		add_action( 'admin_notices', array( $this, 'api_connect_fail_notice' ) );
		add_action( 'admin_enqueue_scripts', function () {
			wp_enqueue_script(
				'ycm-init',
				plugins_url( '/includes/init.js', YOUCAM_MAKEUP_FILE ),
				array( 'jquery', 'jquery-tiptip'),
				null,
				true
			);

			wp_enqueue_style(
				'woocommerce_admin_styles',
				WC()->plugin_url() . '/assets/css/admin.css',
				[],
				true
			);
		});
	}

	public function admin_menu() {
		add_submenu_page( 'woocommerce', 'YouCam Makeup', 'YouCam Makeup', 'manage_options', 'ycm', function () {
			$tab = sanitize_key( $_GET['tab'] ?? 'api' );
			$tab = in_array( $tab, array( 'api', 'settings' ) ) ? $tab : 'api';
			$this->render(
				'settings.php',
				array(
					'tab'                   => $tab,
					'product_page_actions'  => $this->get_tryon_action_in_product(),
					'category_page_actions' => $this->get_tryon_action_in_category(),
					'data'                  => get_option( 'ycm_' . $tab ),
				)
			);
		} );
	}

	public function api_connect() {
		$this->verify_nonce( 'ycm_api_connect_nonce' );
		$api_key  = sanitize_text_field( $_POST['ycm']['api_key'] );
		$response = YouCam_Makeup_Api::instance()->connect( $api_key );
		if ( 200 === $response['code'] ) {
			update_option(
				'ycm_api',
				array(
					'api_key' => $api_key,
				)
			);
		} else {
			update_option( "api_connect_fail_notice", true );
		}
		wp_redirect( admin_url( 'admin.php?page=ycm&tab=api' ) );
	}

	function api_connect_fail_notice() {
		if ( ! get_option( 'api_connect_fail_notice' ) ) {
			return;
		}
		printf( '<div class="notice notice-error"><p>%s</p></div>', __( 'Your API key is incorrect, please verify and connect it again.', 'youcam-makeup' ) );
		delete_option( 'api_connect_fail_notice' );
	}


	public function api_disconnect() {
		$this->verify_nonce( 'ycm_api_disconnect_nonce' );
		delete_option( 'ycm_api' );
		wp_redirect( admin_url( 'admin.php?page=ycm&tab=api' ) );
	}

	public function settings() {
		$this->verify_nonce( 'ycm_settings_nonce' );
		$ycm_settings                               = get_option( 'ycm_settings' );
		$ycm_settings['product_cat_list_in_product']     = $_POST['product_cat_list_in_product'] === '1' ? '1' : '0';
		$ycm_settings['product_cat_list_in_category']    = $_POST['product_cat_list_in_category'] === '1' ? '1' : '0';
		$ycm_settings['show_widget_in_product']     = $_POST['show_widget_in_product'] === '1' ? '1' : '0';
		$ycm_settings['show_widget_in_category']    = $_POST['show_widget_in_category'] === '1' ? '1' : '0';
		$ycm_settings['show_tryon_btn_in_category'] = $_POST['show_tryon_btn_in_category'] === '1' ? '1' : '0';
		$ycm_settings['show_tryon_btn_in_product']  = $_POST['show_tryon_btn_in_product'] === '1' ? '1' : '0';
		$ycm_settings['tryon_btn_text']             = sanitize_text_field( $_POST['tryon_btn_text'] );
		$tryon_btn_position_in_category             = sanitize_key( $_POST['tryon_btn_position_in_category'] );
		$tryon_btn_position_in_product              = sanitize_key( $_POST['tryon_btn_position_in_product'] );
		if ( array_key_exists( $tryon_btn_position_in_category, $this->get_tryon_action_in_category() ) ) {
			$ycm_settings['tryon_btn_position_in_category'] = $tryon_btn_position_in_category;
		}
		if ( array_key_exists( $tryon_btn_position_in_product, $this->get_tryon_action_in_product() ) ) {
			$ycm_settings['tryon_btn_position_in_product'] = $tryon_btn_position_in_product;
		}
		update_option( 'ycm_settings', $ycm_settings );
		wp_redirect( admin_url( 'admin.php?page=ycm&tab=settings' ) );
	}

	private function verify_nonce( $nonce ) {
		if ( isset( $_POST[ $nonce ] ) &&
		     wp_verify_nonce( $_POST[ $nonce ], $nonce ) &&
		     current_user_can( 'manage_options' )
		) {
			return;
		}
		wp_die( 'Permission denied.' );
	}

	private function get_tryon_action_in_category(): array {
		return array(
			'shop_loop_item_title'        => 'Shop Loop Item Title',
			'before_shop_loop_item'       => 'Before Shop Item',
			'after_shop_loop_item'        => 'After Shop Item',
			'before_shop_loop_item_title' => 'Before Shop Loop Item Title',
			'after_shop_loop_item_title'  => 'After Shop Loop Item Title',
		);
	}

	private function get_tryon_action_in_product(): array {
		return array(
			'before_add_to_cart_form'   => 'Before Add To Cart Form',
			'after_add_to_cart_form'    => 'After Add To Cart Form',
			'before_add_to_cart_button' => 'Before Add To Cart Button',
			'after_add_to_cart_button'  => 'After Add To Cart Button',
			'before_single_variation'   => 'Before Single Variation',
			'after_single_variation'    => 'After Single Variation',
			'before_variations_form'    => 'Before Variations Form',
			'after_variations_form'     => 'After Variations Form',
			'product_thumbnails'        => 'Though Product Thumbnails',
			'product_meta_start'        => 'Start of Product Meta',
			'product_meta_end'          => 'End of Product Meta',
		);
	}
}
