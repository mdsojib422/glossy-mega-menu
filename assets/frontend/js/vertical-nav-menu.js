(function ($) {
    const gmvmwrapper = $(".glossymm-vertical-menu-wrapper");
    const gmvertical_menu = $("#glossymm-megamenu-vertical");
    const glossymm_navbar = $(".glossymm-vertical-navbar-nav");
    const glossymm_header = $(".glossymm-vertical-header");

    if (window.innerWidth > 991) {
        const body_margin = parseInt(glossymm_header.css('width'));
        const glmsvm_width = parseInt(gmvmwrapper.css('width'));

        $('body').css("margin-left", body_margin + 'px');
        gmvmwrapper.css({
            "left": body_margin + 'px',
            "width": (glmsvm_width - body_margin + 20) + 'px'
        });
    } else {
        const body_height = parseInt(glossymm_header.css('height'));
        const glmsvm_height = parseInt(glossymm_navbar.css('height'));

        $('body').css("margin-top", body_height + 'px');
        gmvmwrapper.css("top", body_height + 'px');
        glossymm_navbar.css("height", (glmsvm_height - body_height) + 'px');
    }

    if (gmvmwrapper.length > 0) {
        $("#glossymm_vertical_menu_open").on("click", function (e) {
            e.preventDefault();
            gmvertical_menu.toggleClass("show-header");
            $("body").toggleClass("disable_scroll_formenu");
        });
    }
})(jQuery);
