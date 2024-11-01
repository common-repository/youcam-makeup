<?php
defined( 'ABSPATH' ) || exit;
?>
<style>
    .form-table th {
        white-space: nowrap;
    }
</style>
<div class="wrap">
    <h1 clas="wp-heading-inline"><?php _e( 'YouCam Makeup Settings', 'youcam-makeup' ); ?></h1>
	<?php

	$tabs = array( 'api' => 'API connection', 'settings' => 'Settings' );
	?>

    <nav class="nav-tab-wrapper wp-clearfix">
		<?php
		foreach ( $tabs as $k => $v ) {
			echo '<a href="' . esc_url( home_url() . '/wp-admin/admin.php?page=ycm&tab=' . $k ) . '" class="nav-tab ' . ( $k === $tab ? 'nav-tab-active' : '' ) . '">' . esc_html( $v ) . '</a>';
		}
		?>
    </nav>

	<?php
	if ( 'api' === $tab ) :
		?>
        <h2><?php _e( 'YouCam Makeup API Connection', 'youcam-makeup' ); ?></h2>
		<?php if ( $data && $data['api_key'] ) : ?>
        <div class="notice notice-success">
            <p>
				<?php
				_e( 'You have now successfully connected YouCam Makeup to your WordPress site.',
					'youcam-makeup'
				)
				?>
            </p>
        </div>

        <p>
			<?php
			echo sprintf(
				__( '<b>YouCam Makeup API key currently linked</b><br>%s', 'youcam-makeup' ),
				esc_html( $data['api_key'] )
			);
			?>
        </p>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"
              id="ycm_api_disconnect_form">
            <input type="hidden" name="action" value="ycm_api_disconnect">
            <input type="hidden" name="ycm_api_disconnect_nonce"
                   value="<?php echo esc_attr( wp_create_nonce( 'ycm_api_disconnect_nonce' ) ); ?>"/>
            <input type="submit" name="submit" id="submit" class="button button-primary"
                   value="<?php _e( 'Disconnect', 'youcam-makeup' ); ?>">
        </form>

        <hr style="border-top: 1px solid; margin-top: 50px;">
        <h2>How to Enable Virtual Try-On for Your Product?</h2>
        <div>
            <ol>
                <li>
					<?php
					_e( 'In the WordPress admin panel, go to the <a href="' . esc_url( admin_url( 'edit.php?post_type=product' ) ) . '">Products</a> section and select the product you want to enable virtual try-on for. Click on "<strong>Edit</strong>".
					(Note: If you want to enable virtual try-on for a new product, click on "<strong>Add New</strong>")', 'youcam-makeup'
					)
					?>
                </li>
                <li>
					<?php _e( 'If you have successfully connected the API key, you will find the YouCam Makeup plugin on the right-hand side of the page.', 'youcam-makeup' ) ?>
                </li>
                <li>
					<?php _e( 'Click on the plugin to edit the virtual try-on effect for your product.', 'youcam-makeup' ) ?>
                </li>
                <li>
					<?php _e( 'The product name, product image, and permalink will be pre-filled according to your product\'s settings. <br>Select the appropriate category for your product to adjust the right parameters for a realistic virtual try-on effect. <br>You can preview the result in either Photo or Live mode.', 'youcam-makeup' ) ?>
                </li>
                <li>
					<?php _e( 'Click "<strong>Add</strong>" to save your settings, go back to the WordPress and reload your "<strong>Edit Product Page</strong>". Then, click "<strong>Update</strong>" in the "<strong>Publish</strong>" block.', 'youcam-makeup' ) ?>
                </li>
                <li>
					<?php _e( 'A try-on button will now be shown on your product page, allowing your customers to virtually try on your product.', 'youcam-makeup' ) ?>
                </li>
            </ol>
        </div>
        <h3>Note:</h3>
        <p>
			<?php _e( 'The synchronization of product name, product image, and permalink will only occur the first time you create the virtual try-on SKU.
                     <br>If you make any updates to the product name, product image, or permalink after creating the virtual try-on SKU, please remember to <strong>manually</strong> update this information in the YouCam Makeup Console as well.', 'youcam-makeup' ) ?>
        </p>

	<?php else : ?>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="ycm_api_connect_form">
            <input type="hidden" name="action" value="ycm_api_connect">
            <input type="hidden" name="ycm_api_connect_nonce"
                   value="<?php echo esc_attr( wp_create_nonce( 'ycm_api_connect_nonce' ) ); ?>"/>
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <label for="ycm-api_key">
							<?php _e( 'API Key', 'youcam-makeup' ); ?>
                        </label>
                    </th>
                    <td>
                        <input required name="ycm[api_key]" id="ycm-api_key" class="regular-text" type="text">
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="submit" name="submit" id="submit" class="button button-primary"
                   value="<?php _e( 'Connect', 'youcam-makeup' ); ?>">
        </form>

        <hr style="border-top: 1px solid; margin-top: 50px;">
        <h2>How to Connect API Key?</h2>
        <div>
            <ol>
                <li>
					<?php
					_e( 'Sign up for a <a href="https://www.perfectcorp.com/business/user/free-trial" target="_blank">YouCam Makeup free trial account</a>.',
						'youcam-makeup'
					)
					?>
                </li>
                <li>
					<?php
					_e( 'Go to the <a href="https://smb.perfectcorp.com/web-module/api-integration" target="_blank">API Integration</a> section and copy your API key.',
						'youcam-makeup'
					)
					?>
                </li>
                <li>
					<?php
					_e( 'Go back to your WordPress admin panel and Paste your API key and click on "<strong>Connect</strong>".',
						'youcam-makeup'
					)
					?>
                </li>
            </ol>
        </div>
	<?php endif; ?>

	<?php elseif ( 'settings' === $tab ) : ?>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="ycm_settings_form">
            <input type="hidden" name="action" value="ycm_settings">
            <input type="hidden" name="ycm_settings_nonce"
                   value="<?php echo esc_attr( wp_create_nonce( 'ycm_settings_nonce' ) ); ?>"/>
            <table class="form-table">
                <tbody>
                <tr>
                    <th><h2 style="margin: 0;"><?php _e( 'Prodcut Category List', 'youcam-makeup' ); ?>
                        <?php echo wc_help_tip( 'This config determines whether to display the product category hint (highlighted in red) on the module. <img src="'. plugins_url( '/includes/images/category_tip.png', YOUCAM_MAKEUP_FILE ) . '" alt="Category Tip" width="150px" />' ); ?>
                    </h2>
                    </th>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-product_cat_list_in_category">
							<?php _e( 'Enable on WooCommerce catalog page', 'youcam-makeup' ); ?>
                        </label>
                    </th>
                    <td>
                        <fieldset>
                            <input type="checkbox" <?php checked( $data['product_cat_list_in_category'] ); ?>
                                   name="product_cat_list_in_category" id="ycm-product_cat_list_in_category" value="1">
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-product_cat_list_in_product">
							<?php _e( 'Enable on WooCommerce Product page', 'youcam-makeup' ); ?>
                        </label>
                    </th>
                    <td>
                        <fieldset>
                            <input type="checkbox" <?php checked( $data['product_cat_list_in_product'] ); ?>
                                   name="product_cat_list_in_product" id="ycm-product_cat_list_in_product" value="1">
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th><h2 style="margin: 0;"><?php _e( 'Widget button', 'youcam-makeup' ); ?></h2></th>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-show_widget_in_category">
							<?php _e( 'Enable on WooCommerce catalog page', 'youcam-makeup' ); ?>
                        </label>
                    </th>
                    <td>
                        <fieldset>
                            <input type="checkbox" <?php checked( $data['show_widget_in_category'] ); ?>
                                   name="show_widget_in_category" id="ycm-show_widget_in_category" value="1">
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-show_widget_in_product">
							<?php _e( 'Enable on WooCommerce Product page', 'youcam-makeup' ); ?>
                        </label>
                    </th>
                    <td>
                        <fieldset>
                            <input type="checkbox" <?php checked( $data['show_widget_in_product'] ); ?>
                                   name="show_widget_in_product" id="ycm-show_widget_in_product" value="1">
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th><h2 style="margin: 0;"><?php _e( 'Try on button', 'youcam-makeup' ); ?></h2></th>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-tryon_btn_text">
							<?php _e( 'Try On button text', 'youcam-makeup' ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="tryon_btn_text" id="ycm-tryon_btn_text"
                               value="<?php echo esc_html( $data['tryon_btn_text'] ); ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-show_tryon_btn_in_category">
							<?php _e( 'Enable on WooCommerce catalog page', 'youcam-makeup' ); ?>
                        </label>
                    </th>
                    <td>
                        <fieldset>
                            <input type="checkbox" <?php checked( $data['show_tryon_btn_in_category'] ); ?>
                                   name="show_tryon_btn_in_category" id="ycm-show_tryon_btn_in_category" value="1">
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-tryon_btn_position_in_category">
							<?php _e( 'Button Position on WooCommerce catalog page' ); ?>
                        </label>
                    </th>
                    <td>
                        <select name="tryon_btn_position_in_category" id="ycm-tryon_btn_position_in_category">
							<?php foreach ( $category_page_actions as $id => $display ) : ?>
                                <option <?php selected( $data['tryon_btn_position_in_category'] == $id ); ?>
                                        value="<?php echo esc_attr( $id ); ?>">
									<?php echo esc_html( $display ); ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-show_tryon_btn_in_product">
							<?php _e( 'Enable on WooCommerce Product page', 'youcam-makeup' ); ?>
                        </label>
                    </th>
                    <td>
                        <fieldset>
                            <input type="checkbox" <?php checked( $data['show_tryon_btn_in_product'] ); ?>
                                   name="show_tryon_btn_in_product" id="ycm-show_tryon_btn_in_product" value="1">
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="ycm-tryon_btn_position_in_product">
							<?php _e( 'Button Position on WooCommerce product page' ); ?>
                        </label>
                    </th>
                    <td>
                        <select name="tryon_btn_position_in_product" id="ycm-tryon_btn_position_in_product">
							<?php foreach ( $product_page_actions as $id => $display ) : ?>
                                <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $data['tryon_btn_position_in_product'] == $id ); ?>>
									<?php echo esc_html( $display ); ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
			<?php submit_button(); ?>
        </form>
	<?php endif; ?>
</div>