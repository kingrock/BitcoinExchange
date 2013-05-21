<?php
error_reporting(0);
$sql = "SELECT * FROM ordersfilled WHERE want='LTC' and processed='1' ORDER BY rate DESC";
$result = mysql_query($sql);
$count = mysql_num_rows($result);
if($count!=0) {
   echo '<table>
            <tr>
               <td colspan="5" align="left" style="font-weight: bold; padding: 2px; padding-left: 5px;" nowrap>Past Orders</td>
            </tr><tr>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Date</td>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Action</td>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Amount (LTC)</td>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Rate (BTE)</td>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 10px;" nowrap>Total (BTE)</td>
            </tr>';
   $Query = mysql_query("SELECT date, action, amount, rate, total FROM ordersfilled WHERE want='LTC' and processed='1' ORDER BY id DESC");
   while($Row = mysql_fetch_assoc($Query)) {
      $Orders_Sells_DATE = $Row['date'];
      $Orders_Sells_Action = $Row['action'];
      $Orders_Sells_Amount = $Row['amount'];
      $Orders_Sells_Rate = $Row['rate'];
      $Orders_Sells_Total = $Row['total'];
      if($Orders_Sells_Action=="buy") { $Orders_Sells_Action = '<div align="center" class="sellbuttonmini">Buy</div>'; }
      if($Orders_Sells_Action=="sell") { $Orders_Sells_Action = '<div align="center" class="sellbuttonmini">Sell</div>'; }
      echo '<tr>
               <td align="left" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Sells_DATE.'</td>
               <td align="left" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Sells_Action.'</td>
               <td align="right" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Sells_Amount.'</td>
               <td align="right" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Sells_Rate.'</td>
               <td align="right" style="padding: 1px; padding-left: 10px;" nowrap>'.$Orders_Sells_Total.'</td>
            </tr>';
   }
   echo '</table>';
} else {
   echo '<table>
            <tr>
               <td align="left" style="font-weight: bold; padding: 2px; padding-left: 5px;" nowrap>Past Orders</td>
            </tr><tr>
               <td align="left" style="padding: 2px; padding-left: 15px;" nowrap>There has been no trades completed.</td>
            </tr>
         </table>';
}
?>