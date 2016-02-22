<?php
require_once("includes/top.php");
require_once("includes/doc_authentication.php");
//echo "<pre>"; print_r($_SESSION[emp_id]);

$headers="";
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
$mess="";
$mess="";
$speciality_list = $db->Execute("select", "select  * FROM " . SPECIALITY );
$state_list = $db->Execute("select", "select  sta_id,sta_code,sta_name FROM " . STATES );
$userid = trim($_SESSION["emp_email"]);

$userid1 = trim($_SESSION["emp_id"]);

$limit=40;
if (@$_REQUEST['page'] == 0 || !isset($_REQUEST['page'])){ $page = 1;
	$stpoint=0;
} else {
	$page = $_REQUEST['page'];
	$stpoint=($page-1)*$limit;
}
$doct_datas = array();
/*if (isset($_REQUEST['search'])) {

	$filter_string="&search=search";
    $search_opt = '1=1';
	if (isset($_REQUEST['doc_name']) && !empty($_REQUEST['doc_name'])) {
		$doc_name = mysql_real_escape_string($_REQUEST['doc_name']);
		//$search_opt.= " AND (`primary_speciality` like '%".$patient_spec."%' OR `secondary_speciality_1` like '%".$patient_spec."%')";
		$search_opt.= " AND (`doctor_pseudoname` like '%".$doc_name."%' OR `first_name` like '%".$doc_name."%' OR `last_name` like '%".$doc_name."%' OR `middle_name` like '%".$doc_name."%' )";
		$filter_string .="&doc_name=".$_REQUEST['doc_name'];
	}
	if (isset($_REQUEST['doc_spec']) && !empty($_REQUEST['doc_spec'])) {
		$doc_spec = mysql_real_escape_string($_REQUEST['doc_spec']);
		$search_opt.= " AND (`primary_speciality` like '%".$doc_spec."%' OR `secondary_speciality_1` like '%".$doc_spec."%' OR `secondary_speciality_2` like '%".$doc_spec."%' OR `secondary_speciality_3` like '%".$doc_spec."%' OR `secondary_speciality_4` like '%".$doc_spec."%' OR `all_secondary_speciality` like '%".$doc_spec."%' )";
		$filter_string .="&doc_spec=".$_REQUEST['doc_spec'];
	}
	if (isset($_REQUEST['doc_city']) && !empty($_REQUEST['doc_city']))
	{
		$city = trim(mysql_real_escape_string($_REQUEST['doc_city']));
		$search_opt .= " AND `city` = '".trim($city)."'";   
		$filter_string .="&doc_city=".$_REQUEST['doc_city'];	
	}
	if (isset($_REQUEST['doc_state']) && !empty($_REQUEST['doc_state']))
	{
		$state = trim(mysql_real_escape_string($_REQUEST['doc_state']));
		$search_opt .= " AND `doctor_state` = '".$state."'"; 
		$filter_string .="&doc_state=".$_REQUEST['doc_state'];		
		
		
	}
	if (isset($_REQUEST['patient_add3']) && !empty($_REQUEST['patient_add3']))
	{
		$zipcode = trim(mysql_real_escape_string($_REQUEST['patient_add3']));
		$search_opt .= " AND LEFT(zipcode,5)='".$zipcode."'";  
		$filter_string .="&patient_add3=".$_REQUEST['patient_add3'];	
	} 	
	 if (isset($_REQUEST['searchTextField']) && !empty($_REQUEST['searchTextField'])) {
		$filter_string .="&searchTextField=".$_REQUEST['searchTextField'];	
		
		$address=$_REQUEST['searchTextField'];
		$request_url = "https://maps.googleapis.com/maps/api/geocode/xml?key=AIzaSyC8axTAyahJO39IJjHuUYrXJYl7McXkIhw&address=".$address."&sensor=true";
		$xml = simplexml_load_file($request_url) or die("url not loading");
		$status = $xml->status;
	  if ($status=="OK") {
		  $Lat = $xml->result->geometry->location->lat;
		  $Lon = $xml->result->geometry->location->lng;
		
	 } else {
		$Lat="";
		$Lon="";
	 }
	 $miles=$_REQUEST['miles'];
	 $filter_string .="&miles=".$_REQUEST['miles'];	
	if($search_opt != '1=1'){
		echo  $sql_result="SELECT *,
	( 3959 * acos( cos( radians($Lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($Lon) ) + sin( radians($Lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM medic1  HAVING distance $miles  AND  $search_opt  ORDER BY distance";

	} else {
		echo $sql_result="SELECT *,
	( 3959 * acos( cos( radians($Lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($Lon) ) + sin( radians($Lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM medic1  HAVING distance $miles   ORDER BY distance ";

	}
	$doct_datas = $db->Execute("select", $sql_result);   

	
	} else {
		
		
		if(isset($search_opt) && !empty($search_opt) && $search_opt != '1=1')
		{    
		$sql_result="select * from ".MEDIC. " where $search_opt";
		$doct_datas = $db->Execute("select", "select * from ".MEDIC. " where $search_opt");     
        
		}	
 }*/		
 $sql_result="select * from tbl_doctor_social_network where friend_id='".$userid1."' AND status='ACCEPT'";
 $doct_datas = $db->Execute("select", "select * from tbl_doctor_social_network where friend_id='".$userid1."'  AND status='ACCEPT'"); 
//echo "<pre>"; print_r($doct_datas);     

/********************************************************* paging Count *****************************/ 
$total_pages= count($doct_datas);
$adjacents = 3;

//$string=$filter_string;
/***********************************************************************************/


	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;	
	$pagination = "";
	
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"social-network-pending-request.php?page=$prev".$string."\"><< prev</a>";
		else
			$pagination.= "<span class=\"disabled\"><< prev</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"social-network-pending-request.php?page=$counter".$string."\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>&nbsp;";
					else
						$pagination.= "<a href=\"social-network-pending-request.php?page=$counter".$string."\">$counter</a>";					
				}
				$pagination.= " ... ";
				$pagination.= "<a href=\"social-network-pending-request.php?page=$lpm1".$string."\">$lpm1</a>";
				$pagination.= "<a href=\"social-network-pending-request.php?page=$lastpage".$string."\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"social-network-pending-request.php?page=1".$string."\">1</a>";
				$pagination.= "<a href=\"social-network-pending-request.php?page=2".$string."\">2</a>";
				$pagination.= " ... ";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>&nbsp;";
					else
						$pagination.= "<a href=\"social-network-pending-request.php?page=$counter".$string."\">$counter</a>";					
				}
				$pagination.= " ... ";
				$pagination.= "<a href=\"social-network-pending-request.php?page=$lpm1".$string."\">$lpm1</a>";
				$pagination.= "<a href=\"social-network-pending-request.php?page=$lastpage".$string."\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"social-network-pending-request.php?page=1".$string."\">1</a>";
				$pagination.= "<a href=\"social-network-pending-request.php?page=2".$string."\">2</a>";
				$pagination.= " ... ";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>&nbsp;";
					else
						$pagination.= "<a href=\"social-network-pending-request.php?page=$counter".$string."\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"social-network-pending-request.php?page=$next".$string."\">next >></a>";
		else
			$pagination.= "<span class=\"disabled\">next >></span>&nbsp;";
			$pagination.= "</div>\n";		
	}
	
	

	$sql_result=$sql_result." LIMIT $stpoint, $limit";

	$doct_datas = $db->Execute("select", $sql_result);    

	//$doct_datas = $db->Execute("select", $sql_result);
//}	
/*****************************************************************************************************/

//echo "<pre>"; print_r($doct_datas);

?>
<style>
    .stripe-button-el{
        background-color: #204d91 !important; margin: 5px 5px 0 0; background-image:none!important;
    }
    .listbox form{ float: left}
    .stripe-button-el span{ background-image:none!important;background-color: #204d91 !important; font-size:14px!important; font-weight: normal!important}
    .btns{ margin-top: 64px;}
    </style>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>
<?php if(isset($_REQUEST['sendmessage']) && isset($message)){
    echo "<div class='text-left' style='color:green;'>".$message."</div>";
}?>
<link href="<?=$remotelocation;?>css/pagination.css" type="text/css" rel="stylesheet"/>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="<?=$remotelocation;?>includes/js/bootstrap.min.js"></script>
  <script src="<?=$remotelocation;?>includes/js/jquery.validate.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
<div class="col-md-9 col-xs-12">
    <h2 class="pad_btm20 pad_top10 pad_left10">Doctors in My Network</h2>
	<?php
		if($mess==1){
	?>
	  <div style="color:green;margin-bottom:10px"><strong>Payment done successfully</strong></div>
	<?php
		}
	?>	
    <div id="viewsuccessmessage" style="color:green;margin-left:10px"></div>
    <div class="searchbar">
        <h4><img src="<?= $remotelocation; ?>includes/images/patienticon.png" /><span>My Network</span></h4>
        

        <!--- NPI LISTS ---------------->            
        <?php if (isset($doct_datas[0]['user_id']) && $doct_datas[0]['user_id'] > 0) {?>
           
        <div class="docDetail">
                <?php
                foreach ($doct_datas as $val) {
                     $did = $val['user_id'];
					 
					 // User Details --- Sam -- WEBICULES TECHNOLOGY --
					 $sql = "SELECT * FROM medic1 WHERE doctor_id='".$did."'";
					 $query = mysql_query($sql);
					 $res = mysql_fetch_array($query);
					 
					 
					 $doctorlink =  "locators/doctor_details.php?id=".$res['doctor_id'];
                    if (isset($res['gender']) && $res['gender'] == 'M') {

                       
                       
                        
                        echo '<div class="col-sm-3 col-xs-12">   
						 <div class="listbox">
                                    
                          <a href="#" class="btn btn-default" data-doctor-link="'.$doctorlink.'"  data-toggle="modal" data-target="#doctorModal" style=" background-color:#F7F7F7;border:0px; width:100%; min-height:180px;"><img src="' . $remotelocation . "includes/images/profilepic3.png" . '" class="">';
                        if (isset($res['organization_legal_name']) && !empty($res['organization_legal_name'])) {
                            echo '<h5 style="color:black">' . $res['organization_legal_name'] . '</h5></a>';
                        } else {
                            echo '<h5  style="color:black">' . $res['first_name'] . ' ' . $res['middle_name'] . ' ' . $res['last_name'] . '</h5></a>';
                        }

                        echo '';
                    } else {
                        echo '<div class="col-sm-3 col-xs-12">  
							 <div class="listbox">
                                    
                                      <a href="#" class="btn btn-default" data-doctor-link="'.$doctorlink.'"  data-toggle="modal" data-target="#doctorModal" style=" background-color:#F7F7F7;border:0px; width:100%; min-height:180px; "><img src="' . $remotelocation . "includes/images/profilepic2.png" . '" class="">
                                    <h5  style="color:black">' . $res['first_name'] . ' ' . $res['middle_name'] . ' ' . $res['last_name'] . '</h5></a>
                                    ';
                    }
                    $maplink ="locators/map.php?id=".$res['doctor_id']."&myloc=".$res['city'].", ".$res['doctor_state']."";
                    $directionlink=$remotelocation . "locators/directions.php?id=".$res['doctor_id']."&myloc=".$res['city'].", ".$res['doctor_state']."";
					$bookappointment=$remotelocation . "doctor_book_appointment.php?mess=success&type=doctor&id=".$res['doctor_id'];
				
				   echo '<div class="col-sm-12 mar_btm20">
                                <ul class="doc-details">';
                    $secondary_specialities='';
                    if (isset($res['secondary_speciality_1']) && !empty($res['secondary_speciality_1'])) {
                       $secondary_specialities .= ','.$res['secondary_speciality_1'];
                    } if (isset($res['secondary_speciality_2']) && !empty($res['secondary_speciality_2'])) {
                        $secondary_specialities .= ','.$res['secondary_speciality_2'];
                    } if (isset($res['secondary_speciality_3']) && !empty($res['secondary_speciality_3'])) {
                       $secondary_specialities .= ','.$res['secondary_speciality_3'];
                    } if (isset($res['secondary_speciality_4']) && !empty($res['secondary_speciality_4'])) {
                       $secondary_specialities .= ','.$res['secondary_speciality_4'];
                    } if (isset($res['all_secondary_speciality']) && !empty($res['all_secondary_speciality'])) {
                        $secondary_specialities .= ','.$res['all_secondary_speciality'];
                    }
                    if (isset($res['primary_speciality']) && !empty($res['primary_speciality'])) {
                        echo '<li><i class="fa fa-angle-right"> PRIMARY SPECIALITY : </i> ' . $res['primary_speciality'] . '</li>';
                    }if (isset($secondary_specialities) && !empty($secondary_specialities)) {
                        echo '<!--li><i class="fa fa-angle-right"> SECONDARY SPECIALITIES : </i> ' . $secondary_specialities . '</li-->';
                    } 

                    echo '<li><i class="fa fa-angle-right"> ADDRESS : </i> ' . $res['city'] . ' , ' . $res['doctor_state'] . ' ' . $res['zipcode'] . '</li>';

                    echo '
                                    </ul>
                         
                                    <div class="btns invite-btns" style="width:100%; text-align:center;">';
									
                    
          
                    echo ' 
                            </div> </div>  </div>
                        </div>';
                    echo '<div class=0"clear"></div>';
                    }
                ?>                     
            </div>  
				<div style="clear:both"></div>	
			<?php echo $pagination; ?>
        </div>
<?php } else {
    
//            echo "<div>No doctor found...!!</div>"
    ?>
        <!-- END NPI LISTS ---->                    
    </div>                    
<?php } ?>
</div> 
<?php //include 'includes/rightsidebar.php'; ?>
</div>   </div> 
                    <div id="myModal" class="modal fade" role="dialog" style="padding-top: 10%">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">

                                <div class="modal-body">
                                    <form action="" method="post" enctype="multipart/form-data" id="changepassword" name="changepassword" class="" > 
                                        <input type="hidden" name="action" value="_setpass">
                                        <input type="hidden" name="recevier_id" id="recevier_id"  />
                                        <input type="hidden" name="sender_id" id="sender_id" value="<?php echo $_SESSION["emp_id"] ?>">
                                        <div  class="form-group">
                                            <input id="subject" type="text" class="form-control required" name="subject" placeholder="Enter Subject" />
                                        </div>

                                        <div  class="form-group">
                                            <textarea rows="5" cols="65" id="message" name="message" class="form-control required"></textarea>
                                        </div>
                                        <!--                            <div style="float:right; font-size:12px; position: relative;"><a href="#">Forgot password?</a></div>-->                               

                                        <div class="form-group2">
                                            <input type="submit" name="sendmessage" class="btn btn-primary" value="Send Message" id="sendmessage" />
                                        </div> 
                                    </form>  
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"  id="close" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>  

                    <div id="mapModal" class="modal  fade" role="dialog" style="padding-top: 10%">
                        <div class="modal-dialog mappop">

                            <!-- Modal content-->
                            <div class="modal-content">

                                <div class="modal-body">
                                    <iframe src="" style="zoom:0.60; width:100%; height: 550px"   frameborder="0"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"  id="close" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>  
                    <div id="directionModal" class="modal  fade" role="dialog" style="padding-top: 10%">
                        <div class="modal-dialog mappop">

                            <!-- Modal content-->
                            <div class="modal-content">

                                <div class="modal-body">
                                    <iframe src="" style="zoom:0.60; width:100%; height: 700px"   frameborder="0"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"  id="close" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
					<div id="doctorModal" class="modal  fade" role="dialog" style="padding-top: 10%">
                        <div class="modal-dialog doctorpop" style="width:70%">

                            <!-- Modal content-->
                            <div class="modal-content">

                                <div class="modal-body">
                                    <iframe src="" style="zoom:0.60; width:100%; height: 350px"   frameborder="0"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"  id="close" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div> 					
<?php include 'includes/mfooter.php'; ?>

<script>
$(document).ready(function(){
  
	
	$('#doctorModal').on('shown.bs.modal', function (e) {
        var href = $(e.relatedTarget).data('doctor-link');
        $('#doctorModal iframe').attr('src',href);   
    });
    $('#doctorModal').on('hidden.bs.modal', function (e) {
        $('#doctorModal iframe').attr('src','');   
    });
});

 function populateCity(){
    var state =   $('#doc_state').val();
     $.ajax({
            type: "POST",
            url: '<?php echo $remotelocation."populatecity.php";?>',
            data: {
                state_id:state
            },
            success: function(data) {
                 $('#doc_city').empty();
                $('#doc_city').append(data);
                }
        });
    }
        function getdata(id) {
            $('#recevier_id').val(id);
        }
</script>
<script type="text/javascript" >
$(function() {
$("#sendmessage").click(function() {
var receiver = $("#recevier_id").val();
var sender = $("#sender_id").val();
var subject = $("#subject").val();
var message = $("#message").val();
var action = "sendmessage";
var dataString = 'receiver='+ receiver + '&sender=' + sender + '&subject=' + subject + '&message=' + message + '&action=' +action;
if(subject == '' || message == ''){
   alert("All fields are required");
   return false;
}else{
$.ajax({
type: "POST",
url: "<?=$remotelocation."getajaxdata.php"?>",
data: dataString,
success: function(data){
  if(data){
     
      $('#myModal').modal('hide');
      $('#viewsuccessmessage').append(data);
  }
}
});

return false;
}
});
});
function validateSearch()
{
        if(document.getElementById("inputpatient").value == '' && document.getElementById("inputpatientadd1").value == '' && document.getElementById("inputpatientadd2").value == '' && document.getElementById("inputpatientadd3").value == '')
        {
           alert("Please enter the speciality or search location..!!");
           return false;
        }   
}
var geocoder, map, marker;
function initialize() {
 geocoder = new google.maps.Geocoder();
    var input = document.getElementById('searchTextField');
    var options = {componentRestrictions: {country: 'us'}};
                
    new google.maps.places.Autocomplete(input, options);
}
            
google.maps.event.addDomListener(window, 'load', initialize);
function validate(vals) {
 
    var address = document.getElementById('searchTextField').value;
    geocoder.geocode({'address': address }, function(results, status) {
      switch(status) {
        case google.maps.GeocoderStatus.OK:
        $("#searchTextField").css('border','2px solid green');
          break;
        case google.maps.GeocoderStatus.ZERO_RESULTS:
   
           $("#searchTextField").css('border','2px solid red');
          break;
        default:
   
           $("#searchTextField").css('border','2px solid red');
      }
    });
  }

</script>
