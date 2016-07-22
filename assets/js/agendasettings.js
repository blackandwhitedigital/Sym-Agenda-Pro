(function($){
    $('.tlp-color').wpColorPicker();
})(jQuery);

function agendaSettings(e){

    jQuery('#response').hide();
    arg=jQuery( e ).serialize();
    bindElement = jQuery('#SaveButton');
    AgendaAjaxCall( bindElement, 'agendaSettings', arg, function(data){
        console.log(data);
        if(data.error){
            jQuery('#response').removeClass('error');
            jQuery('#response').show('slow').text(data.msg);
        }else{
            jQuery('#response').addClass('error');
            jQuery('#response').show('slow').text(data.msg);
        }
    });
}

function AgendaAjaxCall( element, action, arg, handle){
    if(action) data = "action=" + action;
    if(arg)    data = arg + "&action=" + action;
    if(arg && !action) data = arg;
    data = data ;

    var n = data.search("agenda_nonce");
    if(n<0){
        data = data + "&agenda_nonce=" + agenda_nonce;
    }
    console.log(data);
    jQuery.ajax({
        type: "post",
        url: ajaxurl,
        data: data,
        beforeSend: function() { jQuery("<span class='tlp_loading'></span>").insertAfter(element); },
        success: function( data ){
            jQuery(".tlp_loading").remove();
            handle(data);
        }
    });
}
