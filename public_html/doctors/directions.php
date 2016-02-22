<?php
include("config.php");
include_once("GoogleMap.php");
include_once("JSMin.php");

$id=$_GET['id'];
$myloc=$_GET['myloc'];


$myaddress=@$_POST['myaddress'];
$mycity=@$_POST['mycity'];
$mystate=@$_POST['mystate'];
$myzip=@$_POST['myzip'];

if ($myaddress!="" && $mycity!="" && $mystate!="")
{
$myloc=$myaddress.','.$mycity.','.$mystate.','.$myzip;
}

$sql= "select * from medic where id=$id ";
//echo "$sql";
$result = mysql_query($sql) or die('<p>Query to get hash data from patients table failed:' . mysql_error() . '</p>');


while ($row = mysql_fetch_array($result)) {
if($row["Middle Name"]!=''){
		$name=$row["First Name"]." ".$row["Middle Name"]." ".$row["Last Name"];
	} else {
		$name=$row["First Name"]." ".$row["Last Name"];
	}
	if($row["Line 2 Street Address"]!=''){
		$address=$row["Line 1 Street Address"]." ".$row["Line 2 Street Address"];
	} else {
		$address=$row["Line 1 Street Address"];
	}
$name=$name;
$address=$address;
$city=$row['City'];
$state=$row['State'];
}

$DIRECTIONS_CONTAINER_ID = "map_directions";
$MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
$MAP_OBJECT->disableSidebar();
$MAP_OBJECT->addDirections("$myloc","$address, $city, $state", $DIRECTIONS_CONTAINER_ID, $display_markers=true);

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../formstyle.css">

<?=$MAP_OBJECT->getHeaderJS();?>
<?=$MAP_OBJECT->getMapJS();?>
</head>
<body>
<table border=0>
<td>

<?
echo "<h3>Directions from $myloc</h3>";

echo "<tr><td valign=top>";
echo "<tr><td>$name</td>";
echo "<tr><td valign=top>$address, $city, $state</td>";

echo "</td>";
echo "<tr>";
echo "<td>";

// var_dump($MAP_OBJECT);
?>

<?=$MAP_OBJECT->printOnLoad();?> 
<?=$MAP_OBJECT->printMap();?>
<div id="<?=$DIRECTIONS_CONTAINER_ID?>"></div>

<hr>
<a href="locator.php">Return to Locator</a>
</td>

<td valign="top">

<h3>Get Directions from a specific address</h3>

<table>
<form name="myform" method="POST" action="<?= @$PHP_SELF ?>" >

<tr><td>Street</td><td><input type="text" name="myaddress"></td>
<tr><td>City</td><td><input type="text" name="mycity"></td>
<tr><td>State</td><td><input type="text" name="mystate"></td>
<tr><td>Zip Code</td><td><input type="text" name="myzip"></td>

<tr><td><td><input type="submit" value="Get Directions"></td>
</form>
</table>


<hr>

<br>
</td>
</table>
</body>
</html>