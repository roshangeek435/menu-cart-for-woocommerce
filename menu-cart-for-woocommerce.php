<?php
/*
Plugin Name:  Menu Cart For Woocommerce
Description: 
Author: Geek Code Lab
Version: 1.0
WC tested up to: 6.4.1
Author URI: https://geekcodelab.com/
Text Domain : menu-cart-for-woocommerce
*/

if (!defined('ABSPATH')) exit;

if (!defined("MCFW_PLUGIN_DIR_PATH"))

    define("MCFW_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("MCFW_PLUGIN_URL"))

    define("MCFW_PLUGIN_URL", plugins_url() . '/' . basename(dirname(__FILE__)));

define("mcfw_BUILD", '1.0');

register_activation_hook(__FILE__, 'mcfw_plugin_active_menu_cart_for_woocommerce');
function mcfw_plugin_active_menu_cart_for_woocommerce()
{
    if (!class_exists('WooCommerce')) {
        die('Menu Cart For Woocommerce Plugin can not activate as it requires <b>WooCommerce</b> plugin.');
    }

    $mcfw_general_defaults_options = array(
        'price_format'        => 'currency_symbol',
        'total_price_type' => 'subtotal',
        'cart_icon'    => 'cart1',
        'menu_cart_formats' => 'icon_items_price',
        'page_redirect' => 'cart',
    );

    $mcfw_flyout_defaults_options = array(
        'flyout_status' => 'on',
        'flyout_contents' => 'hover',
        'page_redirect' => 'cart',
        'max_products' => '5',
        'cart_checkout_btn' => 'cart_checkout',
        'cart_btn_txt' => 'View cart',
        'checkout_btn_txt' => 'Checkout',
        'sub_total_txt' => 'Total'
    );

    $mcfw_sticky_defaults_options = array(
        'sticky_sidebar_cart_status' => 'on',
        'item_count' => 'yes',
        'sticky_cart_position' => 'mcfw_cart_bottom_right'
    );

    $mcfw_design_defaults_options = array(
        'currency_position' => 'mcfw_currency_postion_left_withspace',
        'cart_shape' => 'mcfw_round_cart',
        'btns_border' => ',none,'
    );

    $mcfw_general_options = get_option('mcfw_general_options');
    $mcfw_flyout_options = get_option('mcfw_flyout_options');
    $mcfw_sticky_cart_options = get_option('mcfw_sticky_cart_options');
    $mcfw_design_options = get_option('mcfw_design_options');

    if ($mcfw_general_options == false || $mcfw_flyout_options == false || $mcfw_sticky_cart_options == false || $mcfw_design_options == false) {
        update_option('mcfw_general_options', $mcfw_general_defaults_options);
        update_option('mcfw_flyout_options', $mcfw_flyout_defaults_options);
        update_option('mcfw_sticky_cart_options', $mcfw_sticky_defaults_options);
        update_option('mcfw_design_options', $mcfw_design_defaults_options);
    }
}

require_once(MCFW_PLUGIN_DIR_PATH . 'admin/option.php');
require_once(MCFW_PLUGIN_DIR_PATH . 'front/index.php');

add_action('admin_print_styles', 'mcfw_admin_style');
function mcfw_admin_style()
{
    if (is_admin()) {
        $js        =    plugins_url('/assets/js/admin-script.js', __FILE__);
        wp_enqueue_style('mcfw_coloris_style', plugins_url('assets/css/coloris.min.css', __FILE__), '', mcfw_BUILD);
        wp_enqueue_style('mcfw-select2-style', plugins_url('/assets/css/select2.min.css', __FILE__), '', mcfw_BUILD);
        wp_enqueue_style('mcfw_admin_style', plugins_url('assets/css/admin-style.css', __FILE__), '', mcfw_BUILD);


        wp_enqueue_script('mcfw-admin-select2-js', plugins_url('/assets/js/select2.min.js', __FILE__), array('jquery'), mcfw_BUILD);
        wp_enqueue_script('mcfw-admin-coloris-js', plugins_url('/assets/js/coloris.min.js', __FILE__), array('jquery'), mcfw_BUILD);
        wp_enqueue_script('mcfw_admin_js',  plugins_url('/assets/js/admin-script.js', __FILE__), array('jquery', 'wp-color-picker'), mcfw_BUILD);
    }
}

add_action('wp_enqueue_scripts', 'mcfw_include_front_script');
function mcfw_include_front_script()
{
    wp_enqueue_style("mcfw_front_style", plugins_url("/assets/css/front_style.css", __FILE__), '', mcfw_BUILD);
    wp_enqueue_script('mcfw-front-js', plugins_url('/assets/js/front-script.js', __FILE__), array('jquery'), mcfw_BUILD);
    wp_localize_script('mcfw-front-js', 'custom_call', ['ajaxurl' => admin_url('admin-ajax.php'), 'general_data' => get_option('mcfw_general_options')]);
}

function mcfw_plugin_add_settings_link($links)
{
    $support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __('Support') . '</a>';
    array_unshift($links, $support_link);

    $settings_link = '<a href="admin.php?page=mcfw-option-page">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'mcfw_plugin_add_settings_link');

/** after page update button text html  */
add_filter('woocommerce_add_to_cart_fragments', 'mcfw_update_menu_count');
function mcfw_update_menu_count($fragments){

    ob_start();
    $mcfw_general_options1 = get_option('mcfw_general_options');
   

    $items_count = esc_attr(count(WC()->cart->get_cart()));
 
    $currency = esc_attr(($mcfw_general_options1['price_format'] == 'currency') ? get_woocommerce_currency() : get_woocommerce_currency_symbol());
    $menu_cart_formats = $mcfw_general_options1['menu_cart_formats'];
    if ($mcfw_general_options1['total_price_type'] == 'subtotal') {
        $price =  esc_attr(WC()->cart->subtotal);
    }
    if ($mcfw_general_options1['total_price_type'] == 'total_including_discount') {
        $price = esc_attr(WC()->cart->get_cart_contents_total());
    }
    if ($mcfw_general_options1['total_price_type'] == 'checkout_total_including_shipping') {
        $price = esc_attr(WC()->cart->total);
    }

    $output = '';
    
    $price_currency = '<span class="mcfw-mini-cart-price-wp">'.$price . '<span  class="mcfw-flyout-currency">' . $currency . '</span></span>';
    switch ($menu_cart_formats) {
        case 'icon_only':
            $output .= '';
            break;

        case 'icon_items':
            $output .= '' . $items_count . ' Items </span>';
            break;

        case 'icon_price':

            $output .= $price_currency;
            break;

        case 'items_price':
            $output .= '' . $items_count . ' Items - ' . $price_currency;
            break;

        case 'price_items':
            $output .= $price_currency . '- ' . $items_count . ' Items';
            break;

        case 'icon_price_items':
            $output .= $price_currency . '- ' . $items_count . ' Items';
            break;

        default:
            $output .= '' . $items_count . ' Items - ' . $price_currency;
            break;
    }  
    ?>
  
  <span class="mcfw-mini-cart-price-wp-demo">
        <?php _e($output); ?>
    </span>

    <?php
    $fragments['.mcfw-mini-cart-price-wp-demo'] = ob_get_clean();
    
    return $fragments;
}


/** Flyout HTML Start  */
add_filter('woocommerce_add_to_cart_fragments', 'mcfw_update_flyout');
function mcfw_update_flyout($fragments){

    ob_start();
    $mcfw_flyout_options1 = get_option('mcfw_flyout_options');
    $mcfw_general_options1 = get_option('mcfw_general_options');
    $items_count = esc_attr(count(WC()->cart->get_cart()));
    $currency = esc_attr(($mcfw_general_options1['price_format'] == 'currency') ? get_woocommerce_currency() : get_woocommerce_currency_symbol());

    if (!empty($mcfw_flyout_options1['flyout_status'])  && ($mcfw_flyout_options1['flyout_status'] == 'on')) { ?>
        <div class="mcfw-mini-cart-main">
            <?php if ($items_count != 0) { ?>
                <div class="mcfw-flyout-product-list">
                    <?php
                    $i = 1;
                    $mcfe_total = (($mcfw_flyout_options1['max_products'] == 'all') ? $items_count : $mcfw_flyout_options1['max_products']);
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                        if ($i <= $mcfe_total) {
                            $i++;
                            $product = $cart_item['data'];
                            $product_id = $cart_item['product_id'];
                            $product_img = esc_url(get_the_post_thumbnail_url($product_id)); ?>
                            <div class="mcfw-flyout-product">
                                <div class="mcfw-pro-img">
                                    <img src="<?php esc_attr_e($product_img); ?>" alt="" width="70" height="70" alt="<?php esc_attr_e($product->name); ?>">
                                </div>
                                <div class="mcfw-cart-item-qp">
                                    <a href="<?php esc_attr_e(get_permalink($product_id)); ?>" class="mcfw-cart-item-name"><?php esc_attr_e($product->name); ?></a>
                                    <span><?php esc_attr_e($cart_item['quantity']) ?> X</span>
                                    <span class="mcfw-mini-cart-price-wp">
                                        <?php esc_attr_e($product->price); ?>
                                        <span class="mcfw-flyout-currency"><?php esc_attr_e($currency); ?></span>
                                    </span>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
                <?php
                $btns = $mcfw_flyout_options1['cart_checkout_btn'];
                if ($btns != 'no_any_btn') { ?>
                    <div class="mcfw-cart-btn-wp mcfw_for_des">
                        <?php
                        if ($btns == 'cart' || $btns == 'cart_checkout') { ?> <a class="btn button mcfw-btns" href="<?php esc_attr_e(wc_get_cart_url()); ?>"><?php esc_attr_e(!empty($mcfw_flyout_options1['cart_btn_txt']) ? $mcfw_flyout_options1['cart_btn_txt'] : 'View Cart'); ?></a><?php }
                                                                                                                                                                                                                                                                                        if ($btns == 'checkout' || $btns == 'cart_checkout') { ?>
                            <a class="btn button mcfw-btns" href="<?php esc_attr_e(wc_get_checkout_url()); ?>"><?php esc_attr_e(!empty($mcfw_flyout_options1['checkout_btn_txt']) ? $mcfw_flyout_options1['checkout_btn_txt'] : 'CheckOut'); ?></a><?php }  ?>
                    </div>

                    <div class="mcfw-cart-btn-wp mcfw_for_mob">
                        <?php
                        if ($btns == 'cart' || $btns == 'cart_checkout') { ?> <a class="mcfw-btns" href="<?php esc_attr_e(wc_get_cart_url()); ?>"><?php esc_attr_e(!empty($mcfw_flyout_options1['cart_btn_txt']) ? $mcfw_flyout_options1['cart_btn_txt'] : 'View Cart'); ?></a><?php }
                                                                                                                                                                                                                                                                            if ($btns == 'checkout' || $btns == 'cart_checkout') { ?>
                            <a class="mcfw-btns" href="<?php esc_attr_e(wc_get_checkout_url()); ?>"><?php esc_attr_e(!empty($mcfw_flyout_options1['checkout_btn_txt']) ? $mcfw_flyout_options1['checkout_btn_txt'] : 'CheckOut'); ?></a><?php }  ?>
                    </div>
                <?php } ?>
                <div class="mcfw-sub-total">
                    <label><?php esc_attr_e(!empty($mcfw_flyout_options1['sub_total_txt']) ? $mcfw_flyout_options1['sub_total_txt'] : 'Subtotal'); ?>
                        :</label>
                    <b>
                        <span class="mcfw-mini-cart-price-wp">

                            <?php esc_attr_e(WC()->cart->total); ?>
                            <span class="mcfw-flyout-currency"><?php esc_attr_e($currency); ?></span>
                        </span>
                    </b>
                </div>

            <?php
            } else { ?>
                <p><?php _e('Your Cart Is Currently Empty.', 'menu-cart-for-woocommerce'); ?></p>
            <?php } ?>
        </div>
    <?php  }
    $fragments['.mcfw-mini-cart-main'] = ob_get_clean();
    return $fragments;
}


add_filter('woocommerce_add_to_cart_fragments', 'mcfw_update_sticky_count');
function mcfw_update_sticky_count($fragments){
    ob_start();
    $mcfw_general_options1 = get_option('mcfw_general_options');
    $mcfw_sticky_cart_options = get_option('mcfw_sticky_cart_options');
    $mcfw_sticky_cart_status = esc_attr(isset($mcfw_sticky_cart_options['sticky_sidebar_cart_status']) ? $mcfw_sticky_cart_options['sticky_sidebar_cart_status'] : '');
    $items_count = esc_attr(count(WC()->cart->get_cart()));
    $mcfw_always_cart = esc_attr((isset($mcfw_general_options1['always_display'])) && (!empty($mcfw_general_options1['always_display'])) ? $mcfw_general_options1['always_display'] : ''); ?>

    <span class="mcfw-sticky-count">
        <span><?php esc_attr_e($items_count); ?></span>
    </span>
<?php
    $fragments['.mcfw-sticky-count'] = ob_get_clean();
    return $fragments;
}
