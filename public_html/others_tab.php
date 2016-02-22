<?php
require_once("includes/top.php");
require_once("includes/authentication.php");
?>
<?php require_once("includes/mheader.php"); ?>  
<?php include 'includes/leftsidebar.php'; ?>
<script src="<?= $remotelocation; ?>includes/js/jquery.js"></script>
<script src="<?= $remotelocation; ?>includes/js/jquery.themepunch.plugins.min.js"></script>			
<script src="<?= $remotelocation; ?>includes/js/jquery.themepunch.revolution.min.js"></script>
<script src="<?= $remotelocation; ?>includes/js/medical.min.js"></script>	
<script src="<?= $remotelocation; ?>includes/js/jquery.validate.min.js"></script>
<!---<script src="<?= $remotelocation; ?>includes/js/bootstrap.min.js"></script>--->    

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
                <li><a href="<?= $remotelocation . "profile_steps.php"; ?>" style="cursor:pointer;" >Address</a></li>
                <li><a href="<?= $remotelocation . "medication_tab.php"; ?>" style="cursor:pointer;">Problems </a></li>
                <li><a href="<?= $remotelocation . "medication_tab.php"; ?>" style="cursor:pointer;" >Medication </a></li>
                <li><a href="<?= $remotelocation . "allergies_tab.php"; ?>" style="cursor:pointer;">Allergies </a></li>
                <li class="active"><a href="<?= $remotelocation . "others_tab.php"; ?>" style="cursor:pointer;">Others </a></li>                  
            </ul>
        </div>
        <?php
        $message = '';
        $email = $_SESSION["emp_email"];
        if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == '_otherstab') {
            $insurance = trim($_POST['insurance']);
            $medicare = trim($_POST['medicare']);
            $medicaid = trim($_POST['medicaid']);
            $vainsurance = trim($_POST['vainsurance']);
          //  $hourlyfees = trim($_POST['hourlyfees']);
            $summary = trim($_POST['summary']);
            $inscompanies = trim($_POST['inscompanies']);
            $custominsurancedetail = trim($_POST['custominsurancedetail']);
            //,patient_pay_hour_fees ='" . trim(mysql_real_escape_string($hourlyfees)) . "'
            $updateOthers = $db->Execute("update", "update " . PATIENTS . "  SET   patient_is_insured='" . trim(mysql_real_escape_string($insurance)) . "',patient_is_medicare='" . trim(mysql_real_escape_string($medicare)) . "', patient_has_medicarid='" . trim(mysql_real_escape_string($medicaid)) . "',patient_has_vainsurance ='" . trim(mysql_real_escape_string($vainsurance)) . "', patient_inscompanies='" . trim(mysql_real_escape_string($inscompanies)) . "', patient_custominsurancedetail='" . trim(mysql_real_escape_string($custominsurancedetail)) . "',patient_healthsummary ='" . trim(mysql_real_escape_string($summary)) . "',patient_isprofilecomplete = 1 where `patient_email`='" . $email . "'");
            echo"<script>window.location.href='patientdashboard.php'</script>";
        } else {
            $userdata = $db->Execute("select", "select  patient_is_insured,patient_is_medicare,patient_has_medicarid,patient_has_vainsurance,patient_inscompanies,patient_custominsurancedetail,patient_pay_hour_fees,patient_healthsummary FROM " . PATIENTS . " where patient_email ='" . $email . "'");
        }
        if (isset($userdata[0]['patient_pay_hour_fees']) && !empty($userdata[0]['patient_pay_hour_fees'])) {
            list($firstfigure, $secondfigure) = explode(".", $userdata[0]['patient_pay_hour_fees']);
            if ($secondfigure > 0) {
                $userdata[0]['patient_pay_hour_fees'] = $userdata[0]['patient_pay_hour_fees'];
            } else {
                $userdata[0]['patient_pay_hour_fees'] = $firstfigure;
            }
        }
        ?>

        <form id="accountForm" method="post" class="form-horizontal" action="<?php $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="action" value="_otherstab">
            <div class="tab-content-new">
                <div class="tab-pane1" id="others-tab">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Insurance Status</label>
                        <div class="col-xs-8">
                            <select class="form-control" name="insurance" id="insurance">
                                <option value="">Please Select</option>
                                <option value="1"<?= ($userdata[0]['patient_is_insured'] == '1' ? 'selected="selected"' : ''); ?>>Yes</option>
                                <option value="2"<?= ($userdata[0]['patient_is_insured'] == '2' ? 'selected="selected"' : ''); ?>>No</option>
                                <option value="3"<?= ($userdata[0]['patient_is_insured'] == '3' ? 'selected="selected"' : ''); ?>>Don't Know</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Medicare</label>
                        <div class="col-xs-8">
                            <select class="form-control" name="medicare" id="medicare">
                                <option value="">Please Select</option>
                                <option value="1"<?= ($userdata[0]['patient_is_medicare'] == '1' ? 'selected="selected"' : ''); ?>>Yes</option>
                                <option value="2"<?= ($userdata[0]['patient_is_medicare'] == '2' ? 'selected="selected"' : ''); ?>>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Medicaid</label>
                        <div class="col-xs-8">
                            <select class="form-control" name="medicaid" id="medicaid">
                                <option value="">Please Select</option>
                                <option value="1"<?= (isset($userdata[0]['patient_has_medicarid']) && $userdata[0]['patient_has_medicarid'] == '1' ? 'selected="selected"' : ''); ?>>Yes</option>
                                <option value="2"<?= (isset($userdata[0]['patient_has_medicarid']) && $userdata[0]['patient_has_medicarid'] == '2' ? 'selected="selected"' : ''); ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">VA Insurance</label>
                        <div class="col-xs-8">
                            <select class="form-control" name="vainsurance" id="vainsurance">
                                <option value="">Please Select</option>
                                <option value="1"<?= (isset($userdata[0]['patient_has_vainsurance']) && $userdata[0]['patient_has_vainsurance'] == '1' ? 'selected="selected"' : ''); ?>>Yes</option>
                                <option value="2"<?= (isset($userdata[0]['patient_has_vainsurance']) && $userdata[0]['patient_has_vainsurance'] == '2' ? 'selected="selected"' : ''); ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Name Of Your Insurance Company</label>
                        <div class="col-xs-8">
                            <select class="form-control" name="inscompanies" id="inscompanies">

<?php
$inscompdata = $db->Execute("select", "select  * FROM " . INSURANCE);
foreach ($inscompdata as $ins):

    if (isset($userdata[0]['patient_inscompanies']) && $ins['ins_id'] == $userdata[0]['patient_inscompanies']) {

        echo '<option value="' . $ins['ins_id'] . '" selected="selected">' . $ins['ins_name'] . '</option>';
    } else 
	{
        echo '<option value="' . $ins['ins_id'] . '"  >' . $ins['ins_name'] . '</option>';
    }

endforeach;
?>  


                            </select>
                        </div>
                    </div>
<?php if($userdata[0]['patient_inscompanies']==367){ ?> 
<div class="form-group"  style= "display:block;">
 <?php } else {  ?>
 <div class="form-group" id="custominsurance" style= "display:none;">
 <?php } ?>
                   
                        <label class="col-xs-3 control-label">Add Other Insurance Detail </label>
                        <div class="col-xs-8" >
                            <input type="text" class="form-control" name="custominsurancedetail" value="<?= (isset($userdata[0]['patient_custominsurancedetail']) && !empty($userdata[0]['patient_custominsurancedetail']) ? $userdata[0]['patient_custominsurancedetail'] : '' ) ?>" />
                        </div>
                    </div>

                    <div class="form-group" style="display:none;">
                        <label class="col-xs-3 control-label">How much patient is willing to pay per hour for service?</label>
                        <div class="col-xs-8" >
                            <input type="text" class="form-control" placeholder="" maxlength="5" name="hourlyfees" onkeypress="return isNumberKey(event)" id="hourlyfees" value="<?= (isset($userdata[0]['patient_pay_hour_fees']) && !empty($userdata[0]['patient_pay_hour_fees']) ? $userdata[0]['patient_pay_hour_fees'] : '' ) ?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Summary of health problems</label>
                        <div class="col-xs-8">
                            <textarea class="form-control" name="summary" rows="3" coloumn="3"><?= (isset($userdata[0]['patient_healthsummary']) && !empty($userdata[0]['patient_healthsummary']) ? $userdata[0]['patient_healthsummary'] : '' ) ?></textarea>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <div class="col-xs-5 col-xs-offset-3">
                            <a href="allergies_tab.php"><button  class="btn btn-primary btn-back" name="submit" type="button" id="btn-back" >Back</button></a>
                            <!--<a href="allergies_tab.php"><button  class="btn btn-primary btn-back" name="submit" type="submit" id="btn-back" >Back</button></a>-->
                            <!--<a href="profile_steps.php"><button  class="btn btn-primary btn-back" name="submit" type="button" id="btn-back" >Back</button></a>-->
                            <button  class="btn btn-primary" name="submit" type="submit" id="submit" style="margin-left:10px;" onclick="return validatesubmitaction()">Submit</button></a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
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
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function validatesubmitaction() {
        var insurance = $('#insurance').val();
        var vainsurance = $('#vainsurance').val();
        var hourlyfees = $('#hourlyfees').val();
        if (insurance == '') {
            alert("Please enter insurance status");
            return false;
        } else if (vainsurance == '') {
            alert("Please enter vainsurance");
            return false;
        } else if (hourlyfees == '') {
            alert("Please enter hourlyfees");
            return false;
        }

    }

    function changeinsurancetype() {

        var insurance = $("#vainsurance").val();
        if (insurance == '1') {
             $("#insurance_ajax").css('display', 'none');
            $("#custominsurance").hide();
        } else {
          $("#insurance_ajax").css('display', 'block'); 
          
        }
    }
<?php
//if (isset($userdata[0]['patient_has_vainsurance']) && $userdata[0]['patient_has_vainsurance'] == 0) {
    ?>
        changeinsurancetype();
    <?php
//}
?>
</script>
<script type="text/javascript">
    $("#inscompanies").on('change', function (event) {
        if ($(this).val() == '367') {
            $("#custominsurance").show();
        } else {
            $("#custominsurance").hide();
        }
    });
</script>
<?php require_once("includes/mfooter.php"); ?>