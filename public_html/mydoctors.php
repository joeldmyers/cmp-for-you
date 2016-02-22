<style>
    .stripe-button-el{
        background-color: #204d91 !important; margin: 5px 5px 0 0; background-image:none!important;
    }
    .listbox form{ float: left}
    .stripe-button-el span{ background-image:none!important;background-color: #204d91 !important; font-size:14px!important; font-weight: normal!important}
    .btns{ margin-top: 64px;}
    </style>
<?php
require_once("includes/top.php");
require_once("includes/authentication.php");
$speciality_list = $db->Execute("select", "select  * FROM " . SPECIALITY );
$state_list = $db->Execute("select", "select  sta_id,sta_code,sta_name FROM " . STATES );
$userid = trim($_SESSION["emp_email"]);
if (isset($_SESSION["emp_id"]) && isset($_SESSION["emp_email"]) && !empty($_SESSION["emp_id"]) && !empty($_SESSION["emp_email"])) {
    
} else {

    echo "<script>window.location.href='login.php'</script>";
    exit;
}
$doct_datas = array();
if (isset($_POST['search'])) {
    $search_opt = '1=1';
    if (isset($_POST['doc_name']) && !empty($_POST['doc_name'])) {
        $doc_name = mysql_real_escape_string($_POST['doc_name']);
        //$search_opt.= " AND (`primary_speciality` like '%".$patient_spec."%' OR `secondary_speciality_1` like '%".$patient_spec."%')";
        $search_opt.= " AND (`doctor_pseudoname` like '%".$doc_name."%' OR `first_name` like '%".$doc_name."%' OR `last_name` like '%".$doc_name."%' OR `middle_name` like '%".$doc_name."%' )";
    }
    if (isset($_POST['doc_spec']) && !empty($_POST['doc_spec'])) {
        $doc_spec = mysql_real_escape_string($_POST['doc_spec']);
        $search_opt.= " AND (`primary_speciality` like '%".$doc_spec."%' OR `secondary_speciality_1` like '%".$doc_spec."%' OR `secondary_speciality_2` like '%".$doc_spec."%' OR `secondary_speciality_3` like '%".$doc_spec."%' OR `secondary_speciality_4` like '%".$doc_spec."%' OR `all_secondary_speciality` like '%".$doc_spec."%' )";
    }
    if (isset($_POST['doc_city']) && !empty($_POST['doc_city']))
    {
        $city = trim(mysql_real_escape_string($_POST['doc_city']));
        $search_opt .= " AND `city` = '".trim($city)."'";        
    }if (isset($_POST['doc_state']) && !empty($_POST['doc_state']))
    {
        $state = trim(mysql_real_escape_string($_POST['doc_state']));
        $search_opt .= " AND `doctor_state` = '".$state."'";        
        
        
    }if (isset($_POST['patient_add3']) && !empty($_POST['patient_add3']))
    {
        $zipcode = trim(mysql_real_escape_string($_POST['patient_add3']));
        $search_opt .= " AND LEFT(zipcode,5)='".$zipcode."'";   
    }       
    if(isset($search_opt) && !empty($search_opt) && $search_opt != '1=1')
    {    
       // echo "select * from ".MEDIC. " where $search_opt LIMIT 0,10"; exit;
        //$doct_datas = $db->Execute("select", "select * from ".MEDIC. " where $search_opt LIMIT 0,10");
	$doct_datas = $db->Execute("select", "select * from ".MEDIC. " where $search_opt");     
        
    }
}
if (isset($_POST['did'])) {
    $stripeEmail = $_POST['stripeEmail'];
    $stripeToken = $_POST['stripeToken'];
    $doctor_id = $_POST['did'];
    $transaction_amount = $_POST['stripeAmount']/100;
    $patientid = $_SESSION["emp_id"] ; // $_POST['did'];
     
    $db->Execute("insert","insert into " . TRANSACTIONS . " (transaction_amount,transaction_number,transaction_date,patientid,transaction_status) values ('" . $transaction_amount . "','" . $stripeToken . "','" . date('Y-m-d H:i:s') . "','" . $patientid . "','1')");
   
    
    $doctordata=$db->Execute("select", "select * from ".MEDIC. " where doctor_id='".$doctor_id."'");
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    // Send Mail to doctor
    $to=$doctordata[0]['email'];
    $toName=$doctordata[0]['first_name'];
    $subject='New Consultation Requested';
    $mailmessage= 'A new consultation request has been submitted.<br>';
    $mailmessage .='Consultation Type : '.$_POST['type']."<br>";
    $mailmessage .='Patient Name      : '.$_SESSION['emp_name']."<br>";
    $mailmessage .='Patient Email     : '.$_SESSION['emp_email']."<br>";
    $mailmessage .='Patient Age       : '.$_SESSION['emp_age']." years<br>";
    @mail($to, $subject, $mailmessage, $headers);
    //Send Mail to Patient
    $to=$_SESSION['emp_email'];
    $toName=$_SESSION['emp_name'];
    $subject='Your Request has been submitted Successfully';
    $mailmessage= 'Request for consultation has been submitted. You are charged  $'.$transaction_amount.' for this consultation.<br>';
    $mailmessage .='Consultation Type : '.$_POST['type']."<br>";
    $mailmessage .='Doctor Name       : '.$doctordata[0]['first_name'].' '.$doctordata[0]['last_name']."<br>";
    $mailmessage .='Doctor Email      : '.$doctordata[0]['email']."<br>";
    @mail($to, $subject, $mailmessage, $headers);
    
}
?>
<?php require_once("includes/mheader.php"); ?>
<?php include 'includes/leftsidebar.php'; ?>
<?php if(isset($_POST['sendmessage']) && isset($message)){
    echo "<div class='text-left' style='color:green;'>".$message."</div>";
}?>
<script src="<?=$remotelocation;?>includes/js/jquery.validate.min.js"></script>
<div class="col-md-6 col-xs-12">
    <h2 class="pad_btm20 pad_top10 pad_left10">My Doctors</h2>
    <div id="viewsuccessmessage" style="color:green;margin-left:10px"></div>
    <div class="searchbar">
        <h4><img src="<?= $remotelocation; ?>includes/images/patienticon.png" /><span>Find a Doctor</span></h4>
        <form class="form-horizontal" method="post" name="searchForm" id="searchForm">
            <div class="form-group">
                <div class="col-sm-6">
                    <!--<input type="text" value="<?php echo isset($_POST['doc_name']) ? $_POST['doc_name'] : ''; ?>" name="doc_name" class="form-control" id="inputpatient" placeholder="Enter Doctor Name">-->
                </div>
                <div class="col-sm-6">
                   
                        <!--<select class="form-control" name="doc_spec" id="doc_spec" >

                            <option value="">SELECT SPECIALITY</option>

                            <?php

                            if (isset($speciality_list) && !empty($speciality_list)) {

                                foreach ($speciality_list as $data) {

                                    if(isset($_POST['doc_spec']) && $data['speciality'] == $_POST['doc_spec'] ){

                                        echo '<option value="'.$data['speciality'].'" selected="selected" >'.$data['speciality'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.$data['speciality'].'">'.$data['speciality'].'</option>';

                                    } 

                                }

                            }

                            ?>

                        </select> -->                   
                   
                </div>
                
                <div class="col-sm-4">
                    <!--<select class="form-control" name="doc_state" id="doc_state" onchange="populateCity();">

                            <option value="">Select State</option>

                            <?php

                            if (isset($state_list) && !empty($state_list)) {

                                foreach ($state_list as $data) {

                                    if(isset($_POST['doc_state']) && $data['sta_code'] == $_POST['doc_state'] ){

                                        echo '<option value="'.$data['sta_code'].'" selected="selected" >'.$data['sta_name'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.$data['sta_code'].'">'.$data['sta_name'].'</option>';

                                    } 

                                }

                            }

                            ?>

                        </select>--> 
                    <!--<input type="text" value="<?php echo isset($_POST['patient_add2']) ? $_POST['patient_add2'] : ''; ?>" name="patient_add2" class="form-control" id="inputpatientadd2" placeholder="State">-->
                </div>
                <div class="col-sm-4">
                   <!-- <select class="form-control" name="doc_city" id="doc_city" >

                            <option value="">Select City</option>

                            <?php
if(isset($_POST['doc_state']) &&!empty($_POST['doc_state'])){
     $city_list = $db->Execute("select", "select DISTINCT(city) FROM " . CITYSTATE . " where state = '".trim($_POST['doc_state'])."' order by city asc");

                            if (isset($city_list) && !empty($city_list)) {

                                foreach ($city_list as $data) {

                                    if(isset($_POST['doc_city']) && $data['city'] == $_POST['doc_city'] ){

                                        echo '<option value="'.trim($data['city']).'" selected="selected" >'.$data['city'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.trim($data['city']).'">'.$data['city'].'</option>';

                                    } 

                                }

                            }
                            }

                            ?>

                        </select>-->
                    <!--<input type="text" value="<?php echo isset($_POST['patient_add1']) ? $_POST['patient_add1'] : ''; ?>" name="patient_add1" class="form-control" id="inputpatientadd1" placeholder="City">-->
                </div>
                <div class="col-sm-4">
                    <!--<input type="text" value="<?php echo isset($_POST['patient_add3']) ? $_POST['patient_add3'] : ''; ?>" name="patient_add3" class="form-control" id="inputpatientadd3" maxlength="10" placeholder="Zip Code">-->
                </div>
                <div class="col-sm-2">
                    <!--<input type="submit" name="search" id="search" class="btn btn-default" value="Search" onclick="return validateSearch();"/>-->
                </div>
<!--                 <div class="col-sm-2">
                    <input type="reset" name="reset" style="float: left" id="reset" class="btn btn-default" onClick="window.location.reload()" value="Reset"/>
                </div>-->
            </div>
        </form>
		
		
        <!--- NPI LISTS ---------------->            
        <?php if (isset($doct_datas[0]['doctor_id']) && $doct_datas[0]['doctor_id'] > 0) {?>
           
        <div class="docDetail">
                <?php
                foreach ($doct_datas as $val) {
                     $did = $val['doctor_id'];
                    if (isset($val['gender']) && $val['gender'] == 'M') {

                       
                       
                        
                        echo '<div class="col-sm-12 col-xs-12 listbox">    
                                    <div class="col-sm-4 col-xs-12">
                                    <img src="' . $remotelocation . "includes/images/profilepic3.png" . '" class="img-responsive">';
                        if (isset($val['organization_legal_name']) && !empty($val['organization_legal_name'])) {
                            echo '<h5>' . $val['organization_legal_name'] . '</h5>';
                        } else {
                            echo '<h5>' . $val['first_name'] . ' ' . $val['middle_name'] . ' ' . $val['last_name'] . '</h5>';
                        }

                        echo '</div>';
                    } else {
                        echo '<div class="col-sm-12 col-xs-12 listbox">    
                                    <div class="col-sm-4 col-xs-12">
                                    <img src="' . $remotelocation . "includes/images/profilepic2.png" . '" class="img-responsive">
                                    <h5>' . $val['first_name'] . ' ' . $val['middle_name'] . ' ' . $val['last_name'] . '</h5>
                                    </div>';
                    }
                    $maplink = $remotelocation . "locators/map.php?id=".$val['doctor_id']."&myloc=".$val['city'].", ".$val['doctor_state']."";
                    $directionlink=$remotelocation . "locators/directions.php?id=".$val['doctor_id']."&myloc=".$val['city'].", ".$val['doctor_state']."";										$bookappointment=$remotelocation . "book_appointment.php?id=".$val['doctor_id'];
                    echo '<div class="col-sm-8 col-xs-12 mar_btm20">
                                <ul>';
                    $secondary_specialities='';
                    if (isset($val['secondary_speciality_1']) && !empty($val['secondary_speciality_1'])) {
                       $secondary_specialities .= ','.$val['secondary_speciality_1'];
                    } if (isset($val['secondary_speciality_2']) && !empty($val['secondary_speciality_2'])) {
                        $secondary_specialities .= ','.$val['secondary_speciality_2'];
                    } if (isset($val['secondary_speciality_3']) && !empty($val['secondary_speciality_3'])) {
                       $secondary_specialities .= ','.$val['secondary_speciality_3'];
                    } if (isset($val['secondary_speciality_4']) && !empty($val['secondary_speciality_4'])) {
                       $secondary_specialities .= ','.$val['secondary_speciality_4'];
                    } if (isset($val['all_secondary_speciality']) && !empty($val['all_secondary_speciality'])) {
                        $secondary_specialities .= ','.$val['all_secondary_speciality'];
                    }
                    if (isset($val['primary_speciality']) && !empty($val['primary_speciality'])) {
                        echo '<li><i class="fa fa-angle-right"> PRIMARY SPECIALITY : </i> ' . $val['primary_speciality'] . '</li>';
                    }if (isset($secondary_specialities) && !empty($secondary_specialities)) {
                        echo '<li><i class="fa fa-angle-right"> SECONDARY SPECIALITIES : </i> ' . $secondary_specialities . '</li>';
                    } 

                    echo '<li><i class="fa fa-angle-right"> ADDRESS : </i> ' . $val['city'] . ' , ' . $val['doctor_state'] . ' ' . $val['zipcode'] . '</li>';

                    echo '
                                    </ul>
                         
                                    <div class="btns" style="width:350px"><button type="button" id=' . '"' . $val['doctor_id'] . '"  value=' . '"' . $val['doctor_id'] . '" onclick="return getdata(this.value)"  class="btn btn-default" data-toggle="modal" data-target="#myModal"  style="visibility: visible";>Message</button>																		<a href="'.$bookappointment.'"   class="btn btn-default"   >Appointment</a>									
                                    <a href="#" class="btn btn-default" data-map-link="'.$maplink.'"  data-toggle="modal" data-target="#mapModal" >Map</a>
                                        <a href="#" class="btn btn-default" data-direction-link="'.$directionlink.'"  data-toggle="modal" data-target="#directionModal" >Direction</a>';
                       $email_fee = (isset($val['email_consult_fee']) && !empty($val['email_consult_fee']))?$val['email_consult_fee']*100:0;       
                    $video_fee=(isset($val['video_consult_fee']) && !empty($val['video_consult_fee']))?$val['video_consult_fee']*100:0;
                    
echo $val['email_consult_fee'];				
$person_fee=(isset($val['person_consult_fee']) && !empty($val['person_consult_fee']))?$val['person_consult_fee']*100:0;
                    if($email_fee !=0)
					{
						
						 echo ' 
                        <form method="POST" method="get" action="searchdoctor.php?1=1">                               
                                   <input type="hidden" name="did" id="did" value="'.$did.'" />
  <input type="hidden" name="email" id="email" value="video" />                                  
<input type="hidden" name="stripeAmount" id="stripeAmount" value="'.$email_fee.'" />
                                    <script
                                      src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                      data-key="pk_test_PJ6Yhhbye0R7lR6ZwTKdbtN5"
                                      data-amount="'.$email_fee.'"
                                      data-name="CMforYou"
                                      data-description="A patient profile review fees"
                                      data-label="Email Consultation"
                                      data-image="includes/images/cmlogo.png">
                                    </script>                                                                      
                                    </form>   ';
						
					}
					if($video_fee !=0){
                       
                    echo ' 
                        <form method="POST" method="get" action="searchdoctor.php?1=1">                               
                                   <input type="hidden" name="did" id="did" value="'.$did.'" />
  <input type="hidden" name="type" id="type" value="video" />                                  
<input type="hidden" name="stripeAmount" id="stripeAmount" value="'.$video_fee.'" />
                                    <script
                                      src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                      data-key="pk_test_PJ6Yhhbye0R7lR6ZwTKdbtN5"
                                      data-amount="'.$video_fee.'"
                                      data-name="CMforYou"
                                      data-description="A patient profile review fees"
                                      data-label="Video Consultation"
                                      data-image="includes/images/cmlogo.png">
                                    </script>                                                                      
                                    </form>   ';
                    }
                    if($person_fee !=0){
                        
                    echo '              <form method="POST" method="get" action="searchdoctor.php?1=1">                               
                                   <input type="hidden" name="did" id="did" value="'.$did.'" />
                                       <input type="hidden" name="stripeAmount" id="stripeAmount" value="'.$person_fee.'" />
                                   <input type="hidden" name="type" id="type" value="person" />
                                    <script
                                      src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                      data-key="pk_test_PJ6Yhhbye0R7lR6ZwTKdbtN5"
                                      data-amount="'.$person_fee.'"
                                      data-name="CMforYou"
                                      data-description="A patient profile review fees"
                                      data-label="Person Consultation"
                                      data-image="includes/images/cmlogo.png">
                                    </script>                                                                      
                                    </form>  ';
                    }
                    echo ' 
                            </div> </div>  
                        </div>';
                    echo '<div class=0"clear"></div>';
                    }
                ?>                     
            </div>   
        </div>
<?php } else {
    
//            echo "<div>No doctor found...!!</div>"
    ?>
        <!-- END NPI LISTS ---->                    
    </div><br/><br/>
<div class="col-md-6 col-xs-12" align = 'right' style="margin-left:40%">
<h4><img src="<?= $remotelocation; ?>includes/images/patienticon.png" /><span>Question For Email Consultation</span></h4><br/>
<textarea cols="40" rows="5"></textarea>

</div>
	
<?php } ?>
</div> 
<?php include 'includes/rightsidebar.php'; ?>
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
<?php include 'includes/mfooter.php'; ?>

<script>
$(document).ready(function(){
    $('#mapModal').on('shown.bs.modal', function (e) {
        var href = $(e.relatedTarget).data('map-link');
        $('#mapModal iframe').attr('src',href);   
    });
    $('#mapModal').on('hidden.bs.modal', function (e) {
        $('#mapModal iframe').attr('src','');   
    });

    $('#directionModal').on('shown.bs.modal', function (e) {
        var href = $(e.relatedTarget).data('direction-link');
        $('#directionModal iframe').attr('src',href);   
   });
    $('#directionModal').on('hidden.bs.modal', function (e) {
        $('#directionModal iframe').attr('src','');   
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
</script>
