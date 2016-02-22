<?php
require_once("includes/top.php");
require_once("includes/authentication.php");
global $db;
?>
 <?php require_once("includes/mheader.php"); ?> 
<?php include 'includes/leftsidebar.php'; ?>
    <script src="<?=$remotelocation;?>includes/js/jquery.js"></script>
    <script src="<?=$remotelocation;?>includes/js/jquery.themepunch.plugins.min.js"></script>			
    <script src="<?=$remotelocation;?>includes/js/jquery.themepunch.revolution.min.js"></script>
    <script src="<?=$remotelocation;?>includes/js/medical.min.js"></script>	
    <script src="<?=$remotelocation;?>includes/js/jquery.validate.min.js"></script>
    <!---<script src="<?=$remotelocation;?>includes/js/bootstrap.min.js"></script>--->
    
    
    <div class="container">
        <div class="col-md-9 col-xs-12">
    <h2 class="pad_btm20 pad_top10 pad_left10">Profile Steps</h2>
    <div class="searchbar">  

        <div class="docpanel-list">
    <style type="text/css">
        #accountForm {
            margin-top: 15px;
        }
    </style>
    <div  class="tabs-new margin-top-40">
        <ul class="nav-new nav-tabs-new">
            <li><a href="<?=$remotelocation."profile_steps.php";?>" style="cursor:pointer;">Address</a></li>
            <li><a href="<?= $remotelocation . "problems_tab.php"; ?>" style="cursor:pointer;">Problems </a></li>
            <li><a href="<?=$remotelocation."medication_tab.php"; ?>" style="cursor:pointer;">Medication </a></li>
            <li class="active"><a href="<?=$remotelocation."allergies_tab.php";?>" style="cursor:pointer;">Allergies </a></li>
            <li><a href="<?= $remotelocation . "others_tab.php"; ?>" style="cursor:pointer;">Others </a></li>  
        </ul>
    </div>
    
    <?php 
    $message = '';
    $email = $_SESSION["emp_email"];
    if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == '_allergiesTab'){
        $allergies = trim($_POST['allergies']);
        $updateAllergies = $db->Execute("update", "update " . PATIENTS . "  SET  patient_allergies='" . trim(mysql_real_escape_string($allergies)) . "' where `patient_email`='" . $email . "'");
        echo"<script>window.location.href='others_tab.php'</script>";
    }else{
        $userdata = $db->Execute("select", "select  patient_allergies FROM " . PATIENTS . " where patient_email ='".$email."'");
       }
    
    ?>

<form id="accountForm" method="post" class="form-horizontal" action="<?php $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="action" value="_allergiesTab">
        <div class="tab-content-new">
         <div class="tab-pane1" id="allergies-tab">
                <div class="form-group">
                    <label class="col-xs-3 control-label">Please List If Any Allergies</label>
                    <div class="col-xs-5">
                        <textarea class="form-control" name="allergies" id="allergies" rows="5" coloumn="5"><?=(isset($userdata[0]['patient_allergies']) && !empty($userdata[0]['patient_allergies'])? $userdata[0]['patient_allergies'] : '');?></textarea>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <div class="col-xs-5 col-xs-offset-3">
                        <a href="medication_tab.php"><button  class="btn btn-primary btn-back" name="submit" type="button" id="btn-back" >Back</button></a>
                        <!--<a href="medication_tab.php"><button  class="btn btn-primary btn-back" name="submit" type="submit"  id="btn-back" >Back</button></a>-->
                        <a href="others_tab.php"><button  class="btn btn-primary btn-next" name="submit"  type="submit" id="btn-next3" style="margin-left:10px;">Save and Continue</button></a>
                    </div>
                </div>
            </div>
            </div>
</form>
    </div>
        <div class="col-md-12 col-xs-12" style="min-height:250px;"></div>
    </div>  
        </div>
    </div>
<style>
    .home{
        background: #fff !important;
    }
    
</style>
<?php require_once("includes/mfooter.php"); ?>