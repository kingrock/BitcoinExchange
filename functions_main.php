<?php
function security($value) {
   if(is_array($value)) {
      $value = array_map('security', $value);
   } else {
      if(!get_magic_quotes_gpc()) {
         $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
      } else {
         $value = htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8');
      }
      $value = str_replace("\\", "\\\\", $value);
   }
   return $value;
}

function apikeygen() {
   $keygen_characters = "0011223344--__5566778899aabbccddeeffgghhiijjkkllmmnnooppqqrrssttuuvvwwxxyyzzAABBCCDDEEF--__FGGHHIIJJKKLLMMNNOOPPQQRRSSTUUVVWWXXYYZZ";
   $keygen_key = "";
   $keygen_length = rand(40, 60);
   for($keygen_i = 0; $keygen_i < $keygen_length; $keygen_i++) {
      $keygen_key .= $keygen_characters[rand(0, strlen($keygen_characters) - 1)];
   }
   return $keygen_key;
}

function satoshitize($satoshitize) {
   return sprintf("%.8f", $satoshitize);
}

function satoshitrim($satoshitrim) {
   return rtrim(rtrim($satoshitrim, "0"), ".");
}

function userbalance($function_user,$function_coin) {
   if($function_coin=="BTC") {
      $function_query = mysql_query("SELECT coin1 FROM balances WHERE username='$function_user'");
      while($function_row = mysql_fetch_assoc($function_query)) { $function_return = $function_row['coin1']; }
   }
   if($function_coin=="BTE") {
      $function_query = mysql_query("SELECT coin2 FROM balances WHERE username='$function_user'");
      while($function_row = mysql_fetch_assoc($function_query)) { $function_return = $function_row['coin2']; }
   }
   if($function_coin=="LTC") {
      $function_query = mysql_query("SELECT coin3 FROM balances WHERE username='$function_user'");
      while($function_row = mysql_fetch_assoc($function_query)) { $function_return = $function_row['coin3']; }
   }
   return $function_return;
}

function buyrate($function_coin) {
   $function_query = mysql_query("SELECT rate FROM buy_orderbook WHERE want='$function_coin' and processed='1' ORDER BY rate DESC LIMIT 1");
   while($function_row = mysql_fetch_assoc($function_query)) {
      $function_return = $function_row['rate'];
   }
   return $function_return;
}

function sellrate($function_coin) {
   $function_query = mysql_query("SELECT rate FROM sell_orderbook WHERE want='$function_coin' and processed='1' ORDER BY rate ASC LIMIT 1");
   while($function_row = mysql_fetch_assoc($function_query)) {
      $function_return = $function_row['rate'];
   }
   return $function_return;
}

function plusfunds($function_user,$function_coin,$function_amount) {
   $function_user_balance = userbalance($function_user,$function_coin);
   $function_balance = $function_user_balance + $function_amount;
   $function_balance = satoshitrim(satoshitize($function_balance));
   if($function_coin=="BTC") { $sql = "UPDATE balances SET coin1='$function_balance' WHERE username='$function_user'"; }
   if($function_coin=="BTE") { $sql = "UPDATE balances SET coin2='$function_balance' WHERE username='$function_user'"; }
   if($function_coin=="LTC") { $sql = "UPDATE balances SET coin3='$function_balance' WHERE username='$function_user'"; }
   $result = mysql_query($sql);
   if($result) {
      $function_return = "success";
   } else {
      $function_return = "error";
   }
   return $function_return;
}

function minusfunds($function_user,$function_coin,$function_amount) {
   $function_user_balance = userbalance($function_user,$function_coin);
   $function_balance = $function_user_balance - $function_amount;
   $function_balance = satoshitrim(satoshitize($function_balance));
   if($function_coin=="BTC") { $sql = "UPDATE balances SET coin1='$function_balance' WHERE username='$function_user'"; }
   if($function_coin=="BTE") { $sql = "UPDATE balances SET coin2='$function_balance' WHERE username='$function_user'"; }
   if($function_coin=="LTC") { $sql = "UPDATE balances SET coin3='$function_balance' WHERE username='$function_user'"; }
   $result = mysql_query($sql);
   if($result) {
      $function_return = "success";
   } else {
      $function_return = "error";
   }
   return $function_return;
}
?>