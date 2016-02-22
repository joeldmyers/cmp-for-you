<?php

require_once("includes/top.php");

require_once("includes/doc_authentication.php");

global $db;

$bodyparts = $db->Execute("select", "select bodyparts_id,patient_bodyparts FROM " . BODYPARTS);

?>

<?php require_once("includes/docheader.php"); ?>   

<?php require_once("includes/docleftsidebar.php"); ?>   

    <script src="<?=$remotelocation;?>includes/js/jquery.js"></script>

    <script src="<?=$remotelocation;?>includes/js/jquery.themepunch.plugins.min.js"></script>			

    <script src="<?=$remotelocation;?>includes/js/jquery.themepunch.revolution.min.js"></script>

    <script src="<?=$remotelocation;?>includes/js/medical.min.js"></script>	

    <script src="<?=$remotelocation;?>includes/js/jquery.validate.min.js"></script>

    <!--<script src="<?=$remotelocation;?>includes/js/bootstrap.min.js"></script>-->    

 

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

            <li><a href="<?=$remotelocation."doctorprofile_steps.php";?>" style="cursor:pointer;">Address</a></li>

            <li class="active"><a href="<?=$remotelocation."doctorpersonal_detailtab.php";?>" style="cursor:pointer;">Personal Information </a></li>

            <li><a href="<?=$remotelocation."doctorspeciality_tab.php";?>" style="cursor:pointer;">Speciality </a></li> 
           
        </ul>

    </div>

     <?php 

    $message = '';

    $email = $_SESSION["emp_email"];

    if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == '_additionaltab'){

        $pacid = trim($_POST['pacid']);

        $npi = trim($_POST['npi']);

        $professional_enroll_id = trim($_POST['professional_enroll_id']);

        $group_practice_pac_id = trim($_POST['group_practice_pac_id']);

        $no_group_practice_members = trim($_POST['no_group_practice_members']);

        $medical_school = trim($_POST['medical_school']);

        $credential = trim($_POST['credential']);

        $graduation_year = trim($_POST['graduation_year']);

        $updateProblems = $db->Execute("update", "update " . MEDIC . "  SET   pac_id='" . trim(mysql_real_escape_string($pacid)) . "',npi='" . trim(mysql_real_escape_string($npi)) . "',professional_enroll_id='" . trim(mysql_real_escape_string($professional_enroll_id)) . "',group_practice_pac_id='" . trim(mysql_real_escape_string($group_practice_pac_id)) . "',no_group_practice_members='" . trim(mysql_real_escape_string($no_group_practice_members)) . "',medical_school='" . trim(mysql_real_escape_string($medical_school)) . "',credential='" . trim(mysql_real_escape_string($credential)) . "',graduation_year='" . $graduation_year . "' where `email`='" . $email . "'");

        echo"<script>window.location.href='doctorspeciality_tab.php'</script>";

    }else{

        $userdata = $db->Execute("select", "select  pac_id,npi,professional_enroll_id,group_practice_pac_id,no_group_practice_members,medical_school,credential,graduation_year FROM " . MEDIC . " where email ='".$email."'");

        

       }

?>

    <form id="accountForm" method="post" class="form-horizontal" action="<?php $_SERVER['PHP_SELF']; ?>">

        <input type="hidden" name="action" value="_additionaltab">

        <div class="tab-content-new">

         <div class="tab-pane1" id="problems-tab">

             <div class="form-group">

                    <label class="col-xs-3 control-label">Pac Id</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="pacid" name="pacid" onkeypress="return isNumberKey(event)" value="<?=(isset($userdata[0]['pac_id']) && !empty($userdata[0]['pac_id']) ? $userdata[0]['pac_id'] : '');?>" />

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-xs-3 control-label">NPI</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="npi" name="npi"  onkeypress="return isNumberKey(event)" value="<?=(isset($userdata[0]['npi']) && !empty($userdata[0]['npi']) ? $userdata[0]['npi'] : '');?>" />

                    </div>

                </div>

                 <div class="form-group">

                    <label class="col-xs-3 control-label">Professional Enroll Id</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="professional_enroll_id" name="professional_enroll_id" onkeypress="return isNumberKey(event)" value="<?=(isset($userdata[0]['professional_enroll_id']) && !empty($userdata[0]['professional_enroll_id']) ? $userdata[0]['professional_enroll_id'] : '');?>" />

                    </div>

                </div>

             <div class="form-group">

                    <label class="col-xs-3 control-label">Group Practice Pac Id</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="group_practice_pac_id" name="group_practice_pac_id" onkeypress="return isNumberKey(event)" value="<?=(isset($userdata[0]['group_practice_pac_id']) && !empty($userdata[0]['group_practice_pac_id']) ? $userdata[0]['group_practice_pac_id'] : '');?>" />

                    </div>

                </div>

             <div class="form-group">

                    <label class="col-xs-3 control-label">No Of Group Practice Members</label>

                    <div class="col-xs-8">

                        <input type="number" class="form-control" name="no_group_practice_members" onkeypress="return isNumberKey(event)" value="<?=(isset($userdata[0]['no_group_practice_members']) && !empty($userdata[0]['no_group_practice_members']) ? $userdata[0]['no_group_practice_members'] : '');?>" id="no_group_practice_members" min="1" max="30" />

                    </div>

                </div>

             <div class="form-group">

                    <label class="col-xs-3 control-label">Credential</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" name="credential" value="<?=(isset($userdata[0]['credential']) && !empty($userdata[0]['credential']) ? $userdata[0]['credential'] : '');?>" id="credential"/>

                    </div>

                </div>

             <div class="form-group">

                    <label class="col-xs-3 control-label">Medical School Name</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" name="medical_school" value="<?=(isset($userdata[0]['medical_school']) && !empty($userdata[0]['medical_school']) ? $userdata[0]['medical_school'] : '');?>" id="medical_school"/>

                    </div>

                </div>

             <div class="form-group">

                    <label class="col-xs-3 control-label">Graduation Year</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" name="graduation_year" value="<?=(isset($userdata[0]['graduation_year']) && !empty($userdata[0]['graduation_year']) ? $userdata[0]['graduation_year'] : '');?>" id="graduation_year" onkeypress="return isNumberKey(event)" maxlength="4"/>

                    </div>

                </div>



                <div class="form-group" style="margin-top: 15px;">

                    <div class="col-xs-5 col-xs-offset-3">

                        <a href="doctorprofile_steps.php"><button  class="btn btn-primary btn-back" name="submit" type="button" id="btn-back" >Back</button></a>

                        <a href="doctorspeciality_tab.php"><button  class="btn btn-primary btn-next1" name="submit" type="submit" id="btn-next1" style="margin-left:10px;" onclick="return validatepersonalsteps()">Save and continue</button></a>

                    </div>

                </div>



            </div>

            </div>

      </form>

    </div> 

        </div>

      </div>

      </div>

<script>

  function isNumberKey(evt)

      {

         var charCode = (evt.which) ? evt.which : event.keyCode

         if (charCode > 31 && (charCode < 48 || charCode > 57))

            return false;



         return true;

      }

</script>

<script>

    function validatepersonalsteps(){

        var pacid = $('#pacid').val();

        var npi = $('#npi').val();

        var professional = $('#professional_enroll_id').val();

        var grouppacid = $('#group_practice_pac_id').val();

        var medical_school = $('#medical_school').val();

        var graduation_year = $('#graduation_year').val();

        if(pacid == ''){

            alert("Please enter pacid");

            return false;

        }else if(npi == ''){

            alert("Please enter npi");

            return false;

        }else if(professional == ''){

           alert("Please enter professional enroll id");

            return false; 

        }else if(grouppacid == ''){

            alert("Please enter group practice pac id");

            return false;

        }else if(medical_school == ''){

            alert("Please enter medical school name");

            return false;

        }else if(graduation_year == ''){

            alert("Please enter graduation year");

            return false;

        }else if(graduation_year.length < 4){

            alert("Please enter graduation year length equal to 4 ");

            return false;

        }

        

    }

</script>



<?php require_once("includes/mfooter.php"); ?>