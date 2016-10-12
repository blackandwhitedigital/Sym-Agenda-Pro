(function($){
    var $isotop = $('.tlp-team-layout2').imagesLoaded( function() {
      $isotop.layout2({
            getSortData: {
                name: '.name',
                designation: '.designation',
            },
            sortAscending : true,
            itemSelector: '.team-member',
        });
    });

    $('.sort-by-button-group').on( 'click', 'button', function() {
        var sortByValue = $(this).attr('data-sort-by');
        $isotop.layout2({ sortBy: sortByValue });
        $(this).parent().find('.selected').removeClass('selected');
        $(this).addClass('selected');
    });

    $(window).resize(  HeightResize );
    $(window).load( HeightResize );

})(jQuery);

function HeightResize(){
    if(jQuery(window).width() > 768) {
        jQuery(".tlp-team").each(function () {
            var tlpMaxH = 0;
            jQuery(this).children('div').children(".tlp-equal-height").height("auto");
            jQuery(this).children('div').children(".tlp-equal-height").each(function () {
                var $thisH = jQuery(this).outerHeight();
                if ($thisH > tlpMaxH) {
                    tlpMaxH = $thisH;
                }
            });
            jQuery(this).children('div').children(".tlp-equal-height").height(tlpMaxH + "px");
        });


        var tlpMaxH = 0;
        jQuery(".tlp-team-layout2 > div > .tlp-equal-height").height("auto");
        jQuery(".tlp-team-layout2 > div > .tlp-equal-height").each(function () {
            var $thisH = jQuery(this).outerHeight();
            if ($thisH > tlpMaxH) {
                tlpMaxH = $thisH;
            }
        });
        jQuery(".tlp-team-layout2 > div > .tlp-equal-height").height(tlpMaxH + "px");
    }else{
        jQuery(".tlp-team").children('div').children(".tlp-equal-height").height("auto");
        jQuery(".tlp-team-layout2 > div > .tlp-equal-height").height("auto");
    }
}