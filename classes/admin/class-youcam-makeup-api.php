<?php

defined( 'ABSPATH' ) || exit;

class YouCam_Makeup_Api {

	private static $_instance;

	public function connect( $api_key ): array {
		$args     = array(
			'body' => array(
				'apiKey' => $api_key,
				'functionType' => 'MAKEUP',
				'platform' => 'WOO_COMMERCE',
			),
		);
		$response = wp_remote_get( YouCam_Makeup::$SMB_URL . 'smb-backend/auth/check-apikey.action', $args );

		return $this->build( $response );
	}

	public function get_product( $id ): array {
		$args     = array(
			'body' => array(
				'apiKey'            => get_option( 'ycm_api' )['api_key'],
				'platformProductId' => $id,
				'platform'          => 'WOO_COMMERCE',
			),
		);
		$response = wp_remote_get( YouCam_Makeup::$SMB_URL . 'smb-backend/sku/check-platform-sku.action', $args );

		return $this->build( $response );
	}

	public function get_productNoMap(): array {
		$args     = array(
			'body' => array(
				'apiKey'   => get_option( 'ycm_api' )['api_key'],
				'platform' => 'WOO_COMMERCE',
			),
		);
		$response = wp_remote_get( YouCam_Makeup::$API_URL . 'query-product-summary.action', $args );

		return $this->build( $response );
	}

	private function build( $response ): array {
		if ( is_wp_error( $response ) ) {
			return array(
				'code' => 500,
				'body' => $response->get_error_message(),
			);
		}

		return array(
			'code' => wp_remote_retrieve_response_code( $response ),
			'body' => json_decode( wp_remote_retrieve_body( $response ), true ),
		);
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

YouCam_Makeup_Api::instance();
