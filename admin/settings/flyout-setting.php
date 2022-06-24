<?php
if (!class_exists('mcfw_flyout_settings')) {
    $mcfw_flyout_options=get_option('mcfw_flyout_options');

    class mcfw_flyout_settings{
        public function __construct(){       
            add_action('admin_init', array($this, 'register_flyout_settings_init'));
        }

        public function register_flyout_design_settings_init(){ ?>
            <form class="mcfw-general-setting" action="options.php?tab=mcfw-flyout" method="post">
                <?php  settings_fields('mcfw-flyout-setting-options');   ?>
                <div class="mcfw-section">
                    <?php do_settings_sections('mcfw_flyout_setting_section'); ?>
                </div>
                <?php submit_button('Save Settings'); ?>
            </form>
            <?php
        }

        /* register setting */
        public function register_flyout_settings_init()
        {
            register_setting('mcfw-flyout-setting-options', 'mcfw_flyout_options', array($this, 'sanitize_settings'));
            
            add_settings_section(
                'mcfw_flyout_setting_id',
                __('', 'woocommerce-shop-page-customizer'),
                array(),
                'mcfw_flyout_setting_section'
            );

            add_settings_field(
                'flyout_status',
                __('Flyout', 'woocommerce-shop-page-customizer'),
                array($this, 'flyout_checkbox_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'flyout_status',
                ]
            );

            add_settings_field(
                'flyout_contents',
                __('Display Flyout contents', 'woocommerce-shop-page-customizer'),
                array($this, 'flyout_contents_radio_callback'),
                'mcfw_flyout_setting_section',
                'mcfw_flyout_setting_id',
                [
                    'label_for'     => 'flyout_contents',
                ]
            );

            add_settings_field(
                    'max_products',
                    __("Set maximum number of products to display in fly-out", 'menu-cart-for-woocommerce'),
                    array($this, 'max_products_callback'),
                    'mcfw_flyout_setting_section',
                    'mcfw_flyout_setting_id',
                    [
                        'label_for'     => 'max_products', 
                    ]
            );

            add_settings_field(
                    'cart_checkout_btn',
                    __("Display cart or checkout button on frontend", 'menu-cart-for-woocommerce'),
                    array($this, 'cart_checkout_btn_callback'),
                    'mcfw_flyout_setting_section',
                    'mcfw_flyout_setting_id',
                    [
                        'label_for'     => 'cart_checkout_btn', 
                    ]
            );

            add_settings_field(
                    'cart_btn_txt',
                    __("Cart Button Text", 'menu-cart-for-woocommerce'),
                    array($this, 'cart_btn_txt_callback'),
                    'mcfw_flyout_setting_section',
                    'mcfw_flyout_setting_id',
                    [
                        'label_for'     => 'cart_btn_txt', 
                    ]
            );

            add_settings_field(
                    'checkout_btn_txt',
                    __("Checkout Button Text", 'menu-cart-for-woocommerce'),
                    array($this, 'cart_btn_txt_callback'),
                    'mcfw_flyout_setting_section',
                    'mcfw_flyout_setting_id',
                    [
                        'label_for'     => 'checkout_btn_txt', 
                    ]
            );

            add_settings_field(
                    'sub_total_txt',
                    __("Total Text", 'menu-cart-for-woocommerce'),
                    array($this, 'cart_btn_txt_callback'),
                    'mcfw_flyout_setting_section',
                    'mcfw_flyout_setting_id',
                    [
                        'label_for'     => 'sub_total_txt', 
                    ]
            );
            
        }

        public function flyout_checkbox_callback($args){
            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : ''; ?>
            <label class="mcfw-switch">
                <input type="checkbox" class="mcfw-checkbox" name="mcfw_flyout_options[<?php esc_attr_e( $args['label_for'] );  ?>]" value="on" <?php if ($value=='on') { _e('checked');} ?>>
                <span class="mcfw-slider"></span>
            </label>
            <?php
        }

        public function flyout_contents_radio_callback($args){
            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : ''; 
            $options=array(
                'click'          => 'On Menu Click ',
                'hover'		 => 'On Menu Hover' ,
            ); 
            foreach ($options as $key => $values) { ?>
            <div class="mcfw_price_main">
                    <input type="radio" class="mcfw_content" name="mcfw_flyout_options[<?php esc_attr_e( $args['label_for'] );  ?>]" value="<?php esc_attr_e($key); ?>" <?php if ($key==$value) { _e('checked'); } ?>><?php esc_attr_e($values); ?>
            </div>
            <?php }
        }

        public function redirect_page_callback($args){
            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : ''; 
            $options=array(
                'cart'          => 'Cart',
                'checkout'		 => 'Checkout' ,
            ); ?>
            <?php 
            foreach ($options as $key => $values) { ?>
                <div class="mcfw_price_main">
                    <input type="radio"  name="mcfw_flyout_options[<?php esc_attr_e( $args['label_for'] );  ?>]" value="<?php esc_attr_e($key); ?>" <?php if ($key==$value) { _e('checked'); } ?>><?php esc_attr_e($values); ?>
                </div>
            <?php } 
        }

        public function max_products_callback($args){
            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : '';  ?>
                <select name="mcfw_flyout_options[<?php esc_attr_e( $args['label_for'] );  ?>]" >
                    <?php
                        for ($i=1; $i <=10 ; $i++) {  ?>
                            <option value="<?php esc_attr_e($i); ?>" <?php if ($value==$i) { _e('selected','menu-cart-for-woocommerce'); } ?>><?php esc_attr_e($i); ?></option>
                            <?php }?>
                            <option value="all" <?php if ($value=='all') { _e('selected','menu-cart-for-woocommerce'); } ?>>All</option>
                </select>
            <?php 
        }

        public function cart_checkout_btn_callback($args){
            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : '';  
            $options=array(
                'cart'          => 'View cart only',
                'checkout'		 => 'Checkout only' ,
				'cart_checkout' => 'Both cart and checkout' ,
                'no_any_btn'    =>  'Not any one'
            ); ?>
                <select name="mcfw_flyout_options[<?php esc_attr_e( $args['label_for'] );  ?>]" >
                <?php
                    foreach ($options as $key => $values) { ?>
                        <option value="<?php esc_attr_e($key); ?>" <?php if ($key==$value) { _e('selected','menu-cart-for-woocommerce'); } ?>><?php esc_attr_e($values); ?></option>
                <?php } ?>
                </select>
            <?php 
        }

        public function cart_btn_txt_callback($args){
            global $mcfw_flyout_options;
            $value = isset($mcfw_flyout_options[$args['label_for']]) ? $mcfw_flyout_options[$args['label_for']] : '';  ?>
                <input type="text" name="mcfw_flyout_options[<?php esc_attr_e( $args['label_for'] );  ?>]" value="<?php esc_attr_e($value);?>">
            <?php
        }

        public function sanitize_settings($input){
            
            if (isset($input['flyout_status']) && !empty($input['flyout_status'])) {
                $new_input['flyout_status']=sanitize_text_field($input['flyout_status']);
            }

            if (isset($input['flyout_contents']) && !empty($input['flyout_contents'])) {
                $new_input['flyout_contents']=sanitize_text_field($input['flyout_contents']);
            }
            
            // if (isset($input['page_redirect']) && !empty($input['page_redirect'])) {
            //     $new_input['page_redirect']=sanitize_text_field($input['page_redirect']);
            // }

            if (isset($input['max_products']) && !empty($input['max_products'])) {
                $new_input['max_products']=sanitize_text_field($input['max_products']);
            }
            
            if (isset($input['cart_checkout_btn']) && !empty($input['cart_checkout_btn'])) {
                $new_input['cart_checkout_btn']=sanitize_text_field($input['cart_checkout_btn']);
            }
            
            if (isset($input['cart_btn_txt']) && !empty($input['cart_btn_txt'])) {
                $new_input['cart_btn_txt']=sanitize_text_field($input['cart_btn_txt']);
            }
            
            if (isset($input['checkout_btn_txt']) && !empty($input['checkout_btn_txt'])) {
                $new_input['checkout_btn_txt']=sanitize_text_field($input['checkout_btn_txt']);
            }

            if (isset($input['sub_total_txt']) && !empty($input['sub_total_txt'])) {
                $new_input['sub_total_txt']=sanitize_text_field($input['sub_total_txt']);
            }
            
            return $new_input;
        }
        
    }
}
