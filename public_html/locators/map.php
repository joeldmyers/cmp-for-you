<?php
//error_reporting(0);
include_once("GoogleMap.php");
include_once("JSMin.php");
require_once("../includes/top.php");
$id=$_GET['id'];
$myloc=$_GET['myloc'];
$data=$db->Execute("select", "SELECT concat(coalesce(first_name,''),' ',coalesce(middle_name,''),' ',coalesce(last_name,'')) as name,concat(coalesce(street_address_line1,''),' ',coalesce(street_address_line2,'')) as address,city,doctor_state FROM `medic1` where doctor_id=".$id );
if(empty($data)){
    echo "Sorry!! we are unable to fetch the information.";
}
$name=$data[0]['name'];
$address=$data[0]['address'];

$city=$data[0]['city'];
$state=$data[0]['doctor_state'];
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