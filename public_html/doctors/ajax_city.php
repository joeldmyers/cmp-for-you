<?php
include("config.php");
extract($_REQUEST);
?>

<select name="city" style="width:50%; float:none;">
<option value="">Select City</option>
<?php
	$sql1 = "SELECT * FROM  citystate WHERE city!='' AND state='$city' GROUP BY city ORDER BY city";
	$sql_result1 = mysql_query ($sql1, $connection ) or die ('request "Could not execute SQL query" '.$sql1);
	echo mysql_error();
	while ($row1 = mysql_fetch_assoc($sql_result1)) {
		echo "<option value='".$row1["city"]."'".($row1["city"]==$_REQUEST["city"] ? " selected" : "").">".$row1["city"]."</option>";
	}
?>
</select>
<?php
exit;
?>