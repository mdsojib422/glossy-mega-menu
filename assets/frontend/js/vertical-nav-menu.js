(function ($) {

    
    let gmvmwrapper = $(".glossymm-vertical-menu-wrapper");
    let gmvertical_menu = $("#glossymm-megamenu-vertical");

     if(window.innerWidth > 911){
        let body_margin = parseInt($(".glossymm-vertical-header").css('width'));
        $('body').css("margin-left",body_margin+'px');
        gmvmwrapper.css("left",body_margin+'px'); 
        console.log(body_margin);
     }else{

        let body_heigth = parseInt($(".glossymm-vertical-header").css('height'));
        $('body').css("margin-top",body_heigth +'px');
        console.log(body_heigth);
        gmvmwrapper.css("top",body_heigth+'px'); 

     }
    
    
    

  
   

    $("#glossymm_vertical_menu_open").on("click",function(e){
        e.preventDefault();  
        gmvertical_menu.toggleClass("show-header");        
        $("body").toggleClass("disable_scroll_formenu");     
    });
    

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/glossymm_vertical_nav_menu.default', ($scope, $)=> {





        });
    });



})(jQuery);