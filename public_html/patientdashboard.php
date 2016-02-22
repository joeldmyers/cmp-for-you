<?php
require_once("includes/top.php");
require_once("includes/authentication.php");
$userid = trim($_SESSION["emp_email"]);
if (isset($_SESSION["emp_id"]) && isset($_SESSION["emp_email"]) && !empty($_SESSION["emp_id"]) && !empty($_SESSION["emp_email"])) {
    
}else{
    
    echo "<script>window.location.href='login.php'</script>";
    exit;
}
?>
<?php require_once("includes/mheader.php"); ?>
<?php include 'includes/leftsidebar.php'; ?>


            <div class="col-md-6 col-xs-12">
                <h2 class="pad_btm20 pad_top10 pad_left10">Patient Dashboard</h2>                
                <div class="col-sm-12 col-xs-12 centerbar mar_top20">
                    <div class="col-sm-6 col-xs-12 box1" onclick="window.location.href='<?=$remotelocation."mydoctors.php"; ?>';" style="cursor:pointer;">
                        <div class="col-sm-8 col-xs-8">
                            <h4>My Doctors</h4>
                            <p style="text-align: justify">
                                Select the doctor you want 
                               depending on Specialty & Fee

                            </p>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <div class="iconbox"><i class="fa fa-user"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 box2" onclick="window.location.href='<?=$remotelocation."message_center.php"; ?>';" style="cursor:pointer;">
                        <div class="col-sm-4 col-xs-4">
                            <div class="iconbox"><i class="fa fa-comments"></i></div>
                        </div>
                        <div class="col-sm-8 col-xs-8 text-right" >
                            <h4>Message Center</h4>
                            <p style="text-align: justify">
                                Send and   receive messages to a Doctor or 
                                Nurse of your Choice &  Select a
                                mutually convenient  Date & Time.
                            </p>
                        </div>

                    </div>
                    <div class="col-sm-6 col-xs-12 box3" onclick="window.location.href='<?=$remotelocation."manage_transactions.php"; ?>';" style="cursor:pointer;">
                        <div class="col-sm-8 col-xs-8">
                            <h4>My Transactions</h4>
                            <p style="text-align: justify">
                                Pay by Credit Card or Pay Pal                             
                                & Get  Video Consultation and 
                                Select a date and time for Online
                            </p>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <div class="iconbox"><i class="fa fa-usd"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 box4" onclick="window.location.href='<?=$remotelocation."profile_steps.php"; ?>';" style="cursor:pointer;">
                        <div class="col-sm-4 col-xs-4">
                            <div class="iconbox"><i class="fa fa-list-alt"></i></div>
                        </div>
                        <div class="col-sm-8 col-xs-8 text-right">
                            <h4>My Profile</h4>
                            <p style="text-align: justify">
                                Details of your symptoms, medications, 
                                allergies and Other Info
                            </p>
                        </div>

                    </div>
                </div>
           
            </div>
          <?php include 'includes/rightsidebar.php'; ?>
    </div>
    

<?php include 'includes/mfooter.php'; ?>
