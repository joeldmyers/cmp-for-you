<?php
include_once("GoogleMap.php");
include_once("JSMin.php");
require_once("../includes/top.php");
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

$data=$db->Execute("select", "SELECT name,city,state,zip FROM `pharmacy` where id=".$id );
if(empty($data)){
    echo "Sorry!! we are unable to fetch the information.";
}
$name=$data[0]['name'];
$address=$data[0]['address'];

$city=$data[0]['city'];
$state=$data[0]['state'];
$zip=$data[0]['zip'];


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

<tr><td>Street</td><td><input type="text" name="myaddress" value="<?php echo (isset($_POST['myaddress']) && !empty($_POST['myaddress']))? $_POST['myaddress']:''; ?>"></td>
<tr><td>City</td><td><input type="text" name="mycity" value="<?php echo (isset($_POST['mycity']) && !empty($_POST['mycity']))? $_POST['mycity']:''; ?>"></td>
<tr><td>State</td><td><input type="text" name="mystate" value="<?php echo (isset($_POST['mystate']) && !empty($_POST['mystate']))? $_POST['mystate']:''; ?>"></td>
<tr><td>Zip Code</td><td><input type="text" name="myzip" value="<?php echo (isset($_POST['myzip']) && !empty($_POST['myzip']))? $_POST['myzip']:''; ?>"></td>

<tr><td><td><input type="submit" value="Get Directions"></td>
</form>
</table>


<hr>

<br>
</td>
</table>
</body>
</html>