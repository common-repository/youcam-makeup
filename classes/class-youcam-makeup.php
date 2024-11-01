<?php

defined( 'ABSPATH' ) || exit;

class YouCam_Makeup {
	private $api;
	private $settings;
	private $skuType;
	private array $skuNoMap = array();

	public static string $API_URL = 'https://smb-api.makeupar.com/smb-api/';
	public static string $SMB_URL = 'https://smb.perfectcorp.com/';
	private static string $SDK_URL = 'https://plugins-media.perfectcorp.com/smb/sdk.js';

	public function __construct() {
		add_action( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
		$this->api      = get_option( 'ycm_api' );
		$this->settings = get_option( 'ycm_settings' );
		$this->init();
	}

	public function plugin_row_meta( $links, $file ) {
		if ( YOUCAM_MAKEUP_PLUGIN_BASENAME === $file ) {
			$row_meta = array(
				'docs' => '<a target="_blank" rel="noopener noferrer" href="' . esc_url( YOUCAM_MAKEUP_DOCUMENTATION_URL ) . '">' . esc_html__( 'Documentation', 'youcam-makeup' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	public function init(): void {
		if ( ! $this->api ) {
			return;
		}
		$response = YouCam_Makeup_Api::instance()->get_productNoMap();
		if ( 200 === $response['code'] ) {
			$this->skuNoMap = $response['body']['skuNoMap'];
		}
		
		add_action( 'woocommerce_after_shop_loop', function () {
			if ( ! empty( $this->skuNoMap ) ) {
				$this->render(
					'ycm-widget.php'
				);
			}
		}, 20 );
		if ( '1' === $this->settings['show_tryon_btn_in_category'] ) {
			add_action( 'woocommerce_' . $this->settings['tryon_btn_position_in_category'], function () {
				$this->render_tryon( 'tryon-button-product' );
			} );
		}

		add_action( 'woocommerce_after_single_product', function () {
			$response = YouCam_Makeup_Api::instance()-> get_product( wc_get_product() -> get_id() );

			if ( 200 === $response['code'] ) {
				$this->skuType = $response['body']['skuType'];
			}
			$this->render_tryon( 'ycm-widget' );
		}, 20 );

		if ( '1' === $this->settings['show_tryon_btn_in_product'] ) {
			add_action( 'woocommerce_' . $this->settings['tryon_btn_position_in_product'], function () {
				$this->render_tryon( 'tryon-button-product' );
			}, 20 );
		}


		add_action( 'wp_enqueue_scripts', function () {
			if ( is_woocommerce() ) {
				wp_enqueue_script(
					'ycm-widget',
					self::$SDK_URL . '?apiKey=' . $this->api['api_key'],
					array(),
					null,
					true
				);

				wp_add_inline_script('ycm-widget', "(function(w){w.wp = w.wp || {};})(window);", 'before');
				wp_enqueue_style(
					'ycm-widget-style',
					plugins_url( '/includes/style.css', YOUCAM_MAKEUP_FILE ),
					array(),
					YOUCAM_MAKEUP_VERSION,
				);
			}
		} );
	}

	private function render_tryon( $view ): void {
		global $product;
		if ( array_key_exists( $product->get_id(), $this->skuNoMap ) ) {
			$this->render(
				$view . '.php',
				array(
					'product_id'     => $product->get_id(),
					'tryon_btn_text' => $this->settings['tryon_btn_text'] ?: 'TRY ON'
				)
			);
		}
	}

	private function is_display_product_cat_list_in_category(){
		return '1' === $this->settings['product_cat_list_in_category'];
	}

	private function is_display_product_cat_list_in_product(){
		return '1' === $this->settings['product_cat_list_in_product'];
	}

	private function is_show_widget_in_category(){
		return '1' === $this->settings['show_widget_in_category'];
	}

	private function is_show_widget_in_product(){
		return '1' === $this->settings['show_widget_in_product'];
	}

	private function get_sku_type(){
		return $this->skuType;
	}

	public function render( $template_name, array $parameters = array() ): void {
		foreach ( $parameters as $name => $value ) {
			${$name} = $value;
		}
		include YOUCAM_MAKEUP_BASE_PATH . '/includes/' . $template_name;
	}
}