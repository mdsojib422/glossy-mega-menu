jQuery(document).ready(function ($) {
    "use strict";

    // Destructure default object from backend
    const {
        glossymm_enabled_options_template,
        menuitem_edit_popup_template,
        ajaxurl,
        security_nonce,
        ajax_loader,
        resturl
    } = obj;

    // Prepend template to nav menu page
    $("#post-body-content").prepend(glossymm_enabled_options_template);
    $("#post-body-content").prepend(menuitem_edit_popup_template);

    if ($('#glossymm_megamenu_enabled').is(":checked")) {
        appendMegaMenuEditLinks();
    }

    // Toggle Mega Menu and Class on Change
    $("#glossymm_megamenu_enabled").on("change", function () {
        handleMegaMenuToggle($(this));
        toggleMegaMenuClass($(this));
        $(this).is(":checked") ? appendMegaMenuEditLinks() : removeMegaMenuEditLinks();
    });

    // Toggle content button disabling
    $("#post-body-content").on("change", "#glossymm_megamenu_item_enabled", function () {
        $("#glossymm-builder-open").toggleClass("disabled", !$(this).is(":checked"));
    });

    // Handle click event for editing Mega Menu
    $("#menu-to-edit").on("click", ".glossymm_megamenu_trigger", function (e) {
        handleMegaMenuEditClick(e, this);
    });

    // Popup tab click handling
    $(".glossymm_popup_tabs ul li").on("click", function () {
        handlePopupTabClick($(this));
    });

    // Close popup handling
    $(".glossymm-close-popup").on("click", closePopup);

    // Save menu item settings on click
    $("#glossymm-save-item").on("click", saveMenuItemSettings);

    // Close builder popup
    $(".glossymm_close_builder_popup").on("click", glossymm_close_builder_popup);

    // Close popup on outside click
    $(document).on("click", function (event) {
        if (!$(event.target).closest(".glossymm_adminmenu_popup, .glossymm_megamenu_trigger, .glossymm_megamenu_builder_popup").length) {
            closePopup();
        }
    });

   

    // Append Mega Menu Edit Links
    function appendMegaMenuEditLinks() {
        $("#menu-to-edit li.menu-item.menu-item-depth-0").each(function () {
            $(this).append(`<a href='#' class='glossymm_megamenu_trigger'>Edit Mega Menu 
                            <div class='ajax-loader'><img src='${ajax_loader}' alt=''></div></a>`);
        });
    }

    // Handle Mega Menu Toggle
    function handleMegaMenuToggle(element) {
        let menuId = element.data("menuid");
        let enabled = element.is(":checked") ? 1 : 0;

        console.log(menuId);
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function () {
                element.prop("disabled", true);
                $(".ajax-loader").show();
            },
            data: {
                action: "glossymm_save_the_menuid",
                security: security_nonce,
                enabled: enabled,
                menuId: menuId
            },
            success: (res)=>{
                if(!res.success){
                    alert(res.data.msg);
                    element.prop("checked", false);
                    removeMegaMenuEditLinks();
                }   
            },
            complete: function () {
                element.prop("disabled", false);
                $(".ajax-loader").hide();
            },
            error: function (xhr) {
                $('.button-row-container').text(`Error: ${xhr.statusText}`);
            }
        });
    }

    // Toggle Mega Menu Class
    function toggleMegaMenuClass(element) {
        $("body").toggleClass("is_mega_enabled", element.is(":checked"))
                 .toggleClass("is_mega_disabled", !element.is(":checked"));
    }

    // Remove Mega Menu Edit Links
    function removeMegaMenuEditLinks() {
        $("#menu-to-edit li.menu-item.menu-item-depth-0").children('a.glossymm_megamenu_trigger').remove();
    }

    // Handle Mega Menu Edit Click
    function handleMegaMenuEditClick(e, parentThis) {
        e.preventDefault();
        let menuId = parseInt($(parentThis).parents("li.menu-item.menu-item-depth-0").attr("id").match(/\d+/)[0], 10);

        $("#glossymm-item-form").data("item", menuId);

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function () {
                $(parentThis).children(".ajax-loader").show();
                $(".ajax_preloader").show();
            },
            data: {
                action: "glossymm_get_item_settings",
                security: security_nonce,
                item_id: menuId
            },
            success: function (res) {
                $("#glossymm-tab-content").html(res.item_settings_withhtml);
                $(".glossymm_popup_overlaping, .glossymm_adminmenu_popup").show();
                $("#glossymm-mmwidth").on("change", function () {
                    toggleCustomWidth($(this));
                });

                // Handle builder open
                $("#glossymm-builder-open").on("click", glossymm_builder_open);
            },
            complete: function () {
                $(parentThis).children(".ajax-loader").hide();
                $(".ajax_preloader").hide();
            },
            error: function (xhr) {
                $('.glossymm_popup_overlaping').text(`Error: ${xhr.statusText}`);
            }
        });
    }

    // Handle Popup Tab Click
    function handlePopupTabClick(element) {
        $(".glossymm_popup_tabs ul li").removeClass("active");
        element.addClass("active");
        $(".glossymm-tabpanel").hide();
        $(`#${element.data("tab")}`).show();
    }

    // Close Popup
    function closePopup() {
        $(".glossymm_popup_tabs li").removeClass("active");
        $("[data-tab='glossymm-pupup-content']").addClass("active");
        if($(".glossymm_megamenu_builder_popup").css('display') == "block"){
            $(".glossymm_megamenu_builder_popup").hide();           
        }else{
            $(".glossymm_adminmenu_popup, .glossymm_popup_overlaping").hide();
        }
        
    }

    // Toggle Custom Width
    function toggleCustomWidth(element) {
        $(".mmcustom_width").toggle(element.val() === "custom_width");
    }

    // Save Menu Item Settings
    function saveMenuItemSettings(e) {
        e.preventDefault();

        let itemId = $("#glossymm-item-form").data("item");
        let formData = {
            item_is_enabled: $("#glossymm_megamenu_item_enabled").is(":checked") ? 1 : 0,
            glossymm_custom_width: $('input[name="glossymm_custom_width"]').val(),
            glossymm_mmwidth: $('select[name="glossymm-mmwidth"]').val(),
            glossymm_mmposition: $('select[name="glossymm-mmposition"]').val(),
            glossymm_fontawesome_class: $('input[name="yhs-fontawesome-class"]').val()
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            beforeSend: function () {
                $(".ajax_preloader").show();
                $(e.target).text('Saving..');
            },
            data: {
                action: "glossymm_saving_item_settings",
                security: security_nonce,
                item_id: itemId,
                formData: formData
            },
            complete: function () {
                $('#glossymm-save-item').text('Saved');
                setTimeout(() => $('#glossymm-save-item').text('Save'), 600);
                $(".ajax_preloader").hide();
            },
            error: function (xhr) {
                $('.glossymm_popup_overlaping').text(`Error: ${xhr.statusText}`);
            }
        });
    }

    // Open builder
    function glossymm_builder_open(e) {
        e.preventDefault();
        let menuitemId = $("#glossymm-builder-open").data("menuitem");
        let elmEditUrl = `${resturl}megamenu/content_editor/menuitem${menuitemId}`;

        $("#glossymm_megamenu_builder_iframe").attr("src", elmEditUrl);
        $(".glossymm_megamenu_builder_popup").show();
    }

    // Close builder popup
    function glossymm_close_builder_popup(e) {
        e.preventDefault();
        saveMenuItemSettings(e);        
        $("#glossymm_megamenu_builder_iframe").attr("src", '');
        $(".glossymm_megamenu_builder_popup").hide();
    }
});
