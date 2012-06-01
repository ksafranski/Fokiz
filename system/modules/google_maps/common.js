$(function(){
    $('.google_map>iframe').each(function(){
        // Get Width
        var w = $(this).outerWidth();
        $(this).css({'height':w+'px'});
    });
});