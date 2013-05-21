<?php
error_reporting(0);
require'database.php';

// this file is to update online status and log if guest user or bot also log location
// get_tag and is_bot functions are from tzine rest of the script by zelles

function get_tag($tag,$xml) {
	preg_match_all('/<'.$tag.'>(.*)<\/'.$tag.'>$/imU',$xml,$match);
	return $match[1];
}
function is_bot() {
   $botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
                    "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
                    "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
                    "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
                    "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
                    "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
                    "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
                    "Butterfly","Twitturls","Me.dium","Twiceler");
   foreach($botlist as $bot) {
      if(strpos($_SERVER['HTTP_USER_AGENT'],$bot)!==false)
      return strpos($_SERVER['HTTP_USER_AGENT'],$bot);
   }
   return false;
}

$link = @mysql_connect($dbdb_host,$dbdb_user,$dbdb_pass) or die('Server error');

mysql_set_charset('utf8');
mysql_select_db($dbdb_database,$link);

$isuser = "2";
$isguest = "2";
$isbot = "2";

$user_session = $_SESSION['user_session'];
if(is_bot()) {
   $botname = is_bot();
   $isbot = "1";
} else {
   if(!$user_session) {
      $isguest = "1";
      $toolbar_username = "";
   } else {
      $isuser = "1";
      $toolbar_username = $user_session;
   }
}
?>