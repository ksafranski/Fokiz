$(function(){

    cur_feed_page = 1;
    feed_items_per_page = $('#feed_listing_per_page').val();
    feed_items_total = $('#feed_listing_total').val();
    loadListings();

    // Handle "Newer" link
    $('#feed_listing_newer').click(function(){
        cur_feed_page--;
        loadListings();
    });
    
    // Handle "Older" link
    $('#feed_listing_older').click(function(){
        cur_feed_page++;
        loadListings();
    });

});

function loadListings(){
    if(cur_feed_page==1){ var start_feed_item = 1; }
    else{ var start_feed_item = ((cur_feed_page-1) * feed_items_per_page)+1; }
    var end_feed_item = start_feed_item + (feed_items_per_page-1);
    // Show current page listings
    $('.feed_listing_item').fadeOut(100).addClass('feed_listing_item_hidden'); // Fade all out
    $('.feed_listing_item').each(function(){
        var count = $(this).attr('data-feed-count');
        if(count>=start_feed_item && count<=end_feed_item){
            $(this).fadeIn(100).removeClass('feed_listing_item_hidden');
        }
    });
    // Show "Newer" link?
    if(cur_feed_page==1){ $('#feed_listing_newer').fadeOut(100); }else{ $('#feed_listing_newer').fadeIn(100); }
    
    // Show "Older" link?
    if(feed_items_total>end_feed_item){ $('#feed_listing_older').fadeIn(100); }else{ $('#feed_listing_older').fadeOut(100); }
    
    
}
