<?php
error_reporting(0);
include("config.php");
$rec_limit = 50;
?>
 <link href="../css/bootstrap.css" rel="stylesheet" id="id_bootstrap">
 <title>Doctor search</title>
<!-- Add jQuery basic library -->
<script type="text/javascript" src="jquery-lib.js"></script>
		
<!-- Add required fancyBox files -->
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js"></script>

<!-- Optional, Add fancyBox for media, buttons, thumbs -->
<link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<style>
.h1{ 
	color:#2B96CC;
	font-family: 'Roboto', sans-serif;
	font-size:24px;
	}
	.btn-default {
    background-color: #2B96CC;
   
    -moz-box-shadow:0 3px 0 #1b83b8;
    -webkit-box-shadow:0 3px 0 #1b83b8;
   }
    td{
		font-size:12px;
   }
   
   input[type="text"], input[type="email"], input[type="number"], select{
		width:100%;
   }
   .nxt-btn a:link, .nxt-btn a:visited {
    background-color: transparent;
    color: #000;
    font-size: 105%;
    padding: 3px 20px 6px;
    text-decoration: none;
}
</style>

<div class="container">
	  	<section class="appointment-sec text-center" style="background-color:#f3f3f3"> 
        <div class="container">
            <h1 class="h1">Doctors Search</h1>
            <div class="row" style="width: 100%; padding-right: 6%; padding-left: 6%; text-align: center;">
				<div class="" style="width: 100%; text-align: center;">
                <div class="appointment-form clearfix">
                    <form id="form1" name="form1" method="post" action="doctors.php">
					<input type="hidden" name="page" value=""/>
					<table width="100%">
					<tr>
						<td width="50%">  <input type="text" name="name"  placeholder="Doctor Name" value="<?php echo stripcslashes(@$_REQUEST["name"]); ?>"></td>
						<td width="50%">
						<select name="specialty">
							<option value="">Select Specialty</option>
							<?php
								$sql = "SELECT DISTINCT(`Primary specialty`) as unique_speciality FROM `medic` ORDER BY `Primary specialty`";
								$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
								while ($row = mysql_fetch_assoc($sql_result)) {
									echo "<option value='".$row["unique_speciality"]."'".($row["unique_speciality"]==$_REQUEST["specialty"] ? " selected" : "").">".$row["unique_speciality"]."</option>";
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
                        <input type="text" name="zipcode"  placeholder="Zipcode" value="<?php echo @$_REQUEST["zipcode"]; ?>"></td>
                      <td> 
                       <select name="state">
							<option value="">Select State</option>
							<?php
								$sql = "SELECT * FROM  us_states GROUP BY state_code ORDER BY state_code";
								$sql_result = mysql_query ($sql, $connection ) or die ('request "Could not execute SQL query" '.$sql);
								while ($row = mysql_fetch_assoc($sql_result)) {
									echo "<option value='".$row["state_code"]."'".($row["state_code"]==$_REQUEST["state"] ? " selected" : "").">".$row["state_code"]."</option>";
								}
							?>
							</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
							<select name="city" style="width:50%; float:none;">
								<option value="">Select City</option>
								<?php
									$sql1 = "SELECT * FROM  citystate WHERE city!='' GROUP BY city ORDER BY city";
									$sql_result1 = mysql_query ($sql1, $connection ) or die ('request "Could not execute SQL query" '.$sql1);
									echo mysql_error();
									while ($row1 = mysql_fetch_assoc($sql_result1)) {
										echo "<option value='".$row1["city"]."'".($row1["city"]==$_REQUEST["city"] ? " selected" : "").">".$row1["city"]."</option>";
									}
								?>
								</select>
					</td>
				</tr>	
				
				<tr>
					<td colspan="2" align="center">
						<input type="submit" value="Search" name="button" class="btn btn-default btn-rounded" style="min-width:100px;font-size:14px; float:none;">
					</td>
				</tr>
					
                    
                    </form>
                </div>
            </div>
            </div>
        </div>
    </section>
<br /><br />
<?php
	if(isset($_REQUEST['button'])){
		
?>
<h2>
<table  width="100%" border="1" cellspacing="0" cellpadding="4">
  <tr>
	 <td width="90" bgcolor="#CCCCCC"  style='color:white'>&nbsp;</td>
    <td width="90" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Name</strong></td>
    <td width="95" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Specialty</strong></td>
    <td width="159" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Organization</strong></td>
    <td width="191" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Address</strong></td>
	 <td width="191" bgcolor="#CCCCCC" style='color:white' align="center"><strong>City</strong></td>
	  <td width="191" bgcolor="#CCCCCC" style='color:white' align="center"><strong>State</strong></td>
	   <td width="191" bgcolor="#CCCCCC" style='color:white' align="center"><strong>zip</strong></td>
    <td width="113" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Map</strong></td>
	<td width="113" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Direction</strong></td>
  </tr>
<?php
$name_search=$_REQUEST["name"];
$city_search=$_REQUEST["city"];
$state_search=$_REQUEST["state"];
$zipcode_search=$_REQUEST["zipcode"];
$specialty_search=$_REQUEST["specialty"];
if ($_REQUEST["name"]<>'') {
	$search_string = " AND ( `First Name` LIKE '%".mysql_real_escape_string($_REQUEST["name"])."%' OR `Last Name` LIKE '%".mysql_real_escape_string($_REQUEST["name"])."%')";	
}
if ($_REQUEST["city"]<>'') {
	$search_string .= " AND City='".mysql_real_escape_string($_REQUEST["city"])."'";	
}
if ($_REQUEST["state"]<>'') {
	$search_string .= " AND State='".mysql_real_escape_string($_REQUEST["state"])."'";	
}
if ($_REQUEST["zipcode"]<>'') {
	$search_string .= " AND `Zip Code`='".mysql_real_escape_string($_REQUEST["zipcode"])."'";	
}
if ($_REQUEST["specialty"]<>'') {
	$search_string .= " AND ( `Primary specialty` LIKE '%".mysql_real_escape_string($_REQUEST["specialty"])."%' OR `Secondary specialty 1` LIKE '%".mysql_real_escape_string($_REQUEST["specialty"])."%' OR `Secondary specialty 2` LIKE '%".mysql_real_escape_string($_REQUEST["specialty"])."%')";	
}
$sql = "SELECT count(id) FROM medic  WHERE id>0".$search_string;
$retval = mysql_query( $sql);

$row = mysql_fetch_array($retval );
$rec_count = $row[0];

if( isset($_GET{'page'} ) &&  $_GET{'page'}!='')
{
$page = $_GET{'page'} + 1;
$offset = $rec_limit * $page ;
}
else
{
$page = 0;
$offset = 0;


}
$left_rec = $rec_count - ($page * $rec_limit);


$search="&name=$name_search&city=$city_search&state=$state_search&zipcode=$zipcode_search&speciality=$speciality_search";
$search_string .=  " LIMIT $offset, $rec_limit";


	$sql = "SELECT * FROM medic  WHERE id>0".$search_string;
	
//echo $sql;

$sql_result = mysql_query ($sql, $connection );
echo mysql_error();
// die ('request "Could not execute SQL query" '.$sql);

$i=$offset+1;
if (mysql_num_rows($sql_result)>0) {
	
	while ($row = mysql_fetch_assoc($sql_result)) {
	if($i%2==0){
		$color="#EDF3FE";
	} else {
		$color="#FFFFFF";
	}
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
	$id=$row["id"];
	$loc=$row["City"].', '.$row["State"];
	$url="map.php?id=".$id."&myloc=".$loc;
	$direction_url="directions.php?id=".$id."&myloc=".$loc;
	
?>
  <tr bgcolor="<?php echo $color; ?>">
	 <td align="right"><?php echo $i; ?></td>
    <td align="center"><a class="various" data-fancybox-type="iframe" href="<?php echo $url; ?>"  style='color:#646464;cursor:pointer'><?php echo $name; ?></a></td>
    <td align="center"><?php echo $row["Primary specialty"]; ?></td>
    <td align="center"><?php echo $row["Organization legal name"]; ?></td>
    <td align="center"><?php echo $address; ?></td>
	  <td align="center"><?php echo $row["City"]; ?></td>
	    <td align="center"><?php echo $row["State"]; ?></td>
		  <td align="center"><?php echo $row["Zip Code"]; ?></td>
  <td align="center"><a class="various" data-fancybox-type="iframe" href="<?php echo $url; ?>"  style='color:#2B96CC'>Map</a></td>
	 <td align="center"><a class="various" data-fancybox-type="iframe" href="<?php echo $direction_url; ?>"  style='color:#2B96CC'>Direction</a></td>
  </tr>
<?php
	$i=$i+1;
	}
	if( $page > 0  && ($left_rec > $rec_limit))
	{
	   $last = $page - 2;
	   echo "<tr><td class='nxt-btn' align='center' colspan='10'><a href=\"$_PHP_SELF?page=$last&button=1$search\"><< Previous Page</a> |";
	   echo "<a href=\"$_PHP_SELF?page=$page&button=1$search\">Next Page >></a></td></tr>";
	}
	else if( $page == 0 )
	{
		
	   echo "<tr><td  class='nxt-btn'  align='center' colspan='10'><a href=\"$_PHP_SELF?page=$page&button=1$search\">Next Page >></a></td></tr>";
	}
	else if( $left_rec < $rec_limit )
	{
	   $last = $page - 2;
	   echo "<tr><td  class='nxt-btn'  align='center' colspan='10'><a href=\"$_PHP_SELF?page=$last&button=1$search\"><< Previous Page</a></td></tr>";
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
</div>