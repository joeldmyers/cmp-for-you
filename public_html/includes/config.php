<?php
@ini_set('session.gc_maxlifetime', '3600');
set_time_limit(0);
ini_set('memory_limit', '256M');

$remotelocation="https://cmpforyou.com/";

$host = "localhost"; // Host name

$username = "cm4uadmin"; // Mysql username
$password = "%Tl8]lW.@#+N"; // Mysql password
$db_name = "cmforyoudb"; // Database name

date_default_timezone_set('America/Los_Angeles');

define("DOCUMENT_PATHS",$_SERVER['DOCUMENT_ROOT']."/help_docs");
define("ROOT_PATHS",$_SERVER['DOCUMENT_ROOT']."/latest/images");
?>
