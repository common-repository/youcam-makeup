<?php
defined( 'ABSPATH' ) || exit;
?>

<?php
if ( isset( $product_id ) ) {
	?>
    <a class="YMK-btn ycm-tryon-btn wp-element-button button" refId="<?php echo esc_js( '__wc__' . $product_id ) ?>"
       type="button"
       style="display: none;">
	    <?php echo esc_html( $tryon_btn_text ) ?>
    </a>
    <p class="YMK-icon" 
        <?php echo ($this->is_show_widget_in_product()? '' : 'hide="true"') ?>
        <?php echo ($this->is_display_product_cat_list_in_product() && !empty( $this->get_sku_type() )? 'categories="' . esc_js($this->get_sku_type()) . '"': '') ?>>
    <p>
	<?php
} else {
    ?>
    <p class="YMK-icon" 
        <?php echo ($this->is_show_widget_in_category()? '' : 'hide="true"') ?> 
        <?php echo ($this->is_display_product_cat_list_in_category()? 'enableAllCategories="true"': '') ?>>
    <p>
    <?php
}
?>