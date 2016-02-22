<?php
require_once("includes/top.php");
require_once("includes/doc_authentication.php");
$userid = trim($_SESSION["emp_email"]);
if (isset($_SESSION["emp_id"]) && isset($_SESSION["emp_email"]) && !empty($_SESSION["emp_id"]) && !empty($_SESSION["emp_email"])) {
    
}else{
    
    echo "<script>window.location.href='doctor_login.php'</script>";
    exit;
}
?>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>


            <div class="col-md-6 col-xs-12">
                <h2 class="pad_btm20 pad_top10 pad_left10">Doctor Dashboard</h2>                
                <div class="col-sm-12 col-xs-12 centerbar mar_top20">
                    <div class="col-sm-6 col-xs-12 box1" onclick="window.location.href='<?=$remotelocation."PatientSearch" ."/". "searchpatient.php"; ?>';" style="cursor:pointer;">					
                        <div class="col-sm-8 col-xs-8">
                            <h4>My Patients</h4>
                            <p>
                                See The Profile of Your Patients
                                Who want Online  Consultation
                            </p>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <div class="iconbox"><i class="fa fa-user"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 box2" onclick="window.location.href='<?=$remotelocation."doctormessage_center.php"; ?>';" style="cursor:pointer;">
                        <div class="col-sm-4 col-xs-4">
                            <div class="iconbox"><i class="fa fa-comments"></i></div>
                        </div>
                        <div class="col-sm-8 col-xs-8 text-right" >
                            <h4>Message Center</h4>
                            <p style="text-align: justify">
                                Send and   receive  messages 
                                to your  Patients 
                                & Select a mutually convenient  Date & Time
                            </p>
                        </div>

                    </div>
                    <div class="col-sm-6 col-xs-12 box3" onclick="window.location.href='<?=$remotelocation."doctormanage_transactions.php"; ?>';" style="cursor:pointer;">
                        <div class="col-sm-8 col-xs-8">
                            <h4>My Transactions</h4>
                            <p style="text-align: justify">
                                Money Received For Online Consultation
                                and Other Transactions
                            </p>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <div class="iconbox"><i class="fa fa-usd"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 box4" onclick="window.location.href='<?=$remotelocation."doctorprofile_steps.php"; ?>';" style="cursor:pointer;">
                        <div class="col-sm-4 col-xs-4">
                            <div class="iconbox"><i class="fa fa-list-alt"></i></div>
                        </div>
                        <div class="col-sm-8 col-xs-8 text-right">
                            <h4>My Profile</h4>
                            <p style="text-align: justify">
                                Your  Qualifications and Experience
                                Colleges where you graduated from 
                                and Other Info
                            </p>
                        </div>

                    </div>
                </div>
           
            </div>
          <?php include 'includes/docrightsidebar.php'; ?>
    </div>
    

<?php include 'includes/docfooter.php'; ?>
