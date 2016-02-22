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
        $search_opt .=" AND (FIND_IN_SET(".$_POST['doc_spec'].",`speciality`))";
        //$search_opt.= " AND (`primary_speciality` like '%".$patient_spec."%' OR `secondary_speciality_1` like '%".$patient_spec."%')";
        //$search_opt.= " AND (`primary_speciality` like '%".$patient_spec."%' OR `secondary_speciality_1` like '%".$patient_spec."%' OR `secondary_speciality_2` like '%".$patient_spec."%' OR `secondary_speciality_3` like '%".$patient_spec."%' OR `secondary_speciality_4` like '%".$patient_spec."%' OR `all_secondary_speciality` like '%".$patient_spec."%' )";
    }
    if (isset($_POST['patient_add1']) && !empty($_POST['patient_add1']))
    {
        $city = trim(mysql_real_escape_string($_POST['patient_add1']));
        $search_opt .= " AND `city` = '".$city."'";        
    }if (isset($_POST['doc_state']) && !empty($_POST['doc_state']))
    {
        $state = trim(mysql_real_escape_string($_POST['doc_state']));
        $search_opt .= " AND `doctor_state` = '".$state."'";        
        
    }if (isset($_POST['patient_add3']) && !empty($_POST['patient_add3']))
    {
        $zipcode = trim(mysql_real_escape_string($_POST['patient_add3']));
        $search_opt .= " AND `zipcode` = '".$zipcode."'";   
    }       
    if(isset($search_opt) && !empty($search_opt) && $search_opt != '1=1')
    {    
//        "select * from ".MEDIC. " where $search_opt LIMIT 0,10"; exit;
        //$doct_datas = $db->Execute("select", "select * from ".MEDIC. " where $search_opt LIMIT 0,10");
	$doct_datas = $db->Execute("select", "select * from ".MEDIC. " where $search_opt");     
        
    }
}
if (isset($_POST['did'])) {
    $stripeEmail = $_POST['stripeEmail'];
    $stripeToken = $_POST['stripeToken'];
    $doctor_id = $_POST['did'];
    $transaction_amount = 25;
    $patientid = 1;
    $db->Execute("insert","insert into " . TRANSACTIONS . " (transaction_amount,transaction_number,transaction_date,patientid,transaction_status) values ('" . $transaction_amount . "','" . $stripeToken . "','" . date('Y-m-d H:i:s') . "','" . $patientid . "','1')");
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
                    <input type="text" value="<?php echo isset($_POST['doc_name']) ? $_POST['doc_name'] : ''; ?>" name="doc_name" class="form-control" id="inputpatient" placeholder="Enter Doctor Name">
                </div>
                <div class="col-sm-6">
                   
                        <select class="form-control" name="doc_spec" id="doc_spec" >

                            <option value="">Select Speciality</option>

                            <?php

                            if (isset($speciality_list) && !empty($speciality_list)) {

                                foreach ($speciality_list as $data) {

                                    if(isset($_POST['doc_spec']) && $data['speciality_id'] == $_POST['doc_spec'] ){

                                        echo '<option value="'.$data['speciality_id'].'" selected="selected" >'.$data['speciality'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.$data['speciality_id'].'">'.$data['speciality'].'</option>';

                                    } 

                                }

                            }

                            ?>

                        </select>                    
                   
                </div>
                <div class="col-sm-4">
                    <input type="text" value="<?php echo isset($_POST['patient_add1']) ? $_POST['patient_add1'] : ''; ?>" name="patient_add1" class="form-control" id="inputpatientadd1" placeholder="City">
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="doc_state" id="doc_state">

                            <option value="">Select State</option>

                            <?php

                            if (isset($state_list) && !empty($state_list)) {

                                foreach ($state_list as $data) {

                                    if(isset($_POST['doc_state']) && $data['sta_id'] == $_POST['doc_state'] ){

                                        echo '<option value="'.$data['sta_id'].'" selected="selected" >'.$data['sta_name'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.$data['sta_id'].'">'.$data['sta_name'].'</option>';

                                    } 

                                }

                            }

                            ?>

                        </select> 
                    <!--<input type="text" value="<?php echo isset($_POST['patient_add2']) ? $_POST['patient_add2'] : ''; ?>" name="patient_add2" class="form-control" id="inputpatientadd2" placeholder="State">-->
                </div>
                <div class="col-sm-4">
                    <input type="text" value="<?php echo isset($_POST['patient_add3']) ? $_POST['patient_add3'] : ''; ?>" name="patient_add3" class="form-control" id="inputpatientadd3" maxlength="10" placeholder="Zip Code">
                </div>
                <div class="col-sm-2">
                    <input type="submit" name="search" id="search" class="btn btn-default" value="Search" onclick="return validateSearch();"/>
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
                    if (isset($val['gender']) && $val['gender'] == 'M') {

                        $did = $val['id'];
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
                    echo '<div class="col-sm-8 col-xs-12 mar_btm20">
                                <ul>';
                    if (isset($val['primary_speciality']) && !empty($val['primary_speciality'])) {
                        echo '<li><i class="fa fa-user-plus"></i> ' . $val['primary_speciality'] . '</li>';
                    }if (isset($val['secondary_speciality_1']) && !empty($val['secondary_speciality_1'])) {
                        echo '<li><i class="fa fa-user-plus"></i> ' . $val['secondary_speciality_1'] . '</li>';
                    } if (isset($val['secondary_speciality_2']) && !empty($val['secondary_speciality_2'])) {
                        echo '<li><i class="fa fa-user-plus"></i> ' . $val['secondary_speciality_2'] . '</li>';
                    } if (isset($val['secondary_speciality_3']) && !empty($val['secondary_speciality_3'])) {
                        echo '<li><i class="fa fa-user-plus"></i> ' . $val['secondary_speciality_3'] . '</li>';
                    } if (isset($val['secondary_speciality_4']) && !empty($val['secondary_speciality_4'])) {
                        echo '<li><i class="fa fa-user-plus"></i> ' . $val['secondary_speciality_4'] . '</li>';
                    } if (isset($val['all_secondary_speciality']) && !empty($val['all_secondary_speciality'])) {
                        echo '<li><i class="fa fa-user-plus"></i> ' . $val['all_secondary_speciality'] . '</li>';
                    }

                    echo '<li><i class="fa fa-location-arrow"></i> ' . $val['city'] . ' , ' . $val['doctor_state'] . ' ' . $val['zipcode'] . '</li>';

                    echo '
                                    </ul>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                            <div class="col-sm-4"></div>
                                   
                                    <div class="col-sm-8">
                                    <button type="button" id=' . '"' . $val['doctor_id'] . '"  value=' . '"' . $val['doctor_id'] . '" onclick="return getdata(this.value)"  class="btn btn-default" data-toggle="modal" data-target="#myModal"  style="visibility: visible";>Send Message</button>
                                    <a href="#" class="btn btn-default" data-map-link="'.$maplink.'"  data-toggle="modal" data-target="#mapModal" >Map</a>
                                        <button type="button" id=' . '"d' . $val['doctor_id'] . '" class="btn btn-default"   >Direction</button>
                                </div>
                            </div>   
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

<div id="mapModal" class="modal mappop fade" role="dialog" style="padding-top: 10%">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">

                                <div class="modal-body">
                                    <iframe src="" style="zoom:0.60"   frameborder="0"></iframe>
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
    });
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