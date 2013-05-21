<?php
session_start();
error_reporting(0);
require_once'jsonRPCClient.php';
require_once'auth.php';
if($Logged_In!==7) {
   header("Location: index.php");
}
$coin_selecter = security($_GET['c']);
if($coin_selecter) {
   if($coin_selecter=="LTC") { $_SESSION['trade_coin'] = "LTC"; }
   if($coin_selecter=="BTC") { $_SESSION['trade_coin'] = "BTC"; }
   header("Location: home.php");
}
$Coin_A_Balance = userbalance($user_session,$BTC);
$Coin_B_Balance = userbalance($user_session,$BTE);
$Buying_Rate = buyrate($BTE);
$Selling_Rate = sellrate($BTE);
if(!$Buying_Rate) { $Buying_Rate = '0'; }
if(!$Selling_Rate) { $Selling_Rate = '0'; }
$Bitcoin_Can_Buy = $Coin_A_Balance / $Selling_Rate;
$Bitcoin_Can_Buy = satoshitrim(satoshitize($Bitcoin_Can_Buy));

$cancel_type = security($_GET['type']);
$cancel_order = security($_GET['cancel']);
if($cancel_order) {
   if($cancel_type=="sell") {
      $Query = mysql_query("SELECT id, username, amount FROM sell_orderbook WHERE id='$cancel_order' and username='$user_session' ORDER BY rate ASC LIMIT 1");
      while($Row = mysql_fetch_assoc($Query)) {
         $CURR_Selling_ID = $Row['id'];
         $CURR_Selling_Username = $Row['username'];
         $CURR_Selling_Amount = $Row['amount'];
      }
      if($user_session==$CURR_Selling_Username) {
         $sql = "UPDATE sell_orderbook SET processed='3' WHERE id='$CURR_Selling_ID' and username='$user_session'";
         $result = mysql_query($sql);
         if($result) {
            $result = "";
            $result = plusfunds($user_session,$BTE,$CURR_Selling_Amount);
            if($result) {
               $Trade_Message = 'The order has been canceled.';
               header("Location: home.php");
            } else {
               $Trade_Message = 'System error.';
            }
         } else {
            $Trade_Message = 'System error.';
         }
      } else {
         $Trade_Message = 'Illegal Operation.';
      }
   } else {
      if($cancel_type=="buy") {
         $Query = mysql_query("SELECT id, username, amount, rate FROM buy_orderbook WHERE id='$cancel_order' and username='$user_session' ORDER BY rate ASC LIMIT 1");
         while($Row = mysql_fetch_assoc($Query)) {
            $CURR_Selling_ID = $Row['id'];
            $CURR_Selling_Username = $Row['username'];
            $CURR_Selling_Amount = $Row['amount'];
            $CURR_Selling_Rate = $Row['rate'];
         }
         if($user_session==$CURR_Selling_Username) {
            $CURR_Selling_Amount = $CURR_Selling_Amount * $CURR_Selling_Rate;
            $CURR_Selling_Amount = satoshitrim(satoshitize($CURR_Selling_Amount));
            $CURR_Selling_Amount = $Coin_A_Balance + $CURR_Selling_Amount;
            $CURR_Selling_Amount = satoshitrim(satoshitize($CURR_Selling_Amount));
            $sql = "UPDATE buy_orderbook SET processed='3' WHERE id='$CURR_Selling_ID' and username='$user_session'";
            $result = mysql_query($sql);
            if($result) {
               $result = "";
               $result = plusfunds($user_session,$BTC,$CURR_Selling_Amount);
               if($result) {
                  $Trade_Message = 'The order has been canceled.';
                  header("Location: home.php");
               } else {
                  $Trade_Message = 'System error.';
               }
            } else {
               $Trade_Message = 'System error.';
            }
         } else {
            $Trade_Message = 'Illegal Operation.';
         }
      } else {
         $Trade_Message = 'Illegal Operation.';
      }
   }
}

$PST_Order_Action = security($_POST['order-action']);
$PST_Order_Amount = security($_POST['order-amount']);
$PST_Order_Rate = security($_POST['order-rate']);
if($PST_Order_Action=="buy"){
   if($PST_Order_Amount) {
      if($PST_Order_Rate) {
         $PST_Order_Amount = satoshitrim(satoshitize($PST_Order_Amount));
         $PST_Order_Rate = satoshitrim(satoshitize($PST_Order_Rate));
         $PST_Order_Sub_Total = $PST_Order_Amount * $PST_Order_Rate;
         $PST_Order_Sub_Total = satoshitrim(satoshitize($PST_Order_Sub_Total));
         if($PST_Order_Sub_Total<=$Coin_A_Balance) {
         if($PST_Order_Sub_Total>="0.00001") {
            if($PST_Order_Sub_Total!=0) {
               if($Selling_Rate>=$PST_Order_Rate) {
                  if($Selling_Rate===$PST_Order_Rate) {
                     $Query = mysql_query("SELECT id, username, amount, rate FROM sell_orderbook WHERE want='$BTC' and processed='1' ORDER BY rate DESC LIMIT 1");
                     while($Row = mysql_fetch_assoc($Query)) {
                        $CURR_Selling_ID = $Row['id'];
                        $CURR_Selling_Username = $Row['username'];
                        $CURR_Selling_Amount = $Row['amount'];
                        $CURR_Selling_Rate = $Row['rate'];
                     }
                     $Client_BTC = userbalance($CURR_Selling_Username,$BTC);
                     $Client_BTE = userbalance($CURR_Selling_Username,$BTE);
                     if($PST_Order_Amount<=$CURR_Selling_Amount) {
            //            if($PST_Order_Amount==$CURR_Selling_Amount) {
          //                 $result = minusfunds($user_session,$BTC,$CURR_Selling_Amount);
        //                   $CURR_Selling_Amount_Fee = satoshitrim(satoshitize(($CURR_Selling_Amount / 100) / 5)));
      //                     $CURR_Selling_Amount = satoshitrim(satoshitize(($CURR_Selling_Amount - $CURR_Selling_Amount_Fee)));
    //                       $result = plusfunds($CURR_Selling_Username,$BTC,$CURR_Selling_Amount);
  //                         $result = plusfunds($user_session,$BTE,$CURR_Selling_Amount);
//                           $result = plusfunds("feebee",$BTC,$CURR_Selling_Amount_Fee);
                //        } else {
                        $Trade_Message = 'Could, Trade matching not done.';
              //          }
                     } else {
                        $Trade_Message = 'Trade matching not done.';
                     }
                  } else {
                     $Trade_Message = 'Trade matching not done..';
                  }
               } else {
                  if(!mysql_query("INSERT INTO buy_orderbook (id, date, ip, username, action, want, initial_amount, amount, rate, processed) VALUES ('','$date','$ip','$user_session','buy','$BTC','$PST_Order_Amount','$PST_Order_Amount','$PST_Order_Rate','1')")) {
                     $Trade_Message = "System error.";
                  } else {
                     $result = minusfunds($user_session,$BTC,$PST_Order_Sub_Total);
                     if($result=="success") {
                        $Trade_Message = "Buy order has been made, but is waiting a Seller.";
                        header("Location: home.php");
                     } else {
                        $Trade_Message = "System error.";
                     }
                  }
               }
            } else {
               $Trade_Message = 'That amount is to low? Try more coins or a higher rate.';
            }
         } else {
            $Trade_Message = 'Orders that total under 0.00001 are not allowed!';
         }
         } else {
            $Trade_Message = 'You do not have enough '.$BTCS.'s to buy that many '.$BTES.'s!';
         }
      } else {
         $Trade_Message = 'You must enter a rate to buy at!';
      }
   } else {
      $Trade_Message = 'You must enter an amount to buy!';
   }
}
if($PST_Order_Action=="sell") {
   if($PST_Order_Amount) {
      if($PST_Order_Rate) {
         $PST_Order_Amount = satoshitrim(satoshitize($PST_Order_Amount));
         $PST_Order_Rate = satoshitrim(satoshitize($PST_Order_Rate));
         $PST_Order_Sub_Total = $PST_Order_Amount * $PST_Order_Rate;
         $PST_Order_Sub_Total = satoshitrim(satoshitize($PST_Order_Sub_Total));
         if($PST_Order_Amount<=$Coin_B_Balance) {
         if($PST_Order_Amount>="0.00001") {
            if($PST_Order_Sub_Total!=0) {
               if($Buying_Rate>=$PST_Order_Rate) {
                  if($Buying_Rate===$PST_Order_Rate) {
                     $Query = mysql_query("SELECT id, username, amount, rate FROM buy_orderbook WHERE want='$BTC' and processed='1' ORDER BY rate ASC LIMIT 1");
                     while($Row = mysql_fetch_assoc($Query)) {
                        $CURR_Selling_ID = $Row['id'];
                        $CURR_Selling_Username = $Row['username'];
                        $CURR_Selling_Amount = $Row['amount'];
                        $CURR_Selling_Rate = $Row['rate'];
                     }
                     $Client_BTC = userbalance($CURR_Selling_Username,$BTC);
                     $Client_BTE = userbalance($CURR_Selling_Username,$BTE);
                     if($PST_Order_Amount<=$CURR_Selling_Amount) {
                        $Trade_Message = 'Could, Trade matching not done.';
                     } else {
                        $Trade_Message = 'Trade matching not done.';
                     }
                  } else {
                     $Trade_Message = 'Trade matching not done.';
                  }
               } else {
                  if(!mysql_query("INSERT INTO sell_orderbook (id, date, ip, username, action, want, initial_amount, amount, rate, processed) VALUES ('','$date','$ip','$user_session','sell','$BTC','$PST_Order_Amount','$PST_Order_Amount','$PST_Order_Rate','1')")) {
                     $Trade_Message = "System error.";
                  } else {
                     $result = minusfunds($user_session,$BTE,$PST_Order_Amount);
                     if($result=="success") {
                        $Trade_Message = "Sell order has been made, but is waiting a Buyer.";
                        header("Location: home.php");
                     } else {
                        $Trade_Message = "System error.";
                     }
                  }
               }
            } else {
               $Trade_Message = "That amount is to low? Try more coins or a higher rate.";
            }
         } else {
            $Trade_Message = 'Orders that total under 0.00001 are not allowed!';
         }
         } else {
            $Trade_Message = "You are trying to sell more '.$BTES.'s then you have!";
         }
      } else {
         $Trade_Message = "You must enter a rate to sell at!";
      }
   } else {
      $Trade_Message = "You must enter an amount to sell!";
   }
}
?>
<html>
<head>
   <title><?php echo $script_title; ?></title>
   <link rel="shortcut icon" href="image/favicon.ico">
   <?php echo $CSS_Stylesheet; ?>
   <script src="js/jquery-1.9.1.js"></script>
   <script type="text/javascript">
      $(document).ready(function () {
         setInterval(function () {
            $("#balances").load("ajax.php?id=balances");
            $("#pending-deposits").load("ajax.php?id=pending-deposits");
         }, 30000);
        setInterval(function () {
            $("#orderspast").load("ajax.php?id=orderspast-<?php echo $BTC; ?>");
            $("#buyorders").load("ajax.php?id=buyorders-<?php echo $BTC; ?>");
            $("#sellorders").load("ajax.php?id=sellorders-<?php echo $BTC; ?>");
            $(".count").load("online.php");
            $("#stats").load("ajax.php?id=stats");
        }, 60000);
        $("#buy-sells").scrollbars();
      });
   </script>
   <script type="text/javascript">
      function buycalculator() {
         m = document.getElementById('buy-quantity').value;
         n = document.getElementById('buy-rate').value;
         if(m=='') { m = 0; }
         if(n=='') { n = 0; }
         o = m*n;
         g = o.toFixed(8);
         b = o/100;
         c = b/5;
         l = c.toFixed(8);
         document.getElementById('buy-subtotal').innerHTML = g;
         document.getElementById('buy-fee').innerHTML = l;
      }
      function sellcalculator() {
         x = document.getElementById('sell-quantity').value;
         y = document.getElementById('sell-rate').value;
         if(x=='') { x = 0; }
         if(x=='') { x = 0; }
         z = x*y;
         r = z.toFixed(8);
         e = z/100;
         f = e/5;
         s = f.toFixed(8);
         document.getElementById('sell-subtotal').innerHTML = r;
         document.getElementById('sell-fee').innerHTML = s;
      }
      function setbuyamounts(text) {
         document.getElementById('buy-quantity').value = text;
         buycalculator();
      }
      function setbuyrates(text) {
         document.getElementById('buy-rate').value = text;
         buycalculator();
      }
      function setsellamounts(text) {
         document.getElementById('sell-quantity').value = text;
         sellcalculator();
      }
      function setsellrates(text) {
         document.getElementById('sell-rate').value = text;
         sellcalculator();
      }
      function setbuyrateamounts(text) {
         pla = document.getElementById('buy-rate').value;
         plb = text/pla;
         plc = plb.toFixed(8);
         document.getElementById('buy-quantity').value = plc;
         buycalculator();
      }
   </script>
</head>
<body title="[zelles]">
   <center>
   <div align="center" class="headdiv">
   <div align="center" class="balancesdiv"><span id="stats"><?php require'ajax/stats.php'; ?></span></div>
   <table style="width: 100%; height: 50px;">
      <tr>
         <td align="left" style="width: 30px;" nowrap>
            <a href="http://<?php echo $server_url; ?>"><img src="image/logob.png" title="<?php echo $script_title; ?>" alt="Logo" border="0"></a>
         </td>
         <td align="left" style="font-size: 18px; font-weight: bold;" nowrap>
            <a href="http://<?php echo $server_url; ?>"><img src="image/logo.png" title="<?php echo $script_title; ?>" alt="[zelles]" border="0"></a>
         </td>
         <td align="right" valign="top" nowrap>
            <table>
               <tr>
                  <td><a href="home.php">Home</a></td>
                  <td style="padding-left: 5px;"><a href="account.php">Account</a></td>
                  <td style="padding-left: 5px;"><a href="logout.php">Logout</a></td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
   </div>
   <p></p>
   <?php if($Trade_Message) { echo '<div align="center" class="error-msg" nowrap>'.$Trade_Message.'</div><p></p>'; } ?>
   <table class="right-panel-table">
      <tr>
         <td valign="top" align="left" class="right-panel-left">
   <div align="center" class="bodydiv">
   <table style="width: 100%;">
      <tr>
         <td align="left" valign="top" style="padding: 5px;" nowrap>
            <table>
               <tr>
                  <td>
                     <div class="coin-button">
                     <a href="home.php?c=BTC" class="coin-link">BTE/BTC</a>
                     </div>
                  </td>
                  <td style="padding-left: 20px;">
                     <div class="coin-button">
                     <a href="home.php?c=LTC" class="coin-link">BTE/LTC</a>
                     </div>
                  </td>
               </tr>
            </table>
         </td>
      </tr><tr>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <div align="center" class="buy-sells-box">
            <table style="width: 260px; height: 50px;">
               <tr>
                  <td align="left" style="font-weight: bold;" nowrap>Balance:</td>
                  <td align="right" style="font-weight: bold;" nowrap>Lowest Sell Value:</td>
               </tr><tr>
                  <td align="left" nowrap><?php echo '<a href="#" onclick="setbuyrateamounts('.$Coin_A_Balance.');">'.$Coin_A_Balance.'</a> '.$BTC; ?></td>
                  <td align="right" nowrap><?php echo '<a href="#" onclick="setbuyrates('.$Selling_Rate.');">'.$Selling_Rate.'</a> '.$BTC; ?></td>
               </tr>
            </table>
            </div>
            <form action="home.php" method="POST">
            <input type="hidden" name="order-action" value="buy">
            <table>
               <tr>
                  <td colspan="3" style="height: 10px;"></td>
               </tr><tr>
                  <td align="right" nowrap><b>Quantity</b></td>
                  <td align="right" nowrap><input type="text" name="order-amount" value="<?php echo $Bitcoin_Can_Buy; ?>" id="buy-quantity" onkeyup="buycalculator();" onmouseenter="buycalculator();" onchange="buycalculator();" onmouseleave="buycalculator();" class="inputtrade"></td>
                  <td align="left" nowrap><?php echo $BTE; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Rate</b></td>
                  <td align="right" nowrap><input type="text" name="order-rate" value="<?php echo $Selling_Rate; ?>" id="buy-rate" onkeyup="buycalculator();" onmouseenter="buycalculator();" onchange="buycalculator();" onmouseleave="buycalculator();" class="inputtrade"></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Sub-total</b></td>
                  <td align="right" nowrap><font id="buy-subtotal"><?php $bsubtotal = $Bitcoin_Can_Buy * $Selling_Rate; echo satoshitize($bsubtotal); ?></font></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Fee</b></td>
                  <td align="right" nowrap><font id="buy-fee"><?php $bfee = (($bsubtotal / 100) / 5); echo satoshitize($bfee); ?></font></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td colspan="3" style="height: 10px;"></td>
               </tr><tr>
                  <td colspan="3" align="right" nowrap><input type="submit" value="Buy" onmouseenter="buycalculator();" class="buybutton"></td>
               </tr>
            </table>
            </form>
         </td>
         <td align="center" valign="top" style="padding: 5px;" nowrap>
            <div align="center" class="buy-sells-box">
            <table style="width: 260px; height: 50px;">
               <tr>
                  <td align="left" style="font-weight: bold;" nowrap>Balance:</td>
                  <td align="right" style="font-weight: bold;" nowrap>Highest Buy Value:</td>
               </tr><tr>
                  <td align="left" nowrap><?php echo '<a href="#" onclick="setsellamounts('.$Coin_B_Balance.');">'.$Coin_B_Balance.'</a> '.$BTE; ?></td>
                  <td align="right" nowrap><?php echo '<a href="#" onclick="setsellrates('.$Buying_Rate.');">'.$Buying_Rate.'</a> '.$BTC; ?></td>
               </tr>
            </table>
            </div>
            <form action="home.php" method="POST">
            <input type="hidden" name="order-action" value="sell">
            <table>
               <tr>
                  <td colspan="3" style="height: 10px;"></td>
               </tr><tr>
                  <td align="right" nowrap><b>Quantity</b></td>
                  <td align="right" nowrap><input id="sell-quantity" type="text" name="order-amount" value="<?php echo $Coin_B_Balance; ?>" onkeyup="sellcalculator();" onchange="sellcalculator();" onmouseenter="sellcalculator();" onmouseleave="sellcalculator();" class="inputtrade"></td>
                  <td align="left" nowrap><?php echo $BTE; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Rate</b></td>
                  <td align="right" nowrap><input id="sell-rate" type="text" name="order-rate" value="<?php echo $Buying_Rate; ?>" onkeyup="sellcalculator();" onchange="sellcalculator();" onmouseenter="sellcalculator();" onmouseleave="sellcalculator();" class="inputtrade"></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Sub-total</b></td>
                  <td align="right" nowrap><font id="sell-subtotal"><?php $bsubtotal = $Coin_B_Balance * $Buying_Rate; echo satoshitize($bsubtotal); ?></font></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td align="right" nowrap><b>Fee</b></td>
                  <td align="right" nowrap><font id="sell-fee"><?php $sfee = (($ssubtotal / 100) / 5); echo satoshitize($sfee); ?></font></td>
                  <td align="left" nowrap><?php echo $BTC; ?></td>
               </tr><tr>
                  <td colspan="3" style="height: 10px;"></td>
               </tr><tr>
                  <td colspan="3" align="right" nowrap><input type="submit" value="Sell" onmouseenter="sellcalculator();" class="sellbutton"></td>
               </tr>
            </table>
            </form>
         </td>
      </tr><tr>
         <td align="left" valign="top" style="padding: 5px;" nowrap>
            <span id="sellorders"><?php require"ajax/sellorders-$BTC.php"; ?></span>
         </td>
         <td align="left" valign="top" style="padding: 5px;" nowrap>
            <span id="buyorders"><?php require"ajax/buyorders-$BTC.php"; ?></span>
         </td>
      </tr><tr>
         <td colspan="2" align="left" valign="top" style="padding: 5px;" nowrap>
            <span id="orderspast"><?php require"ajax/orderspast-$BTC.php"; ?></span>
         </td>
      </tr>
   </table>
   </div>
         </td>
         <td style="width: 6px;">
         </td>
         <td valign="top" align="left" class="right-panel-right">
            <span id="pending-deposits"><?php require'ajax/pending-deposits.php'; ?></span>
            <div align="left" class="right-panel">
            <span id="balances"><?php require'ajax/balances.php'; ?></span>
            </div>
            <p></p>
            <div align="left" class="right-panel">
            <?php require'ajax/menu.php'; ?>
            </div>
            <p></p>
            <div align="left" class="right-panel"><b>Online:</b><p></p><span class="count"><?php require"online.php"; ?></span></div>
         </td>
      </tr>
   </table>
   <p></p>
   <?php require'ajax/footer.php'; ?>
   <p></p>
   </center>
</body>
</html>
<?php
mysql_close($db_handle);
?>