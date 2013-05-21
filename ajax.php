<?php
session_start();
error_reporting(0);
require_once'jsonRPCClient.php';
require_once'auth.php';
$ajax_id = security($_GET['id']);
if(!$user_session) {
   $do = "nothing";
} else {
   if($ajax_id=="pending-deposits") { require'ajax/pending-deposits.php'; }
   if($ajax_id=="balances") { require'ajax/balances.php'; }
}
if($ajax_id=="buyorders-BTC") { require'ajax/buyorders-BTC.php'; }
if($ajax_id=="sellorders-BTC") { require'ajax/sellorders-BTC.php'; }
if($ajax_id=="orderspast-BTC") { require'ajax/orderspast-BTC.php'; }

if($ajax_id=="buyorders-LTC") { require'ajax/buyorders-LTC.php'; }
if($ajax_id=="sellorders-LTC") { require'ajax/sellorders-LTC.php'; }
if($ajax_id=="orderspast-LTC") { require'ajax/orderspast-LTC.php'; }

if($ajax_id=="stats") { require'ajax/stats.php'; }
?>