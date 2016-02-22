<?php 
	@$p=$_GET['p'];
	
	if($p != ''):
	include_once("class/".$p.".php");
	else:
	include_once("class/default.php");
	endif;
	
?>