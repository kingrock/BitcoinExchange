<?php
error_reporting(0);
if(!$user_session) {
   $do = "nothing";
} else {
   $TXSSS_DISP = "";
   $bold_txxs = "";
   $Bitcoind_List_Transactions = $Bitcoind->listtransactions($wallet_id,10);
   foreach($Bitcoind_List_Transactions as $Bitcoind_List_Transaction) {
      if($bold_txxs=="") { $bold_txxs = "color: #666666; "; } else { $bold_txxs = ""; }
      if($Bitcoind_List_Transaction['category']=="receive") {
         if(5>=$Bitcoind_List_Transaction['confirmations']) {
            $TXSSS_DISP = "1";
            $TXSSS .= '<tr>
                          <td align="right" style="'.$bold_txxs.'padding-left: 5px;" nowrap>'.abs($Bitcoind_List_Transaction['amount']).' BTC / '.$Bitcoind_List_Transaction['confirmations'].' confs</span></td>
                       </tr>';
         }
      }
   }
   $Bytecoind_List_Transactions = $Bytecoind->listtransactions($wallet_id,10);
   foreach($Bytecoind_List_Transactions as $Bytecoind_List_Transaction) {
      if($bold_txxs=="") { $bold_txxs = "color: #666666; "; } else { $bold_txxs = ""; }
      if($Bytecoind_List_Transaction['category']=="receive") {
         if(5>=$Bytecoind_List_Transaction['confirmations']) {
            $TXSSS_DISP = "1";
            $TXSSS .= '<tr>
                          <td align="right" style="'.$bold_txxs.'padding-left: 5px;" nowrap>'.abs($Bytecoind_List_Transaction['amount']).'</span> BTE / '.$Bytecoind_List_Transaction['confirmations'].' confs</td>
                       </tr>';
         }
      }
   }
   $Chncoind_List_Transactions = $Chncoind->listtransactions($wallet_id,10);
   foreach($Chncoind_List_Transactions as $Chncoind_List_Transaction) {
      if($bold_txxs=="") { $bold_txxs = "color: #666666; "; } else { $bold_txxs = ""; }
      if($Chncoind_List_Transaction['category']=="receive") {
         if(5>=$Chncoind_List_Transaction['confirmations']) {
            $TXSSS_DISP = "1";
            $TXSSS .= '<tr>
                          <td align="right" style="'.$bold_txxs.'padding-left: 5px;" nowrap>'.abs($Chncoind_List_Transaction['amount']).' LTC / '.$Chncoind_List_Transaction['confirmations'].' confs</td>
                       </tr>';
         }
      }
   }
   if($TXSSS_DISP=="1") {
      echo '<div align="left" class="pending-right">
            <b>Incoming:</b>
            <table style="width: 100%;">'.$TXSSS.'</table>
            </center>
            </div>
            <p></p>';
   }
}
?>