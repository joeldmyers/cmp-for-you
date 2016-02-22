<?php
error_reporting(0);
include("config.php");
?>
 <link href="../css/bootstrap.css" rel="stylesheet" id="id_bootstrap">
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
</style>

<div class="container">
	  	<section class="appointment-sec text-center" style="background-color:#f3f3f3"> 
        <div class="container">
            <h1 class="h1">Facilities Search</h1>
            <div class="row" style="width: 100%; padding-right: 6%; padding-left: 6%; text-align: center;">
				<div class="" style="width: 100%; text-align: center;">
                <div class="appointment-form clearfix">
                    <form id="form1" name="form1" method="post" action="">
					<table width="100%">
					<tr>
						<td width="50%"> <input type="text" name="name"  placeholder="Facility Name" value="<?php echo @stripcslashes($_REQUEST["name"]); ?>"></td>
						<td width="50%">
						  <input type="text" name="zipcode"  placeholder="Zipcode" value="<?php echo @stripcslashes($_REQUEST["zipcode"]); ?>">
						</td>
					</tr>
					<tr>
						
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
					<td><select name="city">
								<option value="">Select City</option>
								<?php
									$sql1 = "SELECT * FROM  citystate WHERE city!='' GROUP BY city ORDER BY city";
									$sql_result1 = mysql_query ($sql1, $connection ) or die ('request "Could not execute SQL query" '.$sql1);
									echo mysql_error();
									while ($row1 = mysql_fetch_assoc($sql_result1)) {
										echo "<option value='".$row1["city"]."'".($row1["city"]==$_REQUEST["city"] ? " selected" : "").">".$row1["city"]."</option>";
									}
								?>
								</select></td>
					
					
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
<table width="100%" border="1" cellspacing="0" cellpadding="4" style="margin-top:20px">
  <tr>
	 <td width="90" bgcolor="#CCCCCC">&nbsp;</td>
    <td width="90" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Name</strong></td>
    <td width="95" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Address</strong></td>
    <td width="159" bgcolor="#CCCCCC" style='color:white' align="center"><strong>City</strong></td>
    <td width="191" bgcolor="#CCCCCC" style='color:white' align="center"><strong>State</strong></td>
	 <td width="191" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Zip</strong></td>
	 <td width="191" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Phone</strong></td>
	  <td width="191" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Type</strong></td>
    <td width="113" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Map</strong></td>
	<td width="113" bgcolor="#CCCCCC" style='color:white' align="center"><strong>Direction</strong></td>
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
		if($i%2==0){
			$color="#EDF3FE";
		} else {
			$color="#FFFFFF";
		}
		$id=$row["facility_id"];
		$loc=$row["facility_city"].', '.$row["facility_state"];
		$url="map.php?id=".$id."&myloc=".$loc;
		$direction_url="directions.php?id=".$id."&myloc=".$loc;
	
?>
  <tr bgcolor="<?php echo $color; ?>">
	 <td align="right"><?php echo $i; ?></td>
    <td align="center"><a class="various" data-fancybox-type="iframe" href="<?php echo $url; ?>" style='color:#646464;cursor:pointer'><?php echo $row["facility_name"]; ?></a></td>
    <td align="center"><?php echo $row["facility_address"]; ?></td>
    <td align="center"><?php echo $row["facility_city"]; ?></td>
    <td align="center"><?php echo $row["facility_state"]; ?></td>
	<td align="center"><?php echo $row["facility_zip"]; ?></td>
	<td align="center"><?php echo $row["facility_phone"]; ?></td>
	<td align="center"><?php echo $row["facility_type"]; ?></td>
   <!-- <td><?php echo "<a href='".$url."' target='_blank'>Map</a>"; ?></td>-->
	<td align="center"><a class="various" data-fancybox-type="iframe" href="<?php echo $url; ?>" style='color:#2B96CC'>Map</a></td>
	 <td align="center"><a class="various" data-fancybox-type="iframe" href="<?php echo $direction_url; ?>" style='color:#2B96CC'>Direction</a></td>
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
</div>