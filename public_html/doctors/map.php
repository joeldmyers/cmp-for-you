<?php
error_reporting(0);
include("config.php");
include_once("GoogleMap.php");
include_once("JSMin.php");

$id=$_GET['id'];
$myloc=$_GET['myloc'];

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


$MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
$MAP_OBJECT->setMapType('TERRAIN');
$MAP_OBJECT->addMarkerByAddress("$address, $city, $state","$name", "$name");
?>
<html>
<head>
<?=$MAP_OBJECT->getHeaderJS();?>
<?=$MAP_OBJECT->getMapJS();?>
</head>
<body>
<table>
<td>

<?
//var_dump($MAP_OBJECT);

?>


<?=$MAP_OBJECT->printOnLoad();?>
<?=$MAP_OBJECT->printMap();?>
<?=$MAP_OBJECT->printSidebar();?>
</td>
<?
echo "<td valign=top>";
echo "<table>";
echo "<td>Doctor Name:$name</td>";
echo "<tr><td valign=top>Address:$address, $city, $state</td>";
?>
</table>

<?
echo "<a href=\"directions.php?id=$id&myloc=$myloc\">Directions</a>";
?>

<br>
<br>

</td>
</table>
<script>
	function close_box(){
		 $('#fancybox-close').trigger('click');
	}
</script>
</body>
</html>