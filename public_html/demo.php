<?php
require_once("includes/top.php");
require_once("includes/header.php");
if(isset($_POST['submit']))
{
 echo "<a href = 'http://cmpforyou.com/patient_reg.php'>";
}
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
			
				$doctor_name=trim(mysql_real_escape_string($firstname))." ".trim(mysql_real_escape_string($lastname));
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
				/***************************info@cmpForYou.com****************************************************/
					$info_subject="A NEW doctor signed up";
					$info_message= "A NEW doctor signed up, name :  ".$doctor_name;
					 $info_message.= "<p>For viewing Profile . Just copy link and paste it in browser or either click on the Profile Link button .</p>";
					$info_message .= "<p><a href=" . $remotelocation . 'viewprofilelink.php?type=doctor&pseudoname='.base64_encode($pseudoname).'&token=' . base64_encode($email) . " target='_blank'>".$remotelocation."viewprofilelink.php?type=doctor&pseudoname=".base64_encode($pseudoname)."&token=".base64_encode($email)."</a></p><br/>";
					$info_message .= "<p><a href=" . $remotelocation . 'viewprofilelink.php?type=doctor&pseudoname='.base64_encode($pseudoname).'&token=' . base64_encode($email) . " target='_blank'><button style='align:center;' type='submit' id='button' name='verify' class='btn-primary'>Profile Link</button></a></p>";
					 $info_message .= "<p><br /></p>";
					$info_message .= "<p>Best Regards</p>";
					$info_message .= "<p>Cmforyou</p>";
					@mail("info@cmpForYou.com", $info_subject, $info_message, $headers);
					
				/***********************************************************************************************/
                $_POST = array();
               
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
		 $target_dir = "uploads/";
      $target_file = $target_dir . basename($_FILES["file"]["name"]);
     $file_name = $_FILES['file']['name'];
     $file_size = $_FILES['file']['size'];
     $file_type = $_FILES['file']['type'];
   $move = move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
		
        if ($password == $repassword) {
	    $isUserExist = $db->Execute("select", "select doctor_id,first_name,last_name,email,doctor_pseudoname,primary_speciality from " . MEDIC . " where  email = '" . $email . "' OR doctor_pseudoname = '" . $pseudoname . "'");
            $isPatientExist = $db->Execute("select","select patient_id from ".PATIENTS." WHERE UPPER(patient_email) = '".strtoupper($email)."' OR UPPER(patient_pseudoname) = '".$pseudoname."'");
            if(isset($isUserExist[0]['doctor_id']) && $isUserExist[0]['doctor_id'] > 0){
                $message = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Entered email address or pseduo name already taken , Try again.</div>"; 
            }elseif(isset($isPatientExist[0]['patient_id']) && $isPatientExist[0]['patient_id'] > 0){
                $message = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Entered email address or pseduo name already taken , Try again.</div>"; 
            } else {
				$doctor_name=trim(mysql_real_escape_string($firstname))." ".trim(mysql_real_escape_string($lastname));
	/*************************************************************************************************/	/* $isdbExist = */
				$db->Execute("insert", "insert into " . MEDIC . " (doctor_pseudoname,email,password,first_name,last_name,gender,is_verified,sign_png
				system_usertype,) values ('" . trim(mysql_real_escape_string($pseudoname)) . "' , '" . trim(mysql_real_escape_string($email)) . "','" . trim(mysql_real_escape_string($password)) . "','" . trim(mysql_real_escape_string($firstname)) . "','" . trim(mysql_real_escape_string($lastname)) . "','" . trim(mysql_real_escape_string($gender)) . "','1','DOCTOR','" . trim(mysql_real_escape_string($file_name)) . "')");
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
				/***************************info@cmpForYou.com****************************************************/
					$info_subject="A NEW doctor signed up";
					$info_message= "A NEW doctor signed up, name :  ".$doctor_name;
					 $info_message.= "<p>For viewing Profile . Just copy link and paste it in browser or either click on the Profile Link button .</p>";
					$info_message .= "<p><a href=" . $remotelocation . 'viewprofilelink.php?type=doctor&pseudoname='.base64_encode($pseudoname).'&token=' . base64_encode($email) . " target='_blank'>".$remotelocation."viewprofilelink.php?type=doctor&pseudoname=".base64_encode($pseudoname)."&token=".base64_encode($email)."</a></p><br/>";
					$info_message .= "<p><a href=" . $remotelocation . 'viewprofilelink.php?type=doctor&pseudoname='.base64_encode($pseudoname).'&token=' . base64_encode($email) . " target='_blank'><button style='align:center;' type='submit' id='button' name='verify' class='btn-primary'>Profile Link</button></a></p>";
					 $info_message .= "<p><br /></p>";
					$info_message .= "<p>Best Regards</p>";
					$info_message .= "<p>Cmforyou</p>";
					@mail("info@cmpForYou.com", $info_subject, $info_message, $headers);
					
				/***********************************************************************************************/
               
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
		//alert(state);
		 $.ajax({
				type: "POST",
				url: 'populatecity.php',
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

<div class="tp-banner-container index-rev-slider">
  <div class="tp-banner" style="padding:0px !important">
    <ul>
      
      <!-- SLIDERIGHT -->
      
      <li data-transition="slideright" data-slotamount="6" data-thumb=""> <img src="<?= $remotelocation; ?>includes/images/heart-specialist.jpg" alt="image" />
        <div class="caption sft big_white" data-x="530" data-y="0" data-speed="1500" data-start="1700" data-easing="easeOutExpo" style="color:#000; font-size:27px;"><strong>Highly Skilled and Experienced Physicians </strong></div>
        <div class="caption sfb medium_grey text-center" data-x="530" data-y="1000" data-speed="1500" data-start="2500" data-easing="easeOutExpo" style="font-size:22px;color:#000; text-align:left;margin-top:-26px; ">Consult Online  Anytime Anywhere<br>
          Look Younger, Live Longer &  Be Happy</div>
      </li>
      
      <!-- SLIDERIGHT -->
      
      <li data-transition="slideright" data-slotamount="6" data-thumb=""> <img src="<?= $remotelocation; ?>includes/images/emergency-services.jpg" alt="image" /> 
        
        <!--div class="caption sft big_white" data-x="530" data-y="0" data-speed="1500" data-start="1700" data-easing="easeOutExpo" style="color:#000; font-size:27px;"><!--strong>Affordable  Care Right at Home or Elsewhere </strong></div>

                <div class="caption sfb medium_grey text-center" data-x="530" data-y="1000" data-speed="1500" data-start="2500" data-easing="easeOutExpo" style="font-size:22px;color:#000; text-align:left;margin-top:-26px; ">Get Home Service Providers or Nursing Home , or Hospital<br> Smart, Competent , Prompt</div--> 
        
      </li>
      
      <!-- SLIDEUP -->
      
      <li data-transition="slideup" data-slotamount="15" data-thumb=""> <img src="<?= $remotelocation; ?>includes/images/twitter_1018.jpg" alt="image" /> 
        
        <!--div class="caption sft big_white" data-x="180" data-y="0" data-speed="1500" data-start="1700" data-easing="easeOutExpo" style="color:#000; font-size:27px;"><strong>Doctors & Nurses:  Earn Big Bucks  in Your Spare Time Get New Patients</strong></div>

                <div class="caption sfb medium_grey text-center" data-x="530" data-y="1000" data-speed="1500" data-start="2500" data-easing="easeOutExpo" style="font-size:22px;color:#000; text-align:left;margin-top:-26px; ">Be Your Own Boss With Flexible Hours<br>

                    CMP For You</div--> 
        
      </li>
    </ul>
  </div>
</div>
<div class="clearfix"></div>

<!---------------------------------------------------------------------start Fast sign up code---------------------------------------------------------------->

<div class="container">

<!-- <h5 class="text-center">Thus we are going to give Free EFAX to all doctors if they sign up with us.<br> </h5>
 -->

<H2 style="width:100%; text-align:center; font-size:28px;">Doctors & Nurses</H2>
<h3 style="width:100%; text-align:center; font-size:22px;">CLAIM YOUR FREE MEMBERSHIP & FREE VIDEO</h3>
<div class="col-md-2"></div>
<table style="width:100%; margin-left: 15%;" class="">
  <p>
    <?= (isset($message) && !empty($message_fast) ? $message_fast : ''); ?>
  </p>
  <tr>
    <td  style="width:70%" valign="top"><div class="col-md-6 mysign" style="width:100%;margin-top:-70px">
      <div class="col-md-6 mysign" style="width:100%;margin-top:50px">
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
                        <div class="clearfix"></div>
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
              <input  name="fname" id="fname" placeholder="Enter your first name" value="<?= (isset($fname_fast) ? $fname_fast : ''); ?>" class="form-control" type="text" required>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
              <label for="textinput">Last Name</label>
              <input  name="lname" id="lname" placeholder="Enter your last name"  value="<?= (isset($lname_fast) ? $lname_fast : ''); ?>" class="form-control" type="text" >
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="form-group">
            <div class="col-md-6">
              <label for="textinput">Zipcode</label>
              <input  name="zipcode" id="zipcode" placeholder="Enter your Zipcode" value="<?= (isset($zipcode_fast) ? $zipcode_fast : ''); ?>" class="form-control" type="text">
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
              <label for="textinput">Phone Number</label>
              <input  name="phoneno_fast" id="phoneno_fast" maxlength="10" placeholder="Enter your Phone number"  value="<?= (isset($phoneno_fast) ? $phoneno_fast : ''); ?>" onKeyPress="return isNumberKey(event)" class="form-control" type="text" required>
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
      </div></td>
      <td style="width:50%" style="display:none">
      <div class="col-md-6 mysign" style="width:100%;margin-top:-20px; display:none;">
    <?= (isset($message) && !empty($message) ? $message : ''); ?>
    <form class="form-horizontal signup-form  text-left margin-top-30" style="padding:30px 25px; background-color:#74b1d2;" method="post" action="<?php $_SERVER['PHP_SELF']; ?>" id="docfrm" enctype="multipart/form-data">
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
        <!--div class="col-md-12">
                            <label for="textinput">Upload signature in png</label>  
                            <input name="file" id="file"  placeholder="Upload Signature in png" class="form-control" type="file" style="position: absolute;
    z-index: 999999;width:100%; height:3030" >
                        </div>
                        
                        <div class="col-md-12" style="padding-top:40px;">
                          <center><h3> OR </h3></center>  
                            
                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                  
                            <!--a id="github" href="https://github.com/szimek/signature_pad">
    <img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Fork me on GitHub">
  </a>

  


                  
                  	<div class="form-group">
                        <div class="col-md-12">
                            <div id="signature-pad" class="m-signature-pad">
    <div class="m-signature-pad--body">
      <canvas></canvas>
    </div>
    <div class="m-signature-pad--footer">
      <div class="description">Sign above</div>
      <!--button class="button clear" data-action="clear">Clear</button>
      <button class="button save" data-action="save">Save</button--> 
        
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
      <div class="secondnode"> second node </div>
      <!-- Second Node-->
    </form>
    </div>
  
    </td>
  
    </tr>
  
</table>
<div class="clearfix"></div>
<h3  style="width:100%; text-align:center; font-size:24px; padding-top:15px;"> YOU CAN LOCATE MORE DOCTORS AND NURSES ON OUR WEB SITE THAN 100 HOSPITALS COMBINED.</h3>
</div>

<!---------------------------------------------------------------------end Fast sign up code----------------------------------------------------------------->

<div class="row text-center no-margin">
  <div class="col-md-12 bg-default">
    <div class="home-box"> <span class="fa fa-heartbeat fa-3x" style="margin-top:50px;"></span>
      <h3>Heart disease</h3>
      <p>Most of the people think that heart disease is probably not our problem, it often happens to other, but in reality the large number of deaths occurs due to the heart diseases in US. </p>
      <a href="diseases.php" class="btn-rounded btn-bordered">Read more</a> </div>
  </div>
  <div class="col-md-12" style="background-color:#f7f7f7">
    <div class="home-box opening-hours clearfix"> <span class="glyphicon glyphicon-time"></span>
      <h3>Sign Up Now</h3>
      <p> " Get treated for Heart Disease, cancer,diabetes, respiratory and Other Chronic Diseases "</p>
      <a href="<?php echo $remotelocation . "patient_reg.php" ?>" class="btn btn-default signup">Sign Up Now</a> </div>
  </div>
  <div class="col-md-12 bg-default">
    <div class="home-box"> <span class="glyphicon glyphicon-tint"></span>
      <h3>Cancer Care</h3>
      <p>Cancer is considered one of the major lives taking disease, it develops uncommon cells that spread uncontrollably in the body, these cells are capable to penetrate and demolish body tissue. </p>
      <a href="diseases.php" class="btn-rounded btn-bordered">Read more</a> </div>
  </div>
</div>

<!--section class="appointment-sec text-center" style="background-color:#f3f3f3"> 

    <div class="container">

        <h1>Make an appointment</h1>

        <div class="row">

            <div class="col-md-6">

                <figure><img src="<?= $remotelocation; ?>includes/images/aptmnt-img.png" alt="image" title="Appointment image" class="img-responsive lady1"></figure>

            </div>

            <div class="col-md-6">

                <div class="appointment-form clearfix">

                    <div id="formsubmission"></div>

                    <div class="form">

                        <form name="appointment" id="appointment" method="post" action="" enctype='multipart/form-data'>

                            <input type="text" name="firstname" id="firstname" placeholder="First Name" disabled>

                            <input type="text" name="lastname" id="lastname"  placeholder="Last Name" disabled >

                            <input type="email" name="emailaddress" id="emailaddress" placeholder="Email Address" disabled > 

                            <input type="text" name="phonenumber"  id="phonenumber"  placeholder="Phone No" disabled maxlength="10"  onkeypress="return isNumberKey(event)">
							<input type="text" name="symptoms" id="symptoms" placeholder="Symptoms" disabled >

                            <input type="text" name="Insurance" id="Insurance"  placeholder="Insurance Name" disabled >

                            <input type="text" name="appointmentdate"  id="appointmentdate" placeholder="Appointment Date" disabled >

                            <select name="gender" id="gender" disabled>

                                <option value="M">Male</option>

                                <option value="F">Female</option>

                                <option value="C">Child</option>

                            </select>

                            <textarea placeholder="Message" id="message" name="message" disabled></textarea>
							
                            
                         <input type=button id="submit" name="submit" class="btn btn-default btn-rounded" onClick="location.href='http://cmpforyou.com/patient_reg.php'" value='Sign Up as A Patient'>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section-->

<section class="creative-sec margin-bottom-40">
  <div class="fluid-container text-center">
    <div class="col-md-4"> 
      
      <!-- <h5 class="h5font">Better Healthcare at Lower cost</h5>

            Get Professional Opinion and Guidance Online<br>

            Instant Messaging Or  Face To Face By Video<br>

            Remove Uncertainty and Doubt <br>

            No Sleepless Nights  <br> --> 
      
    </div>
    
    <div class="clearfix"></div>
  </div>
  <div class="container">
    
    <div class="col-md-12" style="padding-left:40px;padding-right:40px; text-align:center; ">
      <h2 class="text-center">Come Join Us & Save Lives</h2>
     
      
      <iframe src="https://player.vimeo.com/video/148320206" width="460px" height="200px" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href="https://vimeo.com/145786948">cmpforyou</a> on <a href="https://vimeo.com">Vimeo</a>.</p></div>
      
    
    
  </div>
</section>
<section class="app container">
  <div class="container">
    <div class="row">
      <div class="col-md-5 col-sm-4 hidden-xs"> <img src="<?= $remotelocation; ?>includes/images/mobile-hand.png" class="app-img img-responsive" alt="" title=""></div>
      <div class="col-md-7 col-sm-8 col-xs-12">
        <div class="app-content">
        
          <h1>Custom Medical Services App Available</h1>
          <p class="lead text-center">Download   CMPForYou  App </p>
          <ul class="list-unstyled app-buttons">
            <li><a href="https://itunes.apple.com/us/app/cmp-for-you/id1050607724?l=iw&ls=1&mt=8"><img src="<?= $remotelocation; ?>includes/images/app-store-btn.png" alt="" title="App Store" class="img-responsive"></a></li>
            <li><a href="https://play.google.com/store/apps/details?id=biz.app4mobile.app_f682baf90f9240a3a0324a6249d7e4c2.app"><img src="<?= $remotelocation; ?>includes/images/google-play-btn.png" alt="" title="Google App" class="img-responsive"></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
<style>

    .done {

        display:none;

        color: green;

        font-size:18px;

    }

</style>
<script type="text/javascript">


    var revapi;

    jQuery(document).ready(function () {

        revapi = jQuery('.tp-banner').revolution(

                {

                    delay: 9000,

                    startwidth: 1170,

                    startheight: 400,

                    hideThumbs: 200,

                    fullWidth: "on",

                    forceFullWidth: "on"

                });

    });



</script> 
<script type="text/javascript">

    jQuery(document).ready(function () {

        jQuery('.play').click(function () {

            jQuery('.youtube-play').fadeIn('slow');

        });

        jQuery('.rmv').click(function () {

            jQuery('.youtube-play').fadeOut('fast');

        });

    });

</script> 
<script type="text/javascript">

    jQuery(document).ready(function () {

        jQuery('.playa').click(function () {

            jQuery('.youtube-playa').fadeIn('slow');

        });

        jQuery('.rmva').click(function () {

            jQuery('.youtube-playa').fadeOut('fast');

        });



        jQuery('.errno-msg').delay(4000).fadeOut('slow');

    });

</script> 

<!--<script>

    jQuery(document).ready(function () {

        $("#submit").click(function () {

            var firstname = $("#firstname").val();

            var lastname = $("#lastname").val();

            var emailaddress = $("#emailaddress").val();

            var phonenumber = $("#phonenumber").val();

            var gender = $("#gender").val();

            var appointmentdate = $("#appointmentdate").val();
			var symptoms = $("#symptoms").val();
			var Insurance = $("#Insurance").val();

            var message = $("#message").val();

            var reg = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

            //            var request_date = $("#request_date").val();

            var dataString = 'firstname=' + firstname + '&lastname=' + lastname + '&emailaddress=' + emailaddress + '&phonenumber=' + phonenumber + '&gender=' + gender + '&appointmentdate=' + appointmentdate + '&message=' + message+ '&symptoms=' + symptoms+ '&Insurance=' + Insurance;

            if (firstname == '')

            {

                alert("Please fill your first name..!!");

            } else if (lastname == '') {

                alert("Please fill your last name..!!");

            } else if (emailaddress == '' || !reg.test(emailaddress)) {

                alert("Please enter a valid email address..!!");

            } else if (phonenumber == '') {

                alert("Please fill your contact number..!!");


            } else if (symptoms == '') {

                alert("Please fill your symptoms..!!");

            }else if (Insurance == '') {

                alert("Please fill your Insurance Name..!!");

            } else if (appointmentdate == '') {

                alert("Please fill appointment date..!!");

            } else if (message == '') {

                alert("Please describe the problem in message box..!!");

            } else {

                // AJAX Code To Submit Form.

                $.ajax({

                    type: "POST",

                    url: "includes/ajaxform.php",

                    data: dataString,

                    cache: false,

                    success: function (result) {

                        $('#appointment')[0].reset();

                        $('.form').fadeOut('slow');

                        $('.done').fadeIn('slow');

                        $('.form').fadeIn('slow');



                    }

                });

            }

            return false;

        });

    })



</script>-->

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> 
<script type="text/javascript">

    (function ($) {  // important!!!

        $(function () {

            $("#appointmentdate").datepicker({minDate: 0});

        })

    })(jQuery)

</script>
<?php require_once("includes/footer.php"); ?>
<script>

    function isNumberKey(evt)

    {

        var charCode = (evt.which) ? evt.which : event.keyCode

        if (charCode > 31 && (charCode < 48 || charCode > 57))

            return false;



        return true;

    }

</script>