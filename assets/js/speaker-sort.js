
jQuery(document).ready(function() {
   jQuery('#inline_content input[name="speakerRadio"]').click(function(){
        if($(this).attr("value")=="yes"){
            $(".speakerlist").show();
            $("#submitb").show();
            $("#updateb").show();
            $(".submitspeak").hide();
            $(".speakmanual").hide();
        }
        if($(this).attr("value")=="no"){
            $(".speakerlist").hide();
            $(".submitspeak").show();
            $("#submitb").hide();
            $("#updateb").hide();
            $(".speakmanual").show();
        }
    });
});
