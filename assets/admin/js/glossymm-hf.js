;(function($){
    /* Template Select */
    $("#glossymm-template-select select").on("change",function(){
        if ($(this).val() !== "") {
            $('#glossymm-target-location-select').show();      
            $('#glossymm-target-user-select').show();         
        }else{
            $('#glossymm-target-location-select').hide();
            $('#glossymm-target-user-select').hide();
        } 
    });
    /* Enable Button Ajax Action */
    $(".glossymm-toggle-input").on("change", function () {       
        let enabled = $(this).is(":checked") ? 1 : 0;
        let tempId = $(this).data("tempid");
        let security_nonce = $("#_security_nonce").val();        
        $elm = $(this);
        $.ajax({
            url: obj.ajax_url,
            type: 'POST',
            beforeSend: function () {
                $(this).attr("disabled", "disabled");
                $(".ajax-loader").show();
            },
            data: {
                action: "glossymm_enabled_template",
                security: security_nonce,
                enabled: enabled,
                template_id: tempId
            },
            success: (res) => {            
                if(res.success === false){
                    $elm.prop('checked', false);
                    alert(res.data.msg);
                }                
            },
            complete: function () {                
                $(this).removeAttr("disabled");
                $(".ajax-loader").hide();
            },
            error: function (xhr) {
                alert('Error: ' + xhr.statusText);
            }
        });
        
    });

})(jQuery)