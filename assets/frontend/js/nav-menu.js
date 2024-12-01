(function ($) {
    var WidgetNavMenu = function ($scope, $) {
        let glossymmMegamenuHas = $scope.find(".glossymm-megamenu-has");
        if(glossymmMegamenuHas.length){
            glossymmMegamenuHas.each(function(){              
                var spaceToRight = window.innerWidth - ($(this).offset().left + $(this).outerWidth(true));   
                let currentWidth = parseInt( $(this).children(".glossymm-megamenu-panel").css('width'));
                if(currentWidth > spaceToRight){
                    $(this).children(".glossymm-megamenu-panel").css('left',-(currentWidth-spaceToRight));
                }       
            });            
        }

        let glossymmMegamenuPanel = $scope.find(".glossymm-megamenu-panel.full_width");  
        if (glossymmMegamenuPanel.length) {
            $(window).on('resize', () => fixWidth(glossymmMegamenuPanel));
            fixWidth(glossymmMegamenuPanel);
        }
        function fixWidth(items) {
            items.each(function () {
                let panelLeftSpace = $(this).parent().offset().left;
                this.style.left = -panelLeftSpace + 'px';
            });
        }

        let breakPoint = $scope.find(".glossymm-menu-wrapper").data("responsive-breakpoint");
        let container = $scope.find(".glossymm-menu-container");

        const toggleOffcanvasClass = () => {
            if (window.innerWidth < breakPoint) {
                container.addClass("glossymm-megamenu-offcanvas");
            } else {
                container.removeClass("glossymm-megamenu-offcanvas");
            }
        };

        toggleOffcanvasClass();
        $(window).on('resize', toggleOffcanvasClass);

        let clickableElements = $scope.find(".glossymm-nav-dropdown-click .glossymm-megamenu-has, .glossymm-nav-dropdown-click .glossymm-dropdown-has");

        clickableElements.on("dblclick", function (e) {
            e.preventDefault();
            let link = $(this).children('a').attr("href");
            if (link) window.location.href = link;
        });

        clickableElements.on("click", function (e) {
            e.preventDefault();
            let clickedElement = $(this);
            let megamenuPanel = clickedElement.find(".glossymm-megamenu-panel");
            let dropdownPanel = clickedElement.find(".glossymm-dropdown");
            let isCurrentlyShown = megamenuPanel.hasClass("showmenu") || dropdownPanel.hasClass("showmenu");

            $(".glossymm-megamenu-panel.showmenu, .glossymm-dropdown.showmenu").removeClass("showmenu");

            if (!isCurrentlyShown) {
                megamenuPanel.add(dropdownPanel).addClass("showmenu");
            }
        });

        $scope.find(".glossymm-menu-hamburger").on("click", function () {
            let offcanvasMenu = $scope.find(".glossymm-megamenu-offcanvas");
            offcanvasMenu.toggleClass("show");
            $('body').prepend("<div class='glossymm-overlay'></div>").css("overflow", "hidden");

            if (!offcanvasMenu.hasClass("show")) closeOffcanvas($scope);

            $(document).on("click", function (e) {
                if (!$(e.target).closest(".glossymm-megamenu-offcanvas, .glossymm-menu-hamburger").length) {
                    closeOffcanvas($scope);
                }
            });
        });

        $scope.find(".glossymm-menu-close").on("click", function () {
            closeOffcanvas($scope);
        });

        $scope.find(".glossymm-megamenu-panel, .glossymm-dropdown").on("click", function (e) {
            e.stopPropagation();
        });

        $(document).on("click", function (e) {
            if (!$(e.target).closest(".glossymm-nav-dropdown-click, .glossymm-megamenu-panel, .glossymm-dropdown").length) {
                $(".glossymm-megamenu-panel.showmenu, .glossymm-dropdown.showmenu").removeClass("showmenu");
            }
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/glossymm_nav_menu.default', WidgetNavMenu);
    });

    function closeOffcanvas($scope) {
        $(".glossymm-overlay").remove();
        $('body').css('overflow', 'unset');
        $scope.find(".glossymm-megamenu-offcanvas").removeClass("show");
    }

})(jQuery);