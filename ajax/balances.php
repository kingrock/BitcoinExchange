<?php
error_reporting(0);
if(!$user_session) {
   echo '<b>Finances:</b><p></p>
         <table style="width: 100%;">
            <tr>
               <td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtc.php">BTC</a></td>
               <td align="right" style="padding-left: 5px;" nowrap><span id="balance-btc">?</span></td>
            </tr><tr>
               <td align="right" style="padding-left: 5px;" nowrap><a href="fundsbte.php">BTE</a></td>
               <td align="right" style="padding-left: 5px;" nowrap><span id="balance-bte">?</span></td>
            </tr><tr>
               <td align="right" style="padding-left: 5px;" nowrap><a href="fundsltc.php">LTC</a></td>
               <td align="right" style="padding-left: 5px;" nowrap><span id="balance-ltc">?</span></td>
            </tr>
         </table>';
} else {
   echo '<b>Finances:</b><p></p>
         <table style="width: 100%;">
            <tr>
               <td align="right" style="padding-left: 5px;" nowrap><a href="fundsbtc.php">BTC</a></td>
               <td align="right" style="padding-left: 5px;" nowrap><span id="balance-btc">'.userbalance($user_session,"BTC").'</span></td>
            </tr><tr>
               <td align="right" style="padding-left: 5px;" nowrap><a href="fundsbte.php">BTE</a></td>
               <td align="right" style="padding-left: 5px;" nowrap><span id="balance-bte">'.userbalance($user_session,"BTE").'</span></td>
            </tr><tr>
               <td align="right" style="padding-left: 5px;" nowrap><a href="fundsltc.php">LTC</a></td>
               <td align="right" style="padding-left: 5px;" nowrap><span id="balance-ltc">'.userbalance($user_session,"LTC").'</span></td>
            </tr>
         </table>';
}
?>