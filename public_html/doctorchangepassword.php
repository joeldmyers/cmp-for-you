<?php

require_once("includes/top.php");

require_once("includes/authentication.php");
 if (isset($_SESSION['emp_id']) && $_SESSION['emp_id'] > 0) {

    $doctorid =  $db->Execute("select", "SELECT password FROM " . MEDIC . "  where doctor_id = '" . $_SESSION['emp_id'] . "'");

    $doctorpass = $doctorid[0]['password'];
}

$errorMsg = '';

if (isset($_POST["changepass"]) && !empty($_POST["changepass"])) {
    $newPassword = trim($_POST["new_password"]);

    $confirmPassword = trim($_POST["confirm_password"]);

    $oldPassword = trim($_POST["cu_password"]);

    if(isset($doctorpass) && !empty($doctorpass) && $doctorpass == md5($oldPassword)){


     if(isset($newPassword) && isset($confirmPassword) && !empty($newPassword) && !empty($confirmPassword)){

         

         if($newPassword == $confirmPassword){

            

             $setpassword = $db->Execute("update", "update " . MEDIC . "  SET  password='" . md5($newPassword) ."' where `doctor_id`='" . $_SESSION['emp_id'] . "'");

             $successmsg = "Your password have been changed successfully";

         }else{

             

             $errorMsg = "Your new password and confirm password does not match.";  

         }

     }else{

         $errorMsg = "Your new password and confirm password fields are required";

         

     }

    }else{

        $errorMsg = "Your old password doesn't match";

    }

}else{

    //$errorMsg = "You have an error in updating password.";

}

?>

<?php require_once("includes/docheader.php"); ?>

<?php include 'includes/docleftsidebar.php'; ?>

        <div class="col-md-6 col-xs-12">

                    <h2 class="pad_btm20 pad_top10 pad_left10">Change Password</h2>

         <div class="searchbar">  

        <div id="loginbox" style="background-color:#fff; padding:55px;">                    

            <?php

            if (isset($successmsg) && !empty($successmsg)) {

                echo "<div class='text-left' style='color:green;'>".$successmsg."</div>";

            }

            if (isset($errorMsg) && !empty($errorMsg)) {

                echo "<div class='text-left' style='color:red;'>" . $errorMsg . "</div>";

            }

            ?>

<!--            <div style="font-size: 15px; font-weight: bold; text-align: center; padding-top: 0px; margin-bottom: 10px;">Change Password </div>-->

            <form action="" method="post" enctype="multipart/form-data" id="changepassword" name="changepassword" class="" > 

                <input type="hidden" name="action" value="_setpass">

                <div  class="form-group">

                    <input id="currentpassword" type="password" class="form-control" name="cu_password" value="<?=(isset($_POST['cu_password']) ? $_POST['cu_password'] : '');?>"  placeholder="Enter Old Password" />  

                </div>



                <div  class="form-group">

                    <input id="newcurrentpassword" type="password" class="form-control" name="new_password" value="<?=(isset($_POST['new_password']) ? $_POST['new_password'] : '');?>" placeholder="Enter New Password" />

                </div>



                <div  class="form-group">

                    <input id="confirmpassword" type="password" class="form-control" name="confirm_password" value="<?=(isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '');?>" placeholder="Confirm Password" />

                </div>

                <!--                            <div style="float:right; font-size:12px; position: relative;"><a href="#">Forgot password?</a></div>-->                               

                

                <div class="form-group2">

                                    <input type="submit" name="changepass" class="btn btn-default" value="Change Password" id="changepassword" onclick="return validatedocpass()" />

                                </div>

            </form>     

        </div> 

       <div class="clearfix"></div>

    </div>    

                    </div>

                        

           <?php include 'includes/docrightsidebar.php'; ?>

           

<?php                            

include 'includes/docfooter.php'; ?>

<script>

    function validatedocpass(){

      var currentpass = document.getElementById('currentpassword');

      var newcurrentpass = document.getElementById('newcurrentpassword');

      var confirmpass = document.getElementById('confirmpassword');

      

        if(currentpass.vaule == '' || newcurrentpass.value == '' || confirmpass.value == ''){

          alert("All the fields are required");

          return false;

          }else if(currentpass.vaule == ''){

          alert("Current password is required")

          return false;

          }else if(newcurrentpass.value == ''){

          alert("Newpassword is required")

          return false

         }else if(confirmpass.value == ''){

          alert("Confirmpassword is required")

          return false

          }

        

    }

</script>