<?php

defined( 'ABSPATH' ) || exit;

class YouCam_Makeup_Admin_Product {
	function __construct() {
		add_action( 'add_meta_boxes_product', function () {
			add_meta_box(
				'ycm_product_meta_box_name',
				__( 'Youcam Makeup', 'youcam-makeup' ),
				array( $this, 'render_meta_box' ),
				'product',
				'side',
				'high'
			);
		} );
	}

	private function render_connect_api_box(): void {
		if ( ! get_option( 'ycm_api' ) ) {
			_e( 'To begin setting up the virtual try-on, you must first connect your WordPress site with the YouCam Makeup Console.', 'youcam-makeup' );
			?>
            <p>
                <a href="<?php echo esc_url(admin_url( 'admin.php?page=ycm&tab=api' )) ?>" class="button">
					<?php
					_e( 'Connect API', 'youcam-makeup' );
					?>
                </a>
            </p>
            <p>
			<?php
		}
	}

	public function render_meta_box( $post ): void {
		if ( ! get_option( 'ycm_api' ) ) {
			$this->render_connect_api_box();

			return;
		}
		if ( 'publish' !== $post->post_status ) {
			_e( 'You need to first publish your product and then use Edit to link to YouCam Makeup Console to create AR SKU.', 'youcam-makeup' );

			return;
		}
		$product    = wc_get_product();
		$query_data = array(
			'platformPermalink'    => $product->get_permalink(),
			'platformProductName'  => $product->get_name(),
			'platformProductId'    => $product->get_id(),
			'platformProductImage' => wp_get_attachment_url( $product->get_image_id() ),
			'platform'             => 'WOO_COMMERCE',
		);
		$response   = YouCam_Makeup_Api::instance()->get_product( $product->get_id() );
		$ycm_url    = YouCam_Makeup::$SMB_URL . 'products/';

		if ( 200 === $response['code'] ) {
			$ycm_url .= 'edit-sku?skuId=' . $response['body']['id'];
			_e( 'Go to YouCam Makeup Console to edit AR SKU.', 'youcam-makeup' );
			?>
            <p>
                <a href="<?php echo esc_url($ycm_url); ?>" class="button" target="_blank">
					<?php
					_e( 'Edit Try On SKU', 'youcam-makeup' );
					?>
                </a>
            </p>
            <p>
            <div><strong>Note:</strong></div>
			<?php
			_e( 'Update product information manually in YouCam Makeup Console if you change the name, image, or permalink of a virtual try-on SKU.', 'youcam-makeup' );
			?>
            </p>
			<?php
		} else if ( 404 === $response['code'] ) {
			$ycm_url .= 'add-sku?' . http_build_query( $query_data );
			_e( 'Link to YouCam Makeup Console to create AR SKU.', 'youcam-makeup' );
			?>
            <p>
                <a href="<?php echo esc_url($ycm_url); ?>" class="button" target="_blank">
					<?php
					_e( 'Create Try On SKU', 'youcam-makeup' );
					?>
                </a>
            </p>
			<?php
		}
	}
}