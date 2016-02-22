<?php 
	function chk_login($page)
	{
		$m=header('location:'.$page);
		return $m;
	}
	
	function login_auth($a,$b,$c,$d,$e)
	{
		$p= mysql_query("select * from ".$a." where `email`='".$b."' and `password`='".$c."' and `status`='".$d."' and `type`='".$e."'") or die(mysql_error());
		return $p;
	}


function qry_insert($table, $data){

    	$fields = array_keys( $data );  
    	$values = array_map( "mysql_real_escape_string", array_values( $data ) );
    	mysql_query( "INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');") or die( mysql_error() );

	}
	
	function get_header()
	{
		require_once("view/header.tpl.php");
	}
	function get_footer()
	{
		require_once("view/footer.tpl.php");
	}
	function get_sidebar()
	{
		require_once("view/sidebar.tpl.php");
	}
	function get_banner()
	{
		require_once("view/banner.tpl.php");
	}
?>