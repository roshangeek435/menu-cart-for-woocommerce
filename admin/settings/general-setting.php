<?php
if (!class_exists('mcfw_general_settings')) {
    $mcfw_general_options = get_option('mcfw_general_options');

    class mcfw_general_settings
    {
        public function __construct()
        {
            add_action('admin_init', array($this, 'register_general_settings_init'));
        }

        public function register_settings_init()
        { ?>
<form action="options.php" method="post" class="mcfw-general-setting">
    <?php settings_fields('mcfw-general-setting-options');   ?>
    <div class="mcfw-section">
        <?php
                    if (empty(wp_get_nav_menus())) { ?>
        <div class="error mcfw-input-note" style="width:620px; padding:10px;">
            <?php _e('You need to create a menu before you can use Menu Cart. Go to <strong>Appearence > Menus</strong> and create menu to add the cart to.', 'menu-cart-for-woocommerce'); ?>
        </div>
        <?php } ?>
        <?php do_settings_sections('mcfw_general_setting_section'); ?>
    </div>
    <?php submit_button('Save Settings'); ?>
</form>
<?php
        }

        /* register setting */
        public function register_general_settings_init()
        {
            register_setting('mcfw-general-setting-options', 'mcfw_general_options', array($this, 'sanitize_settings'));

            add_settings_section(
                'mcfw_general_setting_id',
                __('', 'menu-cart-for-woocommerce'),
                array(),
                'mcfw_general_setting_section'
            );

            add_settings_field(
                'menu_id',
                __('Select the menu(s) in which you want to display the Menu Cart', 'menu-cart-for-woocommerce'),
                array($this, 'menus_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'menu_id',
                ]
            );

            add_settings_field(
                'always_display',
                __("Always display cart, even if it's empty", 'menu-cart-for-woocommerce'),
                array($this, 'checkbox_element_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'always_display',
                ]
            );

            add_settings_field(
                'show_on_cart_checkout_page',
                __("Show on cart & checkout page", 'menu-cart-for-woocommerce'),
                array($this, 'checkbox_element_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'show_on_cart_checkout_page',
                    'description'    => __('To avoid distracting your customers with duplicate information we do not display the menu cart item on the cart & checkout pages by default', 'wp-menu-cart'),
                ]
            );

            add_settings_field(
                'price_format',
                __("How prices are displayed on the frontend", 'menu-cart-for-woocommerce'),
                array($this, 'price_format_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'price_format',
                ]
            );

            add_settings_field(
                'total_price_type',
                __('Price to display', 'menu-cart-for-woocommerce'),
                array($this, 'price_type_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'total_price_type',
                ]
            );

            add_settings_field(
                'cart_icon',
                __("Choose a cart icon", 'menu-cart-for-woocommerce'),
                array($this, 'menu_cart_icon_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'cart_icon',
                ]
            );

            add_settings_field(
                'menu_cart_formats',
                __("How would you like to display in the menu ?", 'menu-cart-for-woocommerce'),
                array($this, 'menu_cart_formats_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'menu_cart_formats',
                ]
            );

            add_settings_field(
                'page_redirect',
                __("Redirect to cart page or checkout page (when click on cart menu) ", 'menu-cart-for-woocommerce'),
                array($this, 'redirect_page_callback'),
                'mcfw_general_setting_section',
                'mcfw_general_setting_id',
                [
                    'label_for'     => 'page_redirect',
                ]
            );
        }

        public function menus_callback($args)
        {
            global $mcfw_general_options;
            $value = isset($mcfw_general_options[$args['label_for']]) ? explode(",", $mcfw_general_options[$args['label_for']]) : '';
            $mcfw_menus = wp_get_nav_menus();
        ?>
<select name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>][]" class="mcfw-select js-select2-multi"
    multiple="multiple">
    <option value="" disabled></option>
    <?php
                if (isset($mcfw_menus) && !empty($mcfw_menus)) {
                    foreach ($mcfw_menus as $key => $values) {  ?>
    <option value="<?php esc_attr_e($values->term_id) ?>" <?php if (isset($value) && (!empty($value)) && (in_array($values->term_id, $value))) {
                                                                                    _e('selected', 'menu-cart-for-woocommerce');
                                                                                } ?>>
        <?php esc_attr_e($values->name); ?></option>
    <?php }
                } ?>
</select>
<p class="mcfw-select-err mcfw-input-note">
    <?php _e('Below options will be ineffective if not select any menu.', 'menu-cart-for-woocommerce'); ?></p>
<?php
        }

        public function checkbox_element_callback($args)
        {
            global $mcfw_general_options;
            $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : ''; ?>
<label class="mcfw-switch">
    <input type="checkbox" class="mcfw-checkbox" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]"
        <?php if ($value == "on") {
                                                                                                                                            _e('checked', 'menu-cart-for-woocommerce');
                                                                                                                                        } ?>>
    <span class="mcfw-slider"></span>
</label>
<?php
            if (!empty($args['description'])) { ?> <p class="mcfw-input-note">
    <?php esc_attr_e($args['description'], 'menu-cart-for-woocommerce') ?></p>
<?php }
                                                                                                                                                    }

                                                                                                                                                    public function price_format_callback($args)
                                                                                                                                                    {
                                                                                                                                                        global $mcfw_general_options;
                                                                                                                                                        $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
                                                                                                                                                            ?>
<div class="mcfw_price_wrap">
    <div class="mcfw_price_main">
        <input type="radio" id="Currency" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]"
            value="currency"
            <?php if ($value == 'currency') {
                                                                                                                                                            _e('checked', 'menu-cart-for-woocommerce');
                                                                                                                                                        } ?>> 
        <label for="Currency"><?php _e('Currency', 'menu-cart-for-woocommerce'); ?></label>
        <span class="mcfw-input-note"><?php _e('e.g. USD', 'menu-cart-for-woocommerce'); ?></span>
    </div>
    <div class="mcfw_price_main">
        <input type="radio" id="Currency Symbol" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]"
            value="currency_symbol"
            <?php if ($value == 'currency_symbol') {
                                                                                                                                                                    _e('checked');
                                                                                                                                                                } ?>><label
            for="Currency Symbol"><?php _e('Currency Symbol', 'menu-cart-for-woocommerce'); ?></label>
        <span class="mcfw-input-note"><?php _e('e.g. $', 'menu-cart-for-woocommerce'); ?></span>
    </div>
</div>
<?php
                                                                                                                                                    }

                                                                                                                                                    public function price_type_callback($args)
                                                                                                                                                    {
                                                                                                                                                        global $mcfw_general_options;
                                                                                                                                                        $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
                                                                                                                                                        $options = array(
                                                                                                                                                            'total_including_discount'          => 'Cart total (including discounts)',
                                                                                                                                                            'subtotal'         => 'Subtotal (total of products)',
                                                                                                                                                            'checkout_total_including_shipping' => 'Checkout total (including discounts, fees & shipping)',
                                                                                                                                                        ); ?>
<select name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]">
    <?php
                                                                                                                                                        foreach ($options as $key => $values) { ?>
    <option value="<?php esc_attr_e($key); ?>"
        <?php if ($key == $value) {
                                                                                                                                                                _e('selected', 'menu-cart-for-woocommerce');
                                                                                                                                                            } ?>>
        <?php esc_attr_e($values); ?></option>
    <?php } ?>
</select>
<?php
                                                                                                                                                    }

                                                                                                                                                    public function menu_cart_icon_callback($args)
                                                                                                                                                    {
                                                                                                                                                        global $mcfw_general_options;
                                                                                                                                                        $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
                                                                                                                                                        $options = array(
                                                                                                                                                            'cart1',
                                                                                                                                                            'cart2',
                                                                                                                                                            'cart3',
                                                                                                                                                            'cart4',
                                                                                                                                                            'cart5',
                                                                                                                                                            'cart6',
                                                                                                                                                            'cart7',
                                                                                                                                                            'cart8',
                                                                                                                                                            'cart9',
                                                                                                                                                            'cart10',
                                                                                                                                                        );
                                                                                                                                                        foreach ($options as $key => $values) {
                                                                                                                                                            $icons = esc_url(MCFW_PLUGIN_URL . '/assets/images/menu_icons/' . $values . '.png'); ?>
<div class="mcfw-cart-options <?php esc_html_e(($values == $value) ? 'mcfw-current-cart-options' : ''); ?>">
    <input type="radio" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]"
        value="<?php esc_attr_e($values); ?>"
        <?php if ($values == $value) {
                                                                                                                                                                _e('checked');
                                                                                                                                                            } ?>>
    <img src="<?php esc_attr_e($icons); ?>" width="35" height="35" alt="<?php esc_attr_e($values); ?> cart icon">
</div>
<?php }
                                                                                                                                                    }

                                                                                                                                                    public function menu_cart_formats_callback($args)
                                                                                                                                                    {
                                                                                                                                                        global $mcfw_general_options;
                                                                                                                                                        $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
                                                                                                                                                        $options = array(
                                                                                                                                                            'icon_only',
                                                                                                                                                            'icon_items',
                                                                                                                                                            'icon_price',
                                                                                                                                                            'items_price',
                                                                                                                                                            'price_items',
                                                                                                                                                            'icon_items_price',
                                                                                                                                                            'icon_price_items'
                                                                                                                                                        );
                                                                                                                                                        foreach ($options as $key => $values) {
                                                                                                                                                            $icons = esc_url(MCFW_PLUGIN_URL . '/assets/images/menu_cart_formats/' . $values . '.png'); ?>
<div class="mcfw-cart-btn-img <?php esc_html_e(($values == $value) ? 'mcfw-current-cart-options' : ''); ?>">
    <input type="radio" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]"
        value="<?php esc_attr_e($values); ?>"
        <?php if ($values == $value) {
                                                                                                                                                                _e('checked', 'menu-cart-for-woocommerce');
                                                                                                                                                            } ?>>
    <img src="<?php esc_attr_e($icons); ?>" alt="<?php esc_attr_e($values); ?> cart button image">
</div>
<?php }
                                                                                                                                                    }

                                                                                                                                                    public function redirect_page_callback($args)
                                                                                                                                                    {
                                                                                                                                                        global $mcfw_general_options;
                                                                                                                                                        $value = isset($mcfw_general_options[$args['label_for']]) ? $mcfw_general_options[$args['label_for']] : '';
                                                                                                                                                        $options = array(
                                                                                                                                                            'cart'          => 'Cart',
                                                                                                                                                            'checkout'         => 'Checkout',
                                                                                                                                                        ); ?>
<div class="mcfw_price_wrap">
    <?php
                                                                                                                                                        foreach ($options as $key => $values) { ?>
    <div class="mcfw_price_main">
        <input type="radio" name="mcfw_general_options[<?php esc_attr_e($args['label_for']);  ?>]"
            value="<?php esc_attr_e($key); ?>"
            <?php if ($key == $value) {
                                                                                                                                                                _e('checked', 'menu-cart-for-woocommerce');
                                                                                                                                                            } ?>>
        <label for="<?php esc_attr_e($key); ?>"><?php esc_attr_e($values); ?></label>
    </div>
    <?php } ?>
</div>
<?php
                                                                                                                                                    }

                                                                                                                                                    public function sanitize_settings($input)
                                                                                                                                                    {
                                                                                                                                                        $new_input = array();

                                                                                                                                                        if (isset($input['menu_id']) && !empty($input['menu_id'])) {
                                                                                                                                                            if (is_array($input['menu_id'])) {
                                                                                                                                                                $menu_id_list = implode(",", $input['menu_id']);
                                                                                                                                                            } else {
                                                                                                                                                                $menu_id_list = sanitize_text_field($input['menu_id']);
                                                                                                                                                            }
                                                                                                                                                            $new_input['menu_id'] = sanitize_text_field($menu_id_list);
                                                                                                                                                        }

                                                                                                                                                        if (isset($input['always_display']) && !empty($input['always_display'])) {
                                                                                                                                                            $new_input['always_display'] = sanitize_text_field($input['always_display']);
                                                                                                                                                        }

                                                                                                                                                        if (isset($input['show_on_cart_checkout_page']) && !empty($input['show_on_cart_checkout_page'])) {
                                                                                                                                                            $new_input['show_on_cart_checkout_page'] = sanitize_text_field($input['show_on_cart_checkout_page']);
                                                                                                                                                        }

                                                                                                                                                        if (isset($input['price_format']) && !empty($input['price_format'])) {
                                                                                                                                                            $new_input['price_format'] = sanitize_text_field($input['price_format']);
                                                                                                                                                        }

                                                                                                                                                        if (isset($input['total_price_type']) && !empty($input['total_price_type'])) {
                                                                                                                                                            $new_input['total_price_type'] = sanitize_text_field($input['total_price_type']);
                                                                                                                                                        }

                                                                                                                                                        if (isset($input['cart_icon']) && !empty($input['cart_icon'])) {
                                                                                                                                                            $new_input['cart_icon'] = sanitize_text_field($input['cart_icon']);
                                                                                                                                                        }

                                                                                                                                                        if (isset($input['menu_cart_formats']) && !empty($input['menu_cart_formats'])) {
                                                                                                                                                            $new_input['menu_cart_formats'] = sanitize_text_field($input['menu_cart_formats']);
                                                                                                                                                        }

                                                                                                                                                        if (isset($input['page_redirect']) && !empty($input['page_redirect'])) {
                                                                                                                                                            $new_input['page_redirect'] = sanitize_text_field($input['page_redirect']);
                                                                                                                                                        }


                                                                                                                                                        return $new_input;
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                            }