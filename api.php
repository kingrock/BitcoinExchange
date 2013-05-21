<?php
session_start();
error_reporting(0);
require_once'jsonRPCClient.php';
require_once'auth.php';
if($Logged_In===7) {
   $sql = "SELECT * FROM apis WHERE username='$user_session'";
   $result = mysql_query($sql);
   $count = mysql_num_rows($result);
   if($count!=1) {
      $inns_apikey = apikeygen();    // generate a random api key, public key?
      $inns_privkey = apikeygen();   // generate a second random api key, private key?
      if(!mysql_query("INSERT INTO apis (id,username,apikey,privkey) VALUES ('','$user_session','$inns_apikey','$inns_privkey')")){
         $return_error = "System error.";
      } else {
         $return_error = "Logged in.";
      }
   }
   $Query = mysql_query("SELECT apikey, privkey FROM apis WHERE username='$user_session'");
   while($Row = mysql_fetch_assoc($Query)) {
      $API_KEY = $Row['apikey'];      // the first api key generated, public key?
      $PRIV_KEY = $Row['privkey'];    // the second api key generated, private key?
   }
}
?>
<html>
<head>
   <title><?php echo $script_title; ?> - Developer API</title>
   <link rel="shortcut icon" href="image/favicon.ico">
   <?php echo $CSS_Stylesheet; ?>
   <script src="js/jquery-1.9.1.js"></script>
   <script type="text/javascript">
      $(document).ready(function () {
         <?php
         if($Logged_In===7) {
            echo 'setInterval(function () {
                     $("#balances").load("ajax.php?id=balances");
                     $("#pending-deposits").load("ajax.php?id=pending-deposits");
                  }, 30000);';
         }
         ?>
         setInterval(function () {
            $(".count").load("online.php");
            $("#stats").load("ajax.php?id=stats");
         }, 60000);
      });
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
         <?php
         if($Logged_In!==7) {
            echo '<td align="right" nowrap>
                     <form action="index.php" method="POST">
                     <input type="hidden" name="action" value="login">
                     <table>
                        <tr>
                           <td align="right"><input type="text" name="username" placeholder="Username" style="width: 110px;" required autofocus></td>
                           <td align="right"><input type="password" name="password" placeholder="Password" style="width: 110px;" required></td>
                           <td colspan="2" align="right"><input type="submit" name="submit" class="button" value="Login"></td>
                        </tr>
                     </table>
                     </form>
                  </td>';
         } else {
            echo '<td align="right" valign="top" nowrap>
                     <table>
                        <tr>
                           <td><a href="home.php">Home</a></td>
                           <td style="padding-left: 5px;"><a href="account.php">Account</a></td>
                           <td style="padding-left: 5px;"><a href="logout.php">Logout</a></td>
                        </tr>
                     </table>
                  </td>';
         }
         ?>
      </tr>
   </table>
   </div>
   <p></p>
   <?php if($withdraw_message) { echo '<div align="center" class="error-msg" nowrap>'.$withdraw_message.'</div><p></p>'; } ?>
   <table class="right-panel-table">
      <tr>
         <td valign="top" align="left" class="right-panel-left">
   <div align="center" class="bodydiv">
   <table style="width: 650px;">
      <tr>
         <td align="left" valign="top" style="padding: 5px;" nowrap>
            <table style="width: 100%;">
               <tr>
                  <td align="left" valign="top" style="padding: 5px; padding-right: 15px;" nowrap>
                     <?php
                     if($Logged_In===7) {
                        echo '<b>API Credentials:</b>
                              <table>
                                 <tr>
                                    <td align="left" style="font-weight: bold; padding: 3px; padding-left: 10px;" nowrap>Public Key:</td>
                                 </tr><tr>
                                    <td align="left" style="padding: 2px; padding-left: 20px;" nowrap>'.$API_KEY.'</td>
                                 </tr><tr>
                                    <td align="left" style="font-weight: bold; padding: 3px; padding-left: 10px; nowrap">Private Key:</td>
                                 </tr><tr>
                                    <td align="left" style="padding: 2px; padding-left: 20px;" nowrap>'.$PRIV_KEY.'</td>
                                 </tr>
                              </table>
                              <p></p>';
                     }
                     ?>
                     <b>API Calls:</b>
                     <table>
                        <tr>
                           <td align="left" style="padding: 3px; padding-left: 15px;" nowrap>Api is under development.</td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
   </div>
         </td>
         <td style="width: 6px;">
         </td>
         <td valign="top" align="left" class="right-panel-right">
            <div align="center" class="pending-right">
            Under development.
            </div>
            <p></p>
            <?php
            if($Logged_In===7) {
               echo '<span id="pending-deposits">';
               require'ajax/pending-deposits.php';
               echo '</span>
                     <div align="left" class="right-panel">
                     <span id="balances">';
               require'ajax/balances.php';
               echo '</span>
                     </div>
                     <p></p>
                     <div align="left" class="right-panel">';
               require'ajax/menu.php';
               echo '</div>';
            }
            ?>
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