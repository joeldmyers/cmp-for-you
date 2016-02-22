<?php

require_once("includes/top.php");

if(isset($_SESSION['emp_id']) && $_SESSION['emp_id'] > 0)
{
    echo "<script>window.location.href='patientdashboard.php'</script>";
    exit();
}
$errorMsg = '';
if(isset($_POST["loginbtn"]) && !empty($_POST["loginbtn"]))
{

        $patient_pseudoname=(string)$_POST["patient_pseudoname"];
        $password= md5($_POST["patient_password"]);    
        $loginarr = $db->Execute("select","SELECT concat_ws(' ',p.patient_fname,p.patient_lname) as fullname , p.* FROM ".PATIENTS." as p where UPPER(p.patient_pseudoname) = '".strtoupper(trim(mysql_real_escape_string($patient_pseudoname)))."'  and p.patient_password = '".trim(mysql_real_escape_string($password))."' ");
        $error = '';        
        if(isset($loginarr[0]["patient_id"]) && $loginarr[0]["patient_id"] > 0 ) {
            
            if(isset($loginarr[0]['patient_status']) && $loginarr[0]['patient_status'] == 1 )
            {                
             $_SESSION["emp_id"] = $loginarr[0]["patient_id"];            
             $_SESSION["emp_email"] = $loginarr[0]["patient_email"];
             $_SESSION["emp_name"] = $loginarr[0]["fullname"];
             $_SESSION["emp_usertype"] = $loginarr[0]["system_usertype"];
             if(intval($loginarr[0]['patient_dob']) > 0 )
             {
              $_SESSION["emp_age"] = date("Y") - date("Y",strtotime($loginarr[0]['patient_dob']));                                                                                                    
             }
             $_SESSION['patient_profilepic'] = $loginarr[0]['patient_profilepic'];
             $_SESSION['patient_gender'] = $loginarr[0]['patient_gender'];
              echo "<script>window.location.href='profile_steps.php'</script>";
              exit();
            }else{               
                 $errorMsg = "Your email adress not confirmed please click on verification link from email.";      
            }            
        }else{
                 $errorMsg = "Enter Pseudo Name and password combination incorrect.";            
        }
}
?>
<?php include 'includes/header.php'; ?>
<div class="container">
     <div class=" form-container row margin-top-40">  
     <div class="col-md-4"></div>  

        <div id="loginbox" class="col-md-4" style="background-color:#fff; padding:55px 20px;">                    
            <?php
    if(isset($_GET["status"]) && $_GET["status"] == 'logout')
    {
            echo "<div class='text-left' style='color:red;'>You are logout successfully!</div>";
    } 
    if(isset($errorMsg) && !empty($errorMsg))
    {
            echo "<div class='text-left' style='color:red;'>".$errorMsg."</div>";
    }                                        
?>
                        <div style="font-size: 15px; font-weight: bold; text-align: center; padding-top: 0px; margin-bottom: 10px;">Patient Login</div>
                         <form action="login.php" method="post" enctype="multipart/form-data" id="loginfrm" name="loginfrm" class="form-horizontal" >                                                                         
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="patient_pseudoname" type="text" class="form-control" name="patient_pseudoname" value="<?=(isset($_POST['patient_pseudoname']) ? $_POST['patient_pseudoname'] : '');?>" placeholder="Enter Pseudo Name" />  
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="patient_password" type="password" class="form-control" name="patient_password" placeholder="Enter Password" />
                                    </div>
<!--                            <div style="float:right; font-size:12px; position: relative;"><a href="#">Forgot password?</a></div>-->
                 <span style="font-size:12px;">Not a member? <a href="<?=$remotelocation."patient_reg.php"; ?>">Sign Up</a></span>
                 <span style="font-size:12px; float:right"><a href="<?=$remotelocation."patientforgotpassword.php"; ?>">Forgot Password</a></span>
                                 <input type="submit" name="loginbtn" class="btn-primary signup-btn" value="Sign In" id="loginbtn" />
                            </form>     
        </div> 
            <div class="col-md-4"></div><div class="clearfix"></div>
        </div>    
</div>
</div>

    </body>
</html>