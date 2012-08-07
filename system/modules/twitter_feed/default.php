<?php

require_once('config.php');

// Pass in parameter
$param = "";
if(!empty($_GET['param'])){ $param = $_GET['param']; }

$feed = 'http://twitter.com/statuses/user_timeline.rss?screen_name='.$param.'&count='.$limit;
$tweets = "";
$tweets = @file_get_contents($feed);
$tweet = explode("<item>", $tweets);
$tcount = count($tweet) - 1;

for ($i = 1; $i <= $tcount; $i++) {
    $endtweet = explode("</item>", $tweet[$i]);
    $title = explode("<title>", $endtweet[0]);
    $tcontent = explode("</title>", $title[1]);
    $tcontent[0] = str_replace("–", "—", $tcontent[0]);
    $tcontent[0] = str_replace("—", "—", $tcontent[0]);
    $tcontent[0] = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank">$1$2$4</a>', $tcontent[0]);
    $tcontent[0] = str_ireplace("$param: ", "", $tcontent[0]);
    $tcontent[0] = preg_replace('/@([a-zA-Z0-9_]+)/', '<a target="_blank" href="http://www.twitter.com/$1">@$1</a>', $tcontent[0]);
    $mytweets[] = $tcontent[0];
}

echo("<div class=\"twitter_feed\">");
echo("<ul>");
if(count($mytweets)>0){
    while (list(, $v) = each($mytweets)) {
        echo("<li>$v</li>\n");
    }
}else{
    echo("<li>No Tweets in the last 7 days...</li>");
}
echo("<ul>");
?>
    <div class="follow_link">
        <a target="_blank" href="http://www.twitter.com/<?php echo($param); ?>" title="Follow <?php echo(ucfirst($param)); ?> on Twitter">Follow <?php echo(ucfirst($param)); ?> On Twitter &raquo;</a>
    </div>
</div>

