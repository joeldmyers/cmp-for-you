<?php
require_once("includes/top.php");
if(isset($_SESSION['emp_id']) && $_SESSION['emp_id'] > 0)
{
    echo "<script>window.location.href='patientdashboard.php'</script>";
    exit();
}
$errorMsg = '';
if(isset($_POST["docforgotpass"]) && !empty($_POST["docforgotpass"]))
{
        $emailid =(string)$_POST["email"];
        $isEmailexist = $db->Execute("select","SELECT `email` FROM ".MEDIC."  where `email` = '".trim(mysql_real_escape_string($emailid))."'");
        if(isset($isEmailexist[0]['email']) && !empty($isEmailexist[0]['email']) && $isEmailexist[0]['email'] == $_POST['email']){
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= 'From: welcome@cmforyou.com>' . "\r\n";
        $subject = "Password Change Request";
        $mailmessage.= "<p>You have recieved a link. Just copy link and paste it in browser or either click on the Change Password button to reset your password.</p>";
        $mailmessage .= "<p><a href=".$remotelocation.'resetdoctorpassword.php?token='.base64_encode($emailid)." target='_blank'>".$remotelocation."resetdoctorpassword.php?token=".base64_encode($emailid)."</a></p><br/>";
        $mailmessage .= "<p><a href=".$remotelocation.'resetdoctorpassword.php?token='.base64_encode($emailid)." target='_blank'><button style='align:center;' type='submit' id='button' name='forgotpass' class='btn-primary'>Change Password</button></a></p>";
        $mailmessage .= "<p><br /></p>";
        $mailmessage .= "<p>Best Regards</p>";
        $mailmessage .= "<p>Cmforyou</p>";            
        @mail($emailid, $subject, $mailmessage, $headers);
        $_POST = array();
        $message = " <div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Success!</strong> Thank you a link send on your email address.Click on the link and reset your password.</div>";
      }else{
          $message = " <div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Error!</strong> Please enter a valid mail id</div>";
      }
}
?>
<?php include 'includes/header.php'; ?>
<div class="container">
     <div class=" form-container row margin-top-40">  
     <div class="col-md-4"></div>  

        <div id="loginbox" class="col-md-4" style="background-color:#fff; padding:55px 20px;">                    
            <?php
    if(isset($message) && !empty($message))
    {
            echo $message;
    }                                        
?>
                        <div style="font-size: 15px; font-weight: bold; text-align: center; padding-top: 0px; margin-bottom: 10px;">Enter Your Email</div>
                         <form action="" method="post" enctype="multipart/form-data" id="loginfrm" name="forgotform" class="form-horizontal" >                                                                         
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-at"></i></span>
                                        <input id="email" type="text" class="form-control required" name="email" value="<?=(isset($_POST['email']) ? $_POST['email'] : '');?>" placeholder="Enter Email" />  
                                    </div>
                      
<!--                            <div style="float:right; font-size:12px; position: relative;"><a href="#">Forgot password?</a></div>-->
                     <!--<span style="font-size:12px;">Not a member? <a href="<?=$remotelocation."patient_reg.php"; ?>">Sign Up</a></span>-->                                 
                    <input type="submit" name="docforgotpass" class="btn-primary signup-btn" value="Submit" id="docforgotpass"  onclick="return checkdocforgotpass()"/>
                            </form>     
        </div> 
            <div class="col-md-4"></div><div class="clearfix"></div>
        </div>    
</div>
</div>

    </body>
</html>
<script>
    function checkdocforgotpass(){
        var email = $("#email").val();
        if(email == ''){
            alert("Please enter your mail id");
            return false;
        }
    }
</script>