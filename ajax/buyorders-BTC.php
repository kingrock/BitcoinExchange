<?php
error_reporting(0);
echo '<div align="center" class="buy-sells">';
$sql = "SELECT * FROM buy_orderbook WHERE want='BTC' and processed='1'";
$result = mysql_query($sql);
$count = mysql_num_rows($result);
if($count!=0) {
   $Query = mysql_query("SELECT amount, rate FROM buy_orderbook WHERE want='BTC' and processed='1' ORDER BY rate DESC");
   while($Row = mysql_fetch_assoc($Query)) {
      $Orders_Sells_Amount = $Row['amount'];
      $Orders_Sells_Rate = $Row['rate'];
      $Orders_Sells_Total = $Orders_Sells_Amount * $Orders_Sells_Rate;
      $Orders_Sells_Total = satoshitrim(satoshitize($Orders_Sells_Total));
      $Buys_td .= '<tr>
                      <td align="right" style="padding: 1px;" nowrap><a href="#" onclick="setsellamounts('.$Orders_Sells_Amount.');">'.$Orders_Sells_Amount.'</a></td>
                      <td align="right" style="padding: 1px; padding-left: 10px;" nowrap><a href="#" onclick="setsellrates('.$Orders_Sells_Rate.');">'.$Orders_Sells_Rate.'</a></td>
                      <td align="right" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Sells_Total.'</td>
                   </tr>';
   }
      $buy_subtotal = "0";
      $Query = mysql_query("SELECT amount,rate FROM buy_orderbook WHERE want='BTC' and processed='1' ORDER BY rate ASC");
      while($Row = mysql_fetch_assoc($Query)) {
         $buy_amount = $Row['amount'];
         $buy_rate = $Row['rate'];
         $buy_subtotal += ($buy_amount * $buy_rate);
      }
      $cncvalue = satoshitrim(satoshitize($buy_subtotal));
   echo '<table>
         <tr>
            <td colspan="3" align="left" style="font-weight: bold; padding: 2px;" nowrap>Buy Orders</td>
         </tr><tr>
                  <td colspan="3" align="right" style="padding: 2px;" nowrap>Total BTC: '.$cncvalue.'</td>
               </tr><tr>
            <td align="left" style="font-weight: bold; padding: 1px;" nowrap>Amount (BTE)</td>
            <td align="left" style="font-weight: bold; padding: 1px; padding-left: 10px;" nowrap>Rate (BTC)</td>
            <td align="left" style="font-weight: bold; padding: 1px; padding-left: 10px;" nowrap>Total (BTC)</td>
         </tr>'.$Buys_td.'</table>';
} else {
   echo '<table style="width: 260px;">
            <tr>
               <td align="left" style="font-weight: bold; padding: 2px;" nowrap>Buy Orders</td>
            </tr><tr>
               <td align="left" style="padding: 2px;" nowrap>Currently no buy offers</td>
            </tr>
         </table>';
}
echo '</div>';
?>