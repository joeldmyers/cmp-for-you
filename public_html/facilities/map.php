<?php
include("config.php"); //include database connection details
include_once("GoogleMap.php");
include_once("JSMin.php");

$id=$_GET['id'];
$myloc=$_GET['myloc'];

$sql= "select facility_name,facility_address,facility_city,facility_state,facility_zip from facilities where facility_id=$id ";
//echo "$sql";
$result = mysql_query($sql) or die('<p>Query to get hash data from patients table failed:' . mysql_error() . '</p>');


while ($row = mysql_fetch_array($result)) {

$name=$row[0];
$address=$row[1];
$city=$row[2];
$state=$row[3];
$zip=$row[4];
}


$MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
$MAP_OBJECT->setMapType('TERRAIN');
$MAP_OBJECT->addMarkerByAddress("$address, $city, $state","$name", "$zip");
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
echo "<td>Facility Name:$name</td>";
echo "<tr><td valign=top>Facility Address: $address, $city, $state,$zip</td>";
?>
</table>

<?
echo "<a href=\"directions.php?id=$id&myloc=$myloc\">Directions</a>";
?>

<br>

<br>

</td>
</table>
</body>
</html>