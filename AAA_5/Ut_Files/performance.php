<?php
include_once('adodb.inc.php');
session_start(); # session variables required for monitoring
$conn = ADONewConnection('mysql');
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$perf =& NewPerfMonitor($conn);
$perf->UI($pollsecs=5);
?>


