<?php
require_once("includes/top.php");
require_once("includes/header.php");
global $db;
$message = '';
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
			
	/*************************************************************************************************/	 $isdbExist = $db->Execute("select", "select doctor_id,first_name,last_name,email,doctor_pseudoname,primary_speciality from " . MEDIC . " where  first_name = '" . $firstname . "' AND  last_name = '" . $lastname . "' AND zipcode='" . $zipcode . "' ");	
	
	if(isset($isdbExist[0]['doctor_id']) && $isdbExist[0]['doctor_id'] > 0){
		$ids=$isdbExist[0]['doctor_id'];
		$updatedb ="UPDATE " . MEDIC . " SET doctor_pseudoname='" . trim(mysql_real_escape_string($pseudoname)) . "',password='" . trim(mysql_real_escape_string($password)) . "',email='" . trim(mysql_real_escape_string($email)) . "',is_verified=1,system_usertype='DOCTOR' WHERE doctor_id='$ids'";
		$res=mysql_query($updatedb);	
	/***********************************************************************************************/	} else {	
			$db->Execute("insert", "insert into " . MEDIC . " (doctor_pseudoname,email,password,first_name,last_name,gender,is_verified,system_usertype) values ('" . trim(mysql_real_escape_string($pseudoname)) . "' , '" . trim(mysql_real_escape_string($email)) . "','" . trim(mysql_real_escape_string($password)) . "','" . trim(mysql_real_escape_string($firstname)) . "','" . trim(mysql_real_escape_string($lastname)) . "','" . trim(mysql_real_escape_string($gender)) . "','1','DOCTOR')");
	}		
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
<div class="container"><div class="row"><img src="<?= $remotelocation; ?>includes/images/DOCTOR-PATIENT.jpg" class="img-responsive margin-top-40"></div></div>
<div class="container">
    <div class="form-container row"  style="padding:0px">
        <h4 class="text-center margin-top-20">Join us today for free to feel good and improve your health !</h4>

        <div class="col-md-2"></div>
        <div class="col-md-4 margin-top-40 ">
            <div class="margin-bottom-20"><i class="fa fa-user-md fa-2x" style="color:#999"></i>&nbsp;We Provide Expert advice from top doctors/nurses</div>
            <div class="margin-bottom-20"><i class="fa fa-laptop fa-2x" style="color:#999"></i> &nbsp;We' re Available 24/7 on any device</div>
            <div class="margin-bottom-20"><i class="fa fa-stethoscope fa-2x" style="color:#999"></i>  &nbsp;We Give Private questions answered within 24 hrs</div>
        </div>
        <div class="col-md-6 mysign"><?= (isset($message) && !empty($message) ? $message : ''); ?>
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