<?php
require_once("includes/top.php");
require_once("includes/header.php");
global $db;
$email=mysql_real_escape_string($_POST['email']);

if($email !== '')
{

	$query = $db->Execute("select","select * from `tbl_patients` where `patient_email`='".$email."'") or die(mysql_error());
        $result = $db->rowcount($query);
	if($result > 0)
	{
		echo "<span style='color:red'>This email already in use</span>";
                exit();
	}
	else
	{
	    exit;	 
	}
}
?>