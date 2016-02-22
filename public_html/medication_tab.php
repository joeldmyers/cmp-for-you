<?php
require_once("includes/top.php");
require_once("includes/authentication.php");
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
    <div class="tabs-new margin-top-40">
        <ul class="nav-new nav-tabs-new">
            <li><a href="<?=$remotelocation."profile_steps.php";?>" style="cursor:pointer;">Address</a></li>
            <li><a href="<?= $remotelocation . "problems_tab.php"; ?>" style="cursor:pointer;" >Problems </a></li>
            <li class="active"><a href="<?=$remotelocation."medication_tab.php"; ?>" style="cursor:pointer;" >Medication </a></li>
            <li><a href="<?=$remotelocation."allergies_tab.php";?>" style="cursor:pointer;">Allergies </a></li>
            <li><a href="<?= $remotelocation . "others_tab.php"; ?>" style="cursor:pointer;">Others </a></li>  
        </ul>
    </div>
    
    <?php 
    $medication = $db->Execute("select", "select  medication_id,medication_name FROM " . MEDICATION);
    $message = '';
    $email = $_SESSION["emp_email"];
    if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == '_medicationtab'){
        $medication1 = trim($_POST['medication1']);
        $medication2 = trim($_POST['medication2']);
        $medication3 = trim($_POST['medication3']);
        $updateMedication = $db->Execute("update", "update " . PATIENTS . "  SET  patient_medication1='" . trim(mysql_real_escape_string($medication1)) . "',patient_medication2='" . trim(mysql_real_escape_string($medication2)) . "',patient_medication3='" . trim(mysql_real_escape_string($medication3)) . "' where `patient_email`='" . $email . "'");
        echo"<script>window.location.href='allergies_tab.php'</script>";
    }else{
        $userdata = $db->Execute("select", "select  patient_medication1,patient_medication2,patient_medication3 FROM " . PATIENTS . " where patient_email ='".$email."'");
       }
    
    ?>

<form id="accountForm" method="post" class="form-horizontal" action="<?php $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="action" value="_medicationtab">
        <div class="tab-content-new">
         <div class="tab-pane1" id="medication-tab">
                <div class="form-group">
                    <label class="col-xs-3 control-label">Primary Medication</label>
                    <div class="col-xs-5" style="width:41.6%%;">
                            <select class="form-control" name="medication1" id="medications1">
                                <option value="">Select Medication</option>
                                <?php
                                foreach ($medication as $value) {

                                    if (isset($value['medication_name']) && $value['medication_id'] == $userdata[0]['patient_medication1']) {
                                        echo '<option value="' . $value['medication_id'] . '" selected="selected" >' . $value['medication_name'] . '</option>';
                                    } else {
                                        echo '<option id="' . $value['medication_id'] . '" value="' . $value['medication_id'] . '">' . $value['medication_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-3 control-label">Secondary Medication</label>
                    <div class="col-xs-5" style="width:41.6%%;">
                    <select class="form-control" name="medication2" id="medications2">
                                <option value="">Select Medication</option>
                                <?php
                                foreach ($medication as $value) {

                                    if (isset($value['medication_name']) && $value['medication_id'] == $userdata[0]['patient_medication2']) {
                                        echo '<option value="' . $value['medication_id'] . '" selected="selected" >' . $value['medication_name'] . '</option>';
                                    } else {
                                        echo '<option id="' . $value['medication_id'] . '" value="' . $value['medication_id'] . '">' . $value['medication_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                </div>
                    </div>

                <div class="form-group">
                    <label class="col-xs-3 control-label">Tertiary Medication</label>
                    <div class="col-xs-5" style="width:41.6%%;">
                    <select class="form-control" name="medication3" id="medications3">
                                <option value="">Select Medication</option>
                                <?php
                                foreach ($medication as $value) {

                                    if (isset($value['medication_name']) && $value['medication_id'] == $userdata[0]['patient_medication3']) {
                                        echo '<option value="' . $value['medication_id'] . '" selected="selected" >' . $value['medication_name'] . '</option>';
                                    } else {
                                        echo '<option id="' . $value['medication_id'] . '" value="' . $value['medication_id'] . '">' . $value['medication_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                </div>
                    </div>

                <div class="form-group" style="margin-top: 15px;">
                    <div class="col-xs-5 col-xs-offset-3">
                        <a href="<?=$remotelocation.'problems_tab.php'?>"><button  class="btn btn-primary btn-back" name="submit" type="button" id="btn-back" >Back</button></a>
                        <!--<a href="problems_tab.php"><button  class="btn btn-primary btn-back1" name="submit"  type="submit" id="btn-back" >Back</button></a>-->
                        <a href="<?=$remotelocation.'allergies_tab.php'?>"><button  class="btn btn-primary btn-next" name="submit"  type="submit" id="btn-next2" style="margin-left:10px;" onclick="return validatemedicationsteps()">Save and Continue</button></a>
                    </div>
                </div>
            </div>
            </div>
    </form>
    
    <div class="col-md-12 col-xs-12" style="min-height:200px;"></div>
    </div>
</div>  
        </div>
    </div>
<style>
    .home{
        background: #fff !important;
    }
    
</style>
<script>
    function validatemedicationsteps(){
        var medications1 = $('#medications1').val();
        if(medications1 == ''){
            alert("Please enter Primary Medication");
            return false;
        }
        
    }
</script>
<?php require_once("includes/mfooter.php"); ?>