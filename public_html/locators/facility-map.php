<?php
//error_reporting(0);
include_once("GoogleMap.php");
include_once("JSMin.php");
require_once("../includes/top.php");
$id=$_GET['id'];
$myloc=$_GET['myloc'];
$data=$db->Execute("select", "SELECT facility_name,facility_address,facility_city,facility_state,facility_zip FROM `facilities` where facility_id=".$id );
if(empty($data)){
    echo "Sorry!! we are unable to fetch the information.";
}
$name=$data[0]['facility_name'];
$address=$data[0]['facility_address'];

$city=$data[0]['facility_city'];
$state=$data[0]['facility_state'];
$zip=$data[0]['facility_zip'];


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
<td width="70%">

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
echo "<tr><td valign=top>Facility Address:$address, $city, $state</td>";
?>
</table>

<?
echo "<a href=\"facility-directions.php?id=$id&myloc=$myloc\">Directions</a>";
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