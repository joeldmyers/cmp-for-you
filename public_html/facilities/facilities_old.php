<?php
error_reporting(0);
include("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Facilities search</title>
<!-- Add jQuery basic library -->
<script type="text/javascript" src="jquery-lib.js"></script>
		
<!-- Add required fancyBox files -->
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js"></script>

<!-- Optional, Add fancyBox for media, buttons, thumbs -->
<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<style>
BODY, TD {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:normal
}
</style>
</head>


<body>
<h3 align="center">Facilities</h3>
<hr>

<form id="form1" name="form1" method="post" action="facilities.php">
<label for="from">Facility Name</label>
<input name="name" type="text" id="name" size="20" value="<?php echo stripcslashes($_REQUEST["name"]); ?>" />

 <label>Zipcode:</label>
<input type="text" name="zipcode" id="zipcode" value="<?php echo stripcslashes($_REQUEST["zipcode"]); ?>" />
<label>State</label>
<select name="state">
<option value="">--</option>
<?php
	$sql = "SELECT * FROM  us_states GROUP BY state_code ORDER BY state_code";
	$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
	while ($row = mysql_fetch_assoc($sql_result)) {
		echo "<option value='".$row["state_code"]."'".($row["state_code"]==$_REQUEST["state"] ? " selected" : "").">".$row["state_code"]."</option>";
	}
?>
</select>
<label>City</label>
<select name="city">
<option value="">--</option>
<?php
	$sql1 = "SELECT * FROM  citystate GROUP BY city ORDER BY city";
	$sql_result1 = mysql_query ($sql1, $connection ) or die ('request "Could not execute SQL query" '.$sql1);
	echo mysql_error();
	while ($row1 = mysql_fetch_assoc($sql_result1)) {
		echo "<option value='".$row1["city"]."'".($row1["city"]==$_REQUEST["city"] ? " selected" : "").">".$row1["city"]."</option>";
	}
?>
</select>
<input type="submit" name="button" id="button" value="Filter" />
  </label>
  <a href="facilities.php"> 
  reset</a>
</form>
<br /><br />
<?php
	if(isset($_REQUEST['button'])){
?>
<h2>
<table width="700" border="1" cellspacing="0" cellpadding="4">
  <tr>
	 <td width="90" bgcolor="#CCCCCC">&nbsp;</td>
    <td width="90" bgcolor="#CCCCCC"><strong>Name</strong></td>
    <td width="95" bgcolor="#CCCCCC"><strong>Address</strong></td>
    <td width="159" bgcolor="#CCCCCC"><strong>City</strong></td>
    <td width="191" bgcolor="#CCCCCC"><strong>State</strong></td>
	 <td width="191" bgcolor="#CCCCCC"><strong>Zip</strong></td>
	 <td width="191" bgcolor="#CCCCCC"><strong>Phone</strong></td>
	  <td width="191" bgcolor="#CCCCCC"><strong>Type</strong></td>
    <td width="113" bgcolor="#CCCCCC"><strong>Map</strong></td>
	<td width="113" bgcolor="#CCCCCC"><strong>Direction</strong></td>
  </tr>
<?php
if ($_REQUEST["name"]<>'') {
	$search_string = " AND ( facility_name LIKE '%".mysql_real_escape_string($_REQUEST["name"])."%' )";	
}
if ($_REQUEST["city"]<>'') {
	$search_string .= " AND facility_city='".mysql_real_escape_string($_REQUEST["city"])."'";	
}
if ($_REQUEST["state"]<>'') {
	$search_string .= " AND facility_state='".mysql_real_escape_string($_REQUEST["state"])."'";	
}
if ($_REQUEST["zipcode"]<>'') {
	$search_string .= " AND facility_zip ='".mysql_real_escape_string($_REQUEST["zipcode"])."'";	
}



	$sql = "SELECT * FROM facilities  WHERE facility_id>0".$search_string;
	

$sql_result = mysql_query ($sql, $connection );
echo mysql_error();



$i=1;
if (mysql_num_rows($sql_result)>0) {
	while ($row = mysql_fetch_assoc($sql_result)) {
	
	$id=$row["facility_id"];
	$loc=$row["facility_city"].', '.$row["facility_state"];
	$url="map.php?id=".$id."&myloc=".$loc;
	$direction_url="directions.php?id=".$id."&myloc=".$loc;
	
?>
  <tr>
	 <td><?php echo $i; ?></td>
    <td><?php echo $row["facility_name"]; ?></td>
    <td><?php echo $row["facility_address"]; ?></td>
    <td><?php echo $row["facility_city"]; ?></td>
    <td><?php echo $row["facility_state"]; ?></td>
	<td><?php echo $row["facility_zip"]; ?></td>
	<td><?php echo $row["facility_phone"]; ?></td>
	<td><?php echo $row["facility_type"]; ?></td>
   <!-- <td><?php echo "<a href='".$url."' target='_blank'>Map</a>"; ?></td>-->
	<td><a class="various" data-fancybox-type="iframe" href="<?php echo $url; ?>">Map</a></td>
	 <td><a class="various" data-fancybox-type="iframe" href="<?php echo $direction_url; ?>">Direction</a></td>
  </tr>
<?php
	$i=$i+1;
	}
} else {
?>
<tr><td colspan="5">No results found.</td>
<?php	
}
?>
</table>
<?php
}
?>
<script type="text/javascript">
$(document).ready(function(){
		
	$(".various").fancybox({
		maxWidth	: 900,
		maxHeight	: 900,
		fitToView	: false,
		width		: '100%',
		height		: '100%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	
	
	
	
});
</script>
</body>
</html>