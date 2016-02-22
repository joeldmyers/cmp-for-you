<?php
error_reporting(0);
include("doctors/config.php");
?>
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
		font-size:14px;
   }
   
   input[type="text"], input[type="email"], input[type="number"], select{
		width:100%;
   }
</style>
 <title>Doctor search</title>
<!-- Add jQuery basic library -->
<script type="text/javascript" src="jquery-lib.js"></script>
		
<!-- Add required fancyBox files -->
<link rel="stylesheet" href="doctors/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="doctors/fancybox/source/jquery.fancybox.pack.js"></script>

<!-- Optional, Add fancyBox for media, buttons, thumbs -->
<link rel="stylesheet" href="doctors/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="doctors/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<div class="container">
<br/>
		<section class="appointment-sec text-center" style="background-color:#f3f3f3"> 
        <div class="container">
            <h3 class="h1">Doctors Search</h3>
            <div class="row" style="width: 100%; padding-right: 6%; padding-left: 6%; text-align: center;">
				<div class="" style="width: 100%; text-align: center;">
                <div class="appointment-form clearfix">
                    <form id="form1" name="form1" method="post" action="">
					<table width="100%">
					<tr>
						<td width="50%">  <input type="text" name="name"  placeholder="Doctor Name" value="<?php echo stripcslashes(@$_REQUEST["name"]); ?>" style='font-weight:bold'></td>
						<td width="50%">
						<select name="specialty" style='font-weight:bold'>
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
                        <input type="text" name="zipcode"  placeholder="Zipcode" value="<?php echo @$_REQUEST["zipcode"]; ?>" style='font-weight:bold'></td>
                      <td> 
                       <select name="state" onchange="loadXMLDoc(this.value)" style='font-weight:bold' >
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
					<span id="id_city" >
							<select name="city" style="width:50%; float:none;font-weight:bold">
								<option value="">Select City</option>
								
								</select>
					</span>			
					</td>
				</tr>	
				
				<tr>
					<td colspan="2" align="center">
						<input type="submit" value="Search" name="button" class="btn btn-default btn-rounded" style="min-width:100px;font-size:14px; float:none;">
					</td>
				</tr>
					
                 </table>   
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

<table  width="100%" border="1" cellspacing="0" cellpadding="4">
  <tr>
	 <td width="90"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px">&nbsp;</td>
    <td width="90"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Name</strong></td>
    <td width="95"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Specialty</strong></td>
    <td width="159"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Organization</strong></td>
    <td width="191"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Address</strong></td>
	 <td width="191"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>City</strong></td>
	  <td width="191"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>State</strong></td>
	   <td width="191"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>zip</strong></td>
    <td width="113"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Map</strong></td>
	<td width="113"  bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Direction</strong></td>
  </tr>
<?php
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


	$sql = "SELECT * FROM medic  WHERE id>0".$search_string;
	


$sql_result = mysql_query ($sql, $connection );
echo mysql_error();
// die ('request "Could not execute SQL query" '.$sql);

$i=1;
if (mysql_num_rows($sql_result)>0) {
	
	while ($row = mysql_fetch_assoc($sql_result)) {
	if($i%2==0){
		$color="#FFFFFF";
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
	$url="doctors/map.php?id=".$id."&myloc=".$loc;
	$direction_url="doctors/directions.php?id=".$id."&myloc=".$loc;
	
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
function loadXMLDoc(vals)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("id_city").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","doctors/ajax_city.php?city="+vals,true);
xmlhttp.send();
}
</script>	

</div>