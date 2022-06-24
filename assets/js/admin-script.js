jQuery(document).ready(function ($) {
    $(".js-select2-multi").select2();
    $(".js-select2-multi").on("focus", () => {
        inputElement.parent().find(".select2-search__field").prop("disabled", true);
    });

    jQuery(function () {
        jQuery(".mcfw-color-field").wpColorPicker();
    });

    jQuery(".mcfw-select").val() == "" ? jQuery(".mcfw-select-err").fadeIn() : jQuery(".mcfw-select-err").fadeOut();

    jQuery(".mcfw-select").on("change", function () {
        var selectVal = jQuery(".mcfw-select").val();
        selectVal == "" ? jQuery(".mcfw-select-err").fadeIn() : jQuery(".mcfw-select-err").fadeOut();
    });

    jQuery(".mcfw-cart-options input[type=radio]").click(function () {
        jQuery(".mcfw-cart-options").removeClass("mcfw-current-cart-options");
        jQuery(this).parent().addClass("mcfw-current-cart-options");
    });

    jQuery(".mcfw-cart-btn-img input[type=radio]").click(function () {
        jQuery(".mcfw-cart-btn-img").removeClass("mcfw-current-cart-options");
        jQuery(this).parent().addClass("mcfw-current-cart-options");
    });

    jQuery("body").on("click", ".mcfw-menu-list", function () {
        jQuery(".mcfw-mini-cart-main").addClass("mcfw-menu-list-open");
    });

    Coloris({
        el: ".mcfw_coloris",
        themeMode: "auto",
        swatches: ["#264653", "#2a9d8f", "#e9c46a", "#f4a261"],
        clearButton: {
            show: true,
            label: "Clear",
        },
        formatToggle: true,
        selectInput: true,
    });
});
