<?php
session_start();
error_reporting(0);
require_once'jsonRPCClient.php';
require_once'auth.php';
if($Logged_In!==7) {
   header("Location: index.php");
}
$pcurpass = security($_POST['curpass']);
$pnewpass = security($_POST['newpass']);
$preppass = security($_POST['reppass']);
$form_action = security($_POST['action']);
if($form_action=="currentpass") {
   if($pcurpass) {
      if($pnewpass) {
         $pLength = strlen($pnewpass);
         if($pLength >= 3 && $pLength <= 30) {
            if($pnewpass==$preppass) {
               $pcurpass = md5($pcurpass);
               $Query = mysql_query("SELECT * FROM users WHERE username='$user_session'");
               while($Row = mysql_fetch_assoc($Query)) {
                  $db_Change_Pass_Check = $Row['password'];
               }
               if($pcurpass==$db_Change_Pass_Check) {
                  $pnewpass = md5($pnewpass);
                  $sql = "UPDATE users SET password='$pnewpass' WHERE username='$user_session'";
                  $result = mysql_query($sql);
                  if($result) {
                     $withdraw_message = 'Your password has been changed!';
                  } else {
                     $withdraw_message = 'Internal error while trying to change!';
                  }
               } else {
                  $withdraw_message = 'The current password was entered incorrectly!';
               }
            } else {
               $withdraw_message = 'Your new password was not repeated correctly!';
            }
         } else {
            $withdraw_message = "Password must be between 3 and 30 characters!";
         }
      } else {
         $withdraw_message = 'You must enter a new password!';
      }
   } else {
      $withdraw_message = 'You must enter your current password!';
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

                  </td>
                  <td align="right" valign="top" style="padding: 5px; padding-right: 15px;" nowrap>
                     <form action="account.php" method="POST">
                     <input type="hidden" name="action" value="currentpass">
                     <table>
                        <tr>
                           <td align="left" style="font-weight: bold;">Change passwords:</td>
                        </tr><tr>
                           <td align="right"><input type="password" name="curpass" placeholder="Current Password"></td>
                        </tr><tr>
                           <td align="right"><input type="password" name="newpass" placeholder="New Password"></td>
                        </tr><tr>
                           <td align="right"><input type="password" name="reppass" placeholder="Repeat Password"></td>
                        </tr><tr>
                           <td align="right"><input type="submit" name="submit" value="Change Password" class="button"></td>
                        </tr>
                     </table>
                     </form>
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