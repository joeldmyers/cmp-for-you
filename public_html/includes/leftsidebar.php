<div class="main_container container-fluid mar_btm50 pad_btm50">
        <div class="col-sm-12 ">
<?php
require_once("includes/top.php"); 
$user_id = $_SESSION['emp_id'];
//$total_msg = $db->Execute("select", "select * from " . MESSAGES . " where receiver_id = '" . $user_id . "' and  read_status='0'");


        $sql1="select * from reply where reciever_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' and read_status='0' ";
        $result1 = mysql_query($sql1);
        $num1 = mysql_num_rows($result1);
        $total_msg = $num1 . ' Unread';
?>
            <div class="col-md-3 col-xs-12 sidebar">
                <?php if(empty($_SESSION['patient_profilepic']) && $_SESSION['patient_gender'] == 'f'){?>
                <div class="col-sm-5 pad_btm20"><img src="<?=$remotelocation."includes/upload/profilepic/patientfemaleimg.jpg"; ?>" class="img-responsive" /></div>
                <?php }else if(empty($_SESSION['patient_profilepic']) && $_SESSION['patient_gender'] == 'm'){?>
                <div class="col-sm-5 pad_btm20"><img src="<?=$remotelocation."includes/upload/profilepic/patientmaleimg.png"; ?>" class="img-responsive" /></div>
                <?php }else if(isset ($_SESSION['patient_profilepic']) && !empty($_SESSION['patient_profilepic'])){?>
                <div class="col-sm-5 pad_btm20"><img src="<?=$remotelocation."includes/upload/profilepic/".$_SESSION['patient_profilepic']; ?>" class="img-responsive" /></div>
                <?php }?>
                <div class="col-sm-7 pad_top20"><h3 style="font-size:18px;"><?=(isset($_SESSION["emp_name"]) ? ucfirst($_SESSION["emp_name"]) : '');?></h3> <br /><?=(isset($_SESSION["emp_age"]) && !empty($_SESSION["emp_age"]) ? ucfirst($_SESSION["emp_age"])." yr" : '');?></div>
                <div class="clear"></div>
                <ul class="sidemenu">                    
                    <li><a href="<?=$remotelocation."message_center.php";?>"><i class="fa fa-comments"></i>Message Center<?php if(count($total_msg)>0) {?>(<span style="color:green;" id="comment_count"><?php echo $total_msg."&nbsp"; ?></span>)<?php }?></a></li>
                    <li><a href="<?=$remotelocation."manage_transactions.php"; ?>"><i class="fa fa-usd"></i>My Transactions</a></li>
                    <li><a href="<?=$remotelocation."profile_steps.php"; ?>"><i class="fa fa-list-alt"></i>My Profile</a></li>
                   <!-- <li><a href="javascript:void(0);" onclick="window.open('<?=$remotelocation."video_appointment.php"; ?>', 'Video Appointment', 'width=500, height=500');"><i class="fa fa-video-camera"></i>Video Appointment</a></li>-->
               
                 <li><a href="<?=$remotelocation."manage_booking.php"; ?>"><i class="fa fa-bookmark"></i>Manage Video Appointment</a></li>
				 <li><a href="<?=$remotelocation."today_manage_booking.php"; ?>"><i class="fa-video-camera"></i>Today's Video Appointment</a></li>
				 <li><a href="<?=$remotelocation."patient_appointment_details.php"; ?>"><i class="fa fa-bookmark"></i>Appointment Booking Details</a></li>
                    <li><a href="<?=$remotelocation."changeprofilepic.php"; ?>"><i class="fa fa-user"></i>Update Photo</a></li>
<!--                    <li><a href="#"><i class="fa fa-question"></i>Help & Support</a></li>-->
                    <li><a href="<?=$remotelocation."logout.php"; ?>"><i class="fa fa-power-off"></i>Logout</a></li>
                </ul>
            </div>