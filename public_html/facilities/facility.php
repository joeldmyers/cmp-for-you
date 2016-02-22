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
<br/>
		<section class="appointment-sec text-center" style="background-color:#f3f3f3"> 
        <div class="container">
            <h1 class="h1">Facilities Search</h1>
            <div class="row" style="width: 100%; padding-right: 6%; padding-left: 6%; text-align: center;">
				<div class="" style="width: 100%; text-align: center;">
                <div class="appointment-form clearfix">
                    <form id="form1" name="form1" method="post" action="facility.php">
						<input type="hidden" name="page" value=""/>
					<table width="100%">
					<tr>
						<td width="50%"> <input type="text" name="name" style='font-weight:bold'  placeholder="Facility Name" value="<?php echo @stripcslashes($_REQUEST["name"]); ?>"></td>
						<td width="50%">
						  <input type="text" name="zipcode" style='font-weight:bold'  placeholder="Zipcode" value="<?php echo @stripcslashes($_REQUEST["zipcode"]); ?>">
						</td>
					</tr>
					<tr>
						
                      <td> 
                       <select name="state" onchange="loadXMLDoc(this.value)" style='font-weight:bold'>
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
					<td>
					<span id="id_city" >
						<select name="city" style="font-weight:bold">
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

<table width="100%" border="1" cellspacing="0" cellpadding="4" style="margin-top:20px">
  <tr>
	 <td width="90" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px">&nbsp;</td>
    <td width="90" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Name</strong></td>
    <td width="95" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Address</strong></td>
    <td width="159" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>City</strong></td>
    <td width="191" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>State</strong></td>
	 <td width="191" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Zip</strong></td>
	 <td width="191" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Phone</strong></td>
	  <td width="191" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Type</strong></td>
    <td width="113" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Map</strong></td>
	<td width="113" bgcolor="#74B1D2" align="center" style="color:white;font-size:16px"><strong>Direction</strong></td>
  </tr>
<?php
$name_search=$_REQUEST["name"];
$city_search=$_REQUEST["city"];
$state_search=$_REQUEST["state"];
$zipcode_search=$_REQUEST["zipcode"];
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

$sql = "SELECT count(facility_id) FROM facilities  WHERE facility_id>0".$search_string;
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
echo $left_rec."----------".$rec_count."---".$rec_limit;

$search="&name=$name_search&city=$city_search&state=$state_search&zipcode=$zipcode_search";
$search_string .=  " LIMIT $offset, $rec_limit";

	$sql = "SELECT * FROM facilities  WHERE facility_id>0".$search_string;
	

$sql_result = mysql_query ($sql, $connection );
echo mysql_error();



$i=$offset+1;
if (mysql_num_rows($sql_result)>0) {
	while ($row = mysql_fetch_assoc($sql_result)) {
		if($i%2==0){
			$color="#FFFFFF";
		} else {
			$color="#FFFFFF";
		}
		$id=$row["facility_id"];
		$loc=$row["facility_city"].', '.$row["facility_state"];
		$url="facilities/map.php?id=".$id."&myloc=".$loc;
		$direction_url="facilities/directions.php?id=".$id."&myloc=".$loc;
	
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
xmlhttp.open("GET","facilities/ajax_city.php?city="+vals,true);
xmlhttp.send();
}
</script>	

</div>