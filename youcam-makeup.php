<?php
/**
 * Plugin Name: YouCam Makeup
 * Plugin URI: https://www.perfectcorp.com/document-center/en/#woocommerce-installation-guide
 * Description: YouCam Makeup allows the customer to experience the easiest self-service virtual makeup try-on widget.
 * Version: 1.0.2
 * Author: Perfect Corp.
 * Author URI: https://www.perfectcorp.com/business
 * Text Domain: youcam-makeup
 * Domain Path: /lang
 * Copyright: © 2023 Perfect Corp.
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * WC requires at least: 4.0
 * WC tested up to: 7.4
 */

defined( 'ABSPATH' ) || exit;

define( 'YOUCAM_MAKEUP_VERSION', '1.02' );
define( 'YOUCAM_MAKEUP_BASE_PATH', plugin_dir_path( __FILE__ ) );
define( 'YOUCAM_MAKEUP_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'YOUCAM_MAKEUP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'YOUCAM_MAKEUP_FILE', __FILE__ );
define( 'YOUCAM_MAKEUP_DOCUMENTATION_URL', 'https://www.perfectcorp.com/document-center/en/#woocommerce-installation-guide' );
define( 'YOUCAM_MAKEUP_PLAN_URL', 'https://www.perfectcorp.com/business/plan' );


register_activation_hook( __FILE__, function () {
	update_option( 'ycm_activate_on', time() );
	update_option( 'ycm_version', YOUCAM_MAKEUP_VERSION );

	$ycm_settings = get_option( 'ycm_settings', array(
		'product_cat_list_in_product'    => '1',
		'product_cat_list_in_category'   => '1',
		'show_widget_in_product'         => '1',
		'show_widget_in_category'        => '1',
		'tryon_btn_text'                 => 'Try On',
		'show_tryon_btn_in_category'     => '1',
		'tryon_btn_position_in_category' => 'after_shop_loop_item',
		'show_tryon_btn_in_product'      => '1',
		'tryon_btn_position_in_product'  => 'after_add_to_cart_button',
	) );
	update_option( 'ycm_settings', $ycm_settings );

	$ycm_api = get_option( 'ycm_api', array() );
	update_option( 'ycm_api', $ycm_api );
} );

add_action(
	'woocommerce_init',
	function () {
		require_once 'classes/admin/class-youcam-makeup-api.php';
		require_once 'classes/class-youcam-makeup.php';
		new YouCam_Makeup();

		if ( is_admin() ) {
			require_once 'classes/admin/class-youcam-makeup-admin-tab.php';
			new YouCam_Makeup_Admin();
			require_once 'classes/admin/class-youcam-makeup-product.php';
			new YouCam_Makeup_Admin_Product();
		}
	}
);

require_once YOUCAM_MAKEUP_PLUGIN_DIR . 'includes/links.php';