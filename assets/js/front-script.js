jQuery(document).ready(function ($) {
    setTimeout(refresh_cart_fragment, 1000);

    var $supports_html5_storage = true,
        cart_hash_key = wc_cart_fragments_params.cart_hash_key;

    var $fragment_refresh = {
        url: wc_cart_fragments_params.wc_ajax_url.toString().replace("%%endpoint%%", "get_refreshed_fragments"),
        type: "POST",
        data: {
            time: new Date().getTime(),
        },
        timeout: wc_cart_fragments_params.request_timeout,
        success: function (data) {
            if (data && data.fragments) {
                $.each(data.fragments, function (key, value) {
                    $(key).replaceWith(value);
                });

                if ($supports_html5_storage) {
                    sessionStorage.setItem(wc_cart_fragments_params.fragment_name, JSON.stringify(data.fragments));
                }

                $(document.body).trigger("wc_fragments_refreshed");
            }
        },
    };

    /* Named callback for refreshing cart fragment */
    function refresh_cart_fragment() {
        $.ajax($fragment_refresh);
    }
    jQuery(document.body).on("wc_cart_emptied", empty_cart);

    function empty_cart(params) {
        if (custom_call.general_data["always_display"] != "on") {
            jQuery(".mcfw-menu").hide();
        }
    }
    jQuery("body").on("added_to_cart", function () {
        // ((always_display_cart != 'on') ? jQuery('.mcfw-menu').hide() : jQuery('.mcfw-menu').show());
        jQuery(".mcfw-menu").show();
    });

    jQuery("body").on("click", ".mcfw-menu", function () {
        jQuery(this).toggleClass("mcfw-menu-show");
    });
});
