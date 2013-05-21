<?php
error_reporting(0);
$buy_subtotal = "0";
$buy_amounttotal = "0";
$Query = mysql_query("SELECT amount, rate FROM buy_orderbook WHERE want='BTC' and processed='1' ORDER BY rate ASC");
while($Row = mysql_fetch_assoc($Query)) {
   $buy_amount = $Row['amount'];
   $buy_rate = $Row['rate'];
   $buy_subtotal += ($buy_amount * $buy_rate);
}
$btevalue = satoshitrim(satoshitize($buy_subtotal));

$buy_amounttotal = "0";
$Query = mysql_query("SELECT amount FROM sell_orderbook WHERE want='BTC' and processed='1' ORDER BY rate ASC");
while($Row = mysql_fetch_assoc($Query)) {
   $buy_amount = $Row['amount'];
   $buy_amounttotal += $buy_amount;
}
$btevolume = satoshitrim(satoshitize($buy_amounttotal));

$buy_subtotal = "0";
$buy_amounttotal = "0";
$Query = mysql_query("SELECT amount, rate FROM buy_orderbook WHERE want='LTC' and processed='1' ORDER BY rate ASC");
while($Row = mysql_fetch_assoc($Query)) {
   $buy_amount = $Row['amount'];
   $buy_rate = $Row['rate'];
   $buy_subtotal += ($buy_amount * $buy_rate);
}
$cncvalue = satoshitrim(satoshitize($buy_subtotal));

$buy_amounttotal = "0";
$Query = mysql_query("SELECT amount FROM sell_orderbook WHERE want='LTC' and processed='1' ORDER BY rate ASC");
while($Row = mysql_fetch_assoc($Query)) {
   $buy_amount = $Row['amount'];
   $buy_amounttotal += $buy_amount;
}
$cncvolume = satoshitrim(satoshitize($buy_amounttotal));

echo '<table>
         <tr>
            <td nowrap>BTE/BTC</td>
            <td style="padding-left: 10px;" nowrap>Volume '.$btevolume.' / '.$cncvalue.' BTE</td>
            <td style="padding-left: 30px;" nowrap>BTE/LTC</td>
            <td style="padding-left: 10px;" nowrap>Volume '.$cncvolume.' / '.$btevalue.' BTE</td>
         </tr>
      </table>';
?>