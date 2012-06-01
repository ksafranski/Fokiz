<?php

require_once('config.php');

// Pass in Num of Items parameter
$param = "";
if(!empty($_GET['param'])){ $param = $_GET['param']; }

// List out feed
$feed = new Feed();
$listing = $feed->GetList();

if($listing==0){
    echo("<p>Nothing in the feed!</p>");
}else{
    $count = 1;
    foreach($listing as $item){
        $pub_date = str_replace(" ", "&nbsp;", date('M j, Y', strtotime($item['pub_date'])));
        $page = new Page();
        $page->id = $item['pag_id'];
        $page->Load();
        echo("<article class=\"feed_listing_item feed_listing_item_hidden\" data-feed-count=\"$count\">");
        echo("<header><date>$pub_date</date><a href=\"" . FOKIZ_PATH . $page->url . "\"><h2>" . $page->title . "</h2></a></header>");
        echo("<p>" . $page->description . "</p>");
        echo("<p><a href=\"" . FOKIZ_PATH . $page->url . "\">Continue Reading &raquo;</a></p>");
        echo("</article>");
        $count++;
    }
}

?>
<input type="hidden" id="feed_listing_per_page" value="<?php echo($param); ?>" />
<input type="hidden" id="feed_listing_total" value="<?php echo($count-1); ?>" />
<a id="feed_listing_older">&laquo;&nbsp;Older</a>
<a id="feed_listing_newer">Newer&nbsp;&raquo;</a>
<div class="feed_listing_clear"></div>