<?php
session_start();
error_reporting(0);
require"config.php";
$stringIp = $_SERVER['REMOTE_ADDR'];
$inDB = mysql_query("SELECT * FROM who_is_online WHERE ip='$stringIp'");
if(!mysql_num_rows($inDB)) {
   if($_COOKIE['geoData']) {
      list($city,$countryName,$countryAbbrev) = explode('|',mysql_real_escape_string(strip_tags($_COOKIE['geoData'])));
   } else {
      $xml = file_get_contents('http://api.hack4.us/?ip='.$stringIp);     // get the location of the user by ip
      $city = get_tag('gml:name',$xml);
      $city = $city[1];
      $countryName = get_tag('countryName',$xml);
      $countryName = $countryName[0];
      $countryAbbrev = get_tag('countryAbbrev',$xml);
      $countryAbbrev = $countryAbbrev[0];
      setcookie('geoData',$city.'|'.$countryName.'|'.$countryAbbrev, time()+60*60*24*30,'/');
   }
   $countryName = str_replace('(Unknown Country?)','UNKNOWN',$countryName);
   if(!$countryName) {
      $countryName='UNKNOWN';
      $countryAbbrev='XX';
      $city='(Unknown City?)';
   }
   mysql_query("INSERT INTO who_is_online (id,ip,username,botname,user,bot,guest,city,country,countrycode)
                VALUES('','$stringIp','$toolbar_username','$botname','$isuser','$isbot','$isguest','$city','$countryName','$countryAbbrev')");
} else {
   if($isuser=="1") {
      mysql_query("UPDATE who_is_online SET username='$toolbar_username' WHERE ip='$stringIp'");
      mysql_query("UPDATE who_is_online SET user='1' WHERE ip='$stringIp'");
      mysql_query("UPDATE who_is_online SET guest='2' WHERE ip='$stringIp'");
      mysql_query("UPDATE who_is_online SET bot='2' WHERE ip='$stringIp'");
   }
   if($isbot=="1") {
      mysql_query("UPDATE who_is_online SET botname='$botname' WHERE ip='$stringIp'");
      mysql_query("UPDATE who_is_online SET user='2' WHERE ip='$stringIp'");
      mysql_query("UPDATE who_is_online SET guest='2' WHERE ip='$stringIp'");
      mysql_query("UPDATE who_is_online SET bot='1' WHERE ip='$stringIp'");
   }
   if($isguest=="1") {
      mysql_query("UPDATE who_is_online SET user='2' WHERE ip='$stringIp'");
      mysql_query("UPDATE who_is_online SET guest='1' WHERE ip='$stringIp'");
      mysql_query("UPDATE who_is_online SET bot='2' WHERE ip='$stringIp'");
   }
   mysql_query("UPDATE who_is_online SET dt=NOW() WHERE ip='$stringIp'");
}
// mysql_query("DELETE FROM tz_who_is_online WHERE dt<SUBTIME(NOW(),'0 0:10:0')");
mysql_query("UPDATE who_is_online SET user='2' WHERE dt<SUBTIME(NOW(),'0 0:10:0')");
mysql_query("UPDATE who_is_online SET guest='2' WHERE dt<SUBTIME(NOW(),'0 0:10:0')");
mysql_query("UPDATE who_is_online SET bot='2' WHERE dt<SUBTIME(NOW(),'0 0:10:0')");
list($UsersOnline) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM who_is_online WHERE user='1'"));
list($GuestsOnline) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM who_is_online WHERE guest='1'"));
list($BotsOnline) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM who_is_online WHERE bot='1'"));
echo '<table style="width: 100%;">
         <tr>
            <td align="center">Users: '.$UsersOnline.'</td>
            <td align="center">Guest: '.$GuestsOnline.'</td>
            <td align="center">Bots: '.$BotsOnline.'</td>
         </tr>
      </table>';
?>