<?php
add_action('init', 'mcfw_front_menu_cart');

require_once( MCFW_PLUGIN_DIR_PATH .'front/menu-cart.php');
require_once( MCFW_PLUGIN_DIR_PATH .'front/sticky-cart.php');
require_once( MCFW_PLUGIN_DIR_PATH .'front/front-design-element.php');

function mcfw_front_menu_cart(){
    $menu_cart  = new mcfw_menu_cart();
    $menu_cart->mcfw_set_menu();

    $sticky_cart  = new mcfw_sticky_cart();
    $sticky_cart->mcfw_set_sticky_cart();

    $design  = new mcfw_design();
    $design->mcfw_set_design();
}
?>