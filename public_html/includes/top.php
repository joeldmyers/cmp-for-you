<?php
ini_set('session.gc_maxlifetime', 10800);
session_set_cookie_params(10800);
@session_start();
	if(file_exists("includes/config.php")){
	    require_once("includes/config.php");
		require_once("includes/database_tables.php");
		require_once("includes/classes/database.php");
	} else if(file_exists("../includes/config.php")){
	    require_once("../includes/config.php");
		require_once("../includes/database_tables.php");
		require_once("../includes/classes/database.php");
	} else if(file_exists("config.php")){
	    require_once("config.php");
		require_once("database_tables.php");
		require_once("classes/database.php");
	} else if(file_exists("../../includes/config.php")){
		require_once("../../includes/config.php");
		require_once("../../includes/database_tables.php");
		require_once("../../includes/classes/database.php");
	}
	global $db;
	$db = new databaseConnection($host,$db_name,$username,$password);
	$db->connect();

// Function to redirect Browser
function redirect($url)
{
   if (!headers_sent())
		@header('Location: '.$url);
   else
   {
		echo '<script type="text/javascript">';
    	echo 'window.location.href="'.$url.'";';
       	echo '</script>';
       	echo '<noscript>';
       	echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
       	echo '</noscript>';
   }
}


function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
$sets = array();
if(strpos($available_sets, 'l') !== false)
$sets[] = 'abcdefghjkmnpqrstuvwxyz';
if(strpos($available_sets, 'u') !== false)
$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
if(strpos($available_sets, 'd') !== false)
$sets[] = '23456789';
if(strpos($available_sets, 's') !== false)
$sets[] = '!@#$%&*?';

$all = '';
$password = '';
foreach($sets as $set)
{
$password .= $set[array_rand(str_split($set))];
$all .= $set;
}

$all = str_split($all);
for($i = 0; $i < $length - count($sets); $i++)
$password .= $all[array_rand($all)];

$password = str_shuffle($password);

if(!$add_dashes)
return $password;

$dash_len = floor(sqrt($length));
$dash_str = '';
while(strlen($password) > $dash_len)
{
$dash_str .= substr($password, 0, $dash_len) . '-';
$password = substr($password, $dash_len);
}
$dash_str .= $password;
return $dash_str;
}
?>