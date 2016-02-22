<?php
require_once("includes/top.php");
require_once("includes/header.php");
global $db;
$message = '';
$me=0;
$message_fast="";
$doctor_id="";
$state_list = $db->Execute("select", "select  sta_id,sta_code,sta_name FROM " . STATES );
if (isset($_POST['continuebtn_update'])) {
	 if (isset($_POST['doctor_id']) && $_POST['doctor_id']!='') {
		$firstname = trim($_POST['fname']);
        $lastname = trim($_POST['lname']);
		$city = trim($_POST['city']);
        $zipcode = trim($_POST['zipcode']);
        $email = trim($_POST['email']);
        $pseudoname = trim($_POST['pseudoname']);
        $gender = trim($_POST['gender']);
        $password = trim(md5($_POST['password1']));
        $repassword = trim(md5($_POST['repassword1']));
		$doctor_id = trim($_POST['doctor_id']);
		 if ($password == $repassword) {
	    $isUserExist = $db->Execute("select", "select doctor_id,first_name,last_name,email,doctor_pseudoname,primary_speciality from " . MEDIC . " where  email = '" . $email . "' OR doctor_pseudoname = '" . $pseudoname . "'");
            $isPatientExist = $db->Execute("select","select patient_id from ".PATIENTS." WHERE UPPER(patient_email) = '".strtoupper($email)."' OR UPPER(patient_pseudoname) = '".$pseudoname."'");
            if(isset($isUserExist[0]['doctor_id']) && $isUserExist[0]['doctor_id'] > 0){
				$doctor_id = "";
                $message_fast = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Entered email address or pseduo name already taken , Try again.</div>"; 
            }elseif(isset($isPatientExist[0]['patient_id']) && $isPatientExist[0]['patient_id'] > 0){
				$doctor_id = "";
                $message_fast = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Entered email address or pseduo name already taken , Try again.</div>"; 
            } else {
			
	
				$updatedb ="UPDATE " . MEDIC . " SET doctor_pseudoname='" . trim(mysql_real_escape_string($pseudoname)) . "',password='" . trim(mysql_real_escape_string($password)) . "',email='" . trim(mysql_real_escape_string($email)) . "',is_verified=1,system_usertype='DOCTOR' WHERE doctor_id='$doctor_id'";
				$res=mysql_query($updatedb);	
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= 'From: Support <noreply@cmpforyou.com>' . "\r\n";
                $headers .= "X-Priority: 1\r\n"; 
                $subject = "Thanks From Cmforyou";
                $mailmessage = "<p>Thank you for register as a doctor/nurse.</p>";
                $mailmessage.= "<p>You have recieved a verification link. Just copy link and paste it in browser or either click on the verify button and proceed forward to complete your information.</p>";
                $mailmessage .= "<p><a href=" . $remotelocation . 'verifydoctoremail.php?token=' . base64_encode($email) . " target='_blank'>".$remotelocation."verifydoctoremail.php?token=".base64_encode($email)."</a></p><br/>";
                $mailmessage .= "<p><a href=" . $remotelocation . 'verifydoctoremail.php?token=' . base64_encode($email) . " target='_blank'><button style='align:center;' type='submit' id='button' name='verify' class='btn-primary'>Verify</button></a></p>";
                $mailmessage .= "<p><br /></p>";
                $mailmessage .= "<p>Best Regards</p>";
                $mailmessage .= "<p>Cmforyou</p>";
                @mail($email, $subject, $mailmessage, $headers);
                $_POST = array();
                //$message = " <div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Success!</strong> Thank you for register with us , a confirmation link send on your email address..!!</div>";
				$message_fast = " <div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Success!</strong> Thank you for registering with us. Click on confirmation link sent to your email address.</div>";

		   }
        } else {
			$doctor_id = "";
            $message_fast = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Password must be same.</div>";
        }
	 }

}
if (isset($_POST['continuebtn_fast'])) {
	 if ($_POST['action'] == "_docreg_fast") {
		$state_fast = trim($_POST['state_fast']);
        $city_first = trim($_POST['city_first']);
		$fname_fast = trim($_POST['fname']);
        $zipcode_fast = trim($_POST['zipcode']);
        $lname_fast = trim($_POST['lname']);
        $phoneno_fast = trim($_POST['phoneno_fast']);
        $dob_fast = trim($_POST['dob_fast']);
		
		$sql_check="select doctor_id,first_name,last_name,zipcode,doctor_telephone,doctor_dob,country,city from " . MEDIC . " where doctor_pseudoname='' AND password=''";
		if($fname_fast){
			$sql_check .=" AND first_name='$fname_fast'";
		}
		if($lname_fast){
			$sql_check .=" AND last_name='$lname_fast'";
		}
		if($zipcode_fast){
			$sql_check .=" AND zipcode='$zipcode_fast'";
		}
		if($state_fast){
			$sql_check .=" AND doctor_state='$state_fast'";
		}
		
		if($city_fast){
			$sql_check .=" AND city='$city_fast'";
		}
		if($phoneno_fast){
			$sql_check .=" AND (doctor_telephone='$phoneno_fast' OR  doctor_telephone='') ";
		}
		$sql_check .=" LIMIT 0,1";
		
		$isdbExist = $db->Execute("select", $sql_check );
		 if(isset($isdbExist[0]['doctor_id']) && $isdbExist[0]['doctor_id'] > 0){
		
			$country_fast = $isdbExist[0]['country'];
			$city_fast =$isdbExist[0]['city'];
			$fname_fast = $isdbExist[0]['first_name'];
			$zipcode_fast = $isdbExist[0]['zipcode'];
			$lname_fast = $isdbExist[0]['last_name'];
			$doctor_id=$isdbExist[0]['doctor_id'];
			if($isdbExist[0]['doctor_telephone']==0){
				$phoneno_fast="";
			} else {
				$phoneno_fast =$isdbExist[0]['doctor_telephone'];
			}
			if($isdbExist[0]['doctor_telephone']=='0000-00-00'){
				$dob_fast="";
			} else {
				$dob_fast = $isdbExist[0]['doctor_dob'];
			}
			
			
			$me=1;
		 
		 } else {
			$message_fast = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Sorry!</strong>  we did not found any records on our data, please continue with regular registration.</div>";
			$me=0;
		 
		 }
       
	 }


}



if (isset($_POST['continuebtn'])) {
    if ($_POST['action'] == "_docreg") {
        $firstname = trim($_POST['fname']);
        $lastname = trim($_POST['lname']);
		$city = trim($_POST['city']);
        $zipcode = trim($_POST['zipcode']);
        $email = trim($_POST['email']);
        $pseudoname = trim($_POST['pseudoname']);
        $gender = trim($_POST['gender']);
        $password = trim(md5($_POST['password']));
        $repassword = trim(md5($_POST['repassword']));
        if ($password == $repassword) {
	    $isUserExist = $db->Execute("select", "select doctor_id,first_name,last_name,email,doctor_pseudoname,primary_speciality from " . MEDIC . " where  email = '" . $email . "' OR doctor_pseudoname = '" . $pseudoname . "'");
            $isPatientExist = $db->Execute("select","select patient_id from ".PATIENTS." WHERE UPPER(patient_email) = '".strtoupper($email)."' OR UPPER(patient_pseudoname) = '".$pseudoname."'");
            if(isset($isUserExist[0]['doctor_id']) && $isUserExist[0]['doctor_id'] > 0){
                $message = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Entered email address or pseduo name already taken , Try again.</div>"; 
            }elseif(isset($isPatientExist[0]['patient_id']) && $isPatientExist[0]['patient_id'] > 0){
                $message = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Entered email address or pseduo name already taken , Try again.</div>"; 
            } else {
			
	/*************************************************************************************************/	/* $isdbExist = $db->Execute("select", "select doctor_id,first_name,last_name,email,doctor_pseudoname,primary_speciality from " . MEDIC . " where  first_name = '" . $firstname . "' AND  last_name = '" . $lastname . "' AND zipcode='" . $zipcode . "' ");	
	
	if(isset($isdbExist[0]['doctor_id']) && $isdbExist[0]['doctor_id'] > 0){
		$ids=$isdbExist[0]['doctor_id'];
		$updatedb ="UPDATE " . MEDIC . " SET doctor_pseudoname='" . trim(mysql_real_escape_string($pseudoname)) . "',password='" . trim(mysql_real_escape_string($password)) . "',email='" . trim(mysql_real_escape_string($email)) . "',is_verified=1,system_usertype='DOCTOR' WHERE doctor_id='$ids'";
		$res=mysql_query($updatedb);	
	} else {	
			$db->Execute("insert", "insert into " . MEDIC . " (doctor_pseudoname,email,password,first_name,last_name,gender,is_verified,system_usertype) values ('" . trim(mysql_real_escape_string($pseudoname)) . "' , '" . trim(mysql_real_escape_string($email)) . "','" . trim(mysql_real_escape_string($password)) . "','" . trim(mysql_real_escape_string($firstname)) . "','" . trim(mysql_real_escape_string($lastname)) . "','" . trim(mysql_real_escape_string($gender)) . "','1','DOCTOR')");
	}*/
				$db->Execute("insert", "insert into " . MEDIC . " (doctor_pseudoname,email,password,first_name,last_name,gender,is_verified,system_usertype) values ('" . trim(mysql_real_escape_string($pseudoname)) . "' , '" . trim(mysql_real_escape_string($email)) . "','" . trim(mysql_real_escape_string($password)) . "','" . trim(mysql_real_escape_string($firstname)) . "','" . trim(mysql_real_escape_string($lastname)) . "','" . trim(mysql_real_escape_string($gender)) . "','1','DOCTOR')");
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= 'From: Support <noreply@cmpforyou.com>' . "\r\n";
                $headers .= "X-Priority: 1\r\n"; 
                $subject = "Thanks From Cmforyou";
                $mailmessage = "<p>Thank you for register as a doctor/nurse.</p>";
                $mailmessage.= "<p>You have recieved a verification link. Just copy link and paste it in browser or either click on the verify button and proceed forward to complete your information.</p>";
                $mailmessage .= "<p><a href=" . $remotelocation . 'verifydoctoremail.php?token=' . base64_encode($email) . " target='_blank'>".$remotelocation."verifydoctoremail.php?token=".base64_encode($email)."</a></p><br/>";
                $mailmessage .= "<p><a href=" . $remotelocation . 'verifydoctoremail.php?token=' . base64_encode($email) . " target='_blank'><button style='align:center;' type='submit' id='button' name='verify' class='btn-primary'>Verify</button></a></p>";
                $mailmessage .= "<p><br /></p>";
                $mailmessage .= "<p>Best Regards</p>";
                $mailmessage .= "<p>Cmforyou</p>";
                @mail($email, $subject, $mailmessage, $headers);
                $_POST = array();
                //$message = " <div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Success!</strong> Thank you for register with us , a confirmation link send on your email address..!!</div>";
				$message = " <div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Success!</strong> Thank you for registering with us. Click on confirmation link sent to your email address.</div>";

		   }
        } else {
            $message = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Password must be same.</div>";
        }
    }
}
?> 
<script>
	function display_details(){
		$("#id_hide_div").show();
		$("#id_hide_div2").show();
		$("#id_hide_div3").show();
		$("#id_hide_div4").show();
		$("#id_show_div").hide();
	}
	 function populateCity(){
		var state =   $('#state_fast').val();
		 $.ajax({
				type: "POST",
				url: '<?php echo $remotelocation."populatecity.php";?>',
				data: {
					state_id:state
				},
				success: function(data) {
					 $('#city_fast').empty();
					$('#city_fast').append(data);
					}
			});
    }
</script>
<div class="container"><div class="row"><img src="<?= $remotelocation; ?>includes/images/DOCTOR-PATIENT.jpg" class="img-responsive margin-top-40"></div></div>
<div class="container">
    <div class="form-container row"  style="padding:0px">
        <h4 class="text-center margin-top-20">Join us today for free to feel good and improve your health !</h4>

        <div class="col-md-2"></div>
		<table style="width:100%">
		<tr>
		<td  style="width:50%" valign="top">
			<div class="col-md-4 margin-top-40 "  style="width:100%">
				<div class="margin-bottom-20"><i class="fa fa-user-md fa-2x" style="color:#999"></i>&nbsp;We Provide Expert advice from top doctors/nurses</div>
				<div class="margin-bottom-20"><i class="fa fa-laptop fa-2x" style="color:#999"></i> &nbsp;We' re Available 24/7 on any device</div>
				<div class="margin-bottom-20"><i class="fa fa-stethoscope fa-2x" style="color:#999"></i>  &nbsp;We Give Private questions answered within 24 hrs</div>
			</div>
			<div class="col-md-6 mysign" style="width:100%;margin-top:-70px">
			  <div class="col-md-6 mysign" style="width:100%;margin-top:50px"><?= (isset($message) && !empty($message_fast) ? $message_fast : ''); ?>
				<form class="form-horizontal signup-form  text-left margin-top-30" style="padding:30px 25px; background-color:#74b1d2;" method="post" action="<?php $_SERVER['PHP_SELF']; ?>" <?php if($doctor_id){ ?> id="docfrm_update" <?php } else { ?> id="docfrm_fast" <?php } ?>>
				<input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctor_id; ?>"/>
			  <h3 style="margin-left:-3px">Fast Sign Up</h3>
				  <p style="margin-left:-1px">Check if we have your details, Enter one of the following </p>
				<input type="hidden" name="action" id="_cmd_fast" value="_docreg_fast">
				  <div class="firstnode">
					 <div class="form-group">
                        <div class="col-md-6">
                            <label for="textinput">State</label>  
                             <select class="form-control" name="state_fast" id="state_fast" onchange="populateCity();">
								<option value="">Select State</option>
								<?php

                            if (isset($state_list) && !empty($state_list)) {

                                foreach ($state_list as $data) {

                                    if(isset($_POST['state_fast']) && $data['sta_code'] == $_POST['state_fast'] ){

                                        echo '<option value="'.$data['sta_code'].'" selected="selected" >'.$data['sta_name'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.$data['sta_code'].'">'.$data['sta_name'].'</option>';

                                    } 

                                }

                            }

                            ?>
							</select>	
                        </div>
                        <div class="col-md-6">
                            <label for="textinput">City</label>  
                           <select class="form-control" name="city_fast" id="city_fast" >

                            <option value="">Select City</option>

                            <?php
if(isset($_POST['state_fast']) &&!empty($_POST['state_fast'])){
     $city_list = $db->Execute("select", "select DISTINCT(city) FROM " . CITYSTATE . " where state = '".trim($_POST['state_fast'])."' order by city asc");

                            if (isset($city_list) && !empty($city_list)) {

                                foreach ($city_list as $data) {

                                    if(isset($_POST['city_fast']) && $data['city'] == $_POST['city_fast'] ){

                                        echo '<option value="'.trim($data['city']).'" selected="selected" >'.$data['city'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.trim($data['city']).'">'.$data['city'].'</option>';

                                    } 

                                }

                            }
                            }

                            ?>

                        </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="textinput">First Name</label>  
                            <input  name="fname" id="fname" placeholder="Enter your first name" value="<?= (isset($fname_fast) ? $fname_fast : ''); ?>" class="form-control" type="text" >
                        </div>
                        <div class="col-md-6">
                            <label for="textinput">Last Name</label>  
                            <input  name="lname" id="lname" placeholder="Enter your last name"  value="<?= (isset($lname_fast) ? $lname_fast : ''); ?>" class="form-control" type="text" required>
                        </div>
                        <div class="clearfix"></div>
                    </div>
					 <div class="form-group">
                        <div class="col-md-6">
                            <label for="textinput">Zipcode</label>  
                            <input  name="zipcode" id="zipcode" placeholder="Enter your Zipcode" value="<?= (isset($zipcode_fast) ? $zipcode_fast : ''); ?>" class="form-control" type="text">
                        </div>
                        <div class="col-md-6">
                            <label for="textinput">Phone Number</label>  
                            <input  name="phoneno_fast" id="phoneno_fast" placeholder="Enter your Phone number"  value="<?= (isset($phoneno_fast) ? $phoneno_fast : ''); ?>" class="form-control" type="text">
                        </div>
                        <div class="clearfix"></div>
                    </div>
					<div class="form-group">
                        <div class="col-md-12">
                            <label for="textinput">DOB</label>  
                            <input  name="dob_fast" id="dob_fast" value="<?= (isset($dob_fast) ? $dob_fast : ''); ?>" placeholder="Enter your DOB" class="form-control" type="text">
                        </div>
                        <div class="clearfix"></div>
                    </div>
					 <div class="form-group">
                        <div class="col-md-12" id="id_hide_div" style="display:none">
                            <label for="textinput">Email Address&nbsp;(See our strict <a href="<?php echo $remotelocation . "privacy-policy.php"; ?>" target="_blank">Privacy Policy</a>)</label>  
                            <input  name="email" id="email" value="<?= (isset($_POST['email']) ? $_POST['email'] : ''); ?>" placeholder="Enter your email address" class="form-control email" type="text">
                        </div>
                        <div class="clearfix"></div>
                    </div>
					   <div class="form-group" id="id_hide_div2" style="display:none">
                        <div class="col-md-6">
                            <label for="textinput">Pseudo Name</label>  
                            <input name="pseudoname" id="pseudoname" value="<?= (isset($_POST['pseudoname']) ? $_POST['pseudoname'] : ''); ?>" placeholder="Enter your pseudo name" class="form-control" type="text">
                        </div>
						
                        <div class="col-md-6">
                            <label for="textinput">Gender</label>  
                            <?php $gender_arr = array('M' => "Male", 'F' => "Female", 'T' => "Trans"); ?>
                            <select name="gender" id="gender" class="form-control">                                
                                <?php
                                foreach ($gender_arr as $key => $value):
                                    if (isset($_POST['gender']) && $_POST['gender'] == $key) {
                                        echo "<option value='" . $key . "' selectd='selected'>" . $value . "</option>";
                                    } else {
                                        echo "<option value='" . $key . "'>" . $value . "</option>";
                                    }
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group" id="id_hide_div3" style="display:none">
                        <div class="col-md-6">
                            <label for="textinput">Password</label>  
                            <input name="password1" id="password1" placeholder="Enter your password"  class="form-control" type="password">
                        </div>
                        <div class="col-md-6">
                            <label for="textinput">Retype Password</label>  
                            <input name="repassword1" id="repassword1"  placeholder="Retype password" class="form-control" type="password" >
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group" id="id_hide_div4" style="display:none">
                        <div class="col-md-12">
                            <button type="submit" id="continuebtn_update" name="continuebtn_update" class="btn-primary signup-btn" style="cursor:pointer">Continue</button>
                        </div>
                    </div>
					<?php
						if($me==0){
					?>
					<div class="form-group">
                        <div class="col-md-12">
                            <button type="submit" id="continuebtn_fast" name="continuebtn_fast" class="btn-primary signup-btn" style="cursor:pointer">Search</button>
                        </div>
                    </div>
					<?php
					} else {
					?>
						<div class="form-group" id="id_show_div">
                        <div class="col-md-12">
						<input type="button" name="me" class="btn-primary signup-btn" style="cursor:pointer;height:40px" value="This is me" onclick="display_details();"/>
                            <!--<button type="button" id="continuebtn_fast" name="continuebtn_fast" class="btn-primary signup-btn" style="cursor:pointer">This is me</button>-->
                        </div>
                    </div>
					<?php
					}
					?>
                    <div class="clearfix"></div>
				 </div>	
			   </form>   
		</div> 
			
		</td>
		<td style="width:50%">
        <div class="col-md-6 mysign" style="width:100%;margin-top:60px"><?= (isset($message) && !empty($message) ? $message : ''); ?>
            <form class="form-horizontal signup-form  text-left margin-top-30" style="padding:30px 25px; background-color:#74b1d2;" method="post" action="<?php $_SERVER['PHP_SELF']; ?>" id="docfrm">
                <h3 style="margin-left:-3px">Create Doctor/Nurse Account</h3>
                <p style="margin-left:-1px">Are you a doctor/nurse? <a href="">Sign Up</a>. Already a Member? <a href="<?php echo $remotelocation . "doctor_login.php"; ?>">Login</a></p>
                <p style="margin-left:-1px">I agree <a href="<?php echo $remotelocation . "terms.php"; ?>">Term</a> and <a href="<?php echo $remotelocation . "privacy-policy.php"; ?>" target="_blank">Privacy Policy</a></p>
                <!-- first Node-->
                <input type="hidden" name="action" id="_cmd" value="_docreg">
                <div class="firstnode">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="textinput">First Name</label>  
                            <input  name="fname" id="fname" placeholder="Enter your first name" value="<?= (isset($_POST['fname']) ? $_POST['fname'] : ''); ?>" class="form-control" type="text">
                        </div>
                        <div class="col-md-6">
                            <label for="textinput">Last Name</label>  
                            <input  name="lname" id="lname" placeholder="Enter your last name"  value="<?= (isset($_POST['lname']) ? $_POST['lname'] : ''); ?>" class="form-control" type="text">
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="textinput">Email Address&nbsp;(See our strict <a href="<?php echo $remotelocation . "privacy-policy.php"; ?>" target="_blank">Privacy Policy</a>)</label>  
                            <input  name="email" id="email" value="<?= (isset($_POST['email']) ? $_POST['email'] : ''); ?>" placeholder="Enter your email address" class="form-control email" type="text">
                        </div>
                        <div class="clearfix"></div>
                    </div>
					 <div class="form-group">
                        <div class="col-md-6">
                            <label for="textinput">City</label>  
                            <input  name="city" id="city" placeholder="Enter your City" value="<?= (isset($_POST['city']) ? $_POST['city'] : ''); ?>" class="form-control" type="text">
                        </div>
                        <div class="col-md-6">
                            <label for="textinput">Zipcode</label>  
                            <input  name="zipcode" id="zipcode" placeholder="Enter yourZipcode"  value="<?= (isset($_POST['zipcode']) ? $_POST['zipcode'] : ''); ?>" class="form-control" type="text">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="textinput">Pseudo Name</label>  
                            <input name="pseudoname" id="pseudoname" value="<?= (isset($_POST['pseudoname']) ? $_POST['pseudoname'] : ''); ?>" placeholder="Enter your pseudo name" class="form-control" type="text">
                        </div>
						
                        <div class="col-md-6">
                            <label for="textinput">Gender</label>  
                            <?php $gender_arr = array('M' => "Male", 'F' => "Female", 'T' => "Trans"); ?>
                            <select name="gender" id="gender" class="form-control">                                
                                <?php
                                foreach ($gender_arr as $key => $value):
                                    if (isset($_POST['gender']) && $_POST['gender'] == $key) {
                                        echo "<option value='" . $key . "' selectd='selected'>" . $value . "</option>";
                                    } else {
                                        echo "<option value='" . $key . "'>" . $value . "</option>";
                                    }
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="textinput">Password</label>  
                            <input name="password" id="password" placeholder="Enter your password"  class="form-control" type="password">
                        </div>
                        <div class="col-md-6">
                            <label for="textinput">Retype Password</label>  
                            <input name="repassword" id="repassword"  placeholder="Retype password" class="form-control" type="password" >
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="submit" id="continuebtn" name="continuebtn" class="btn-primary signup-btn" style="cursor:pointer">Continue</button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- first Node-->
                <!-- Second Node-->
                <div class="secondnode">
                    second node
                </div>
                <!-- Second Node-->
            </form>
        </div>
	</td>
	</tr>
</table>	
        <div class="clearfix"></div>
    </div>
</div>
<?php require_once("includes/footer.php"); ?>  
<style>
    label.error{
       text-align: right;
       margin-right: 15px;
    }
</style>