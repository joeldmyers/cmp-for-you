<div class="main_container container-fluid mar_btm50 pad_btm50">
    <div class="col-sm-12 ">
        <?php
        $user_id = $_SESSION['emp_id'];
//$total_msg = $db->Execute("select", "select * from messages where d_id = '" . $user_id . "' and  read_status='0'");
//        $sql = "select * from  messages  where d_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' and read_status='0' ";
//        $result = mysql_query($sql);
//        $numc = mysql_num_rows($result);
//        $total_msg = $numc . ' Unread';
        
        $sql1=" select * from messages  where d_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' and read_status='0'";
        $result1 = mysql_query($sql1);
        $num1 = mysql_num_rows($result1);

        $sql2="select * from reply where reciever_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' and read_status='0' ";
        $result2 = mysql_query($sql2);
        $num2 = mysql_num_rows($result2);
        $total_msg = $num1+$num2 . ' Unread';
        require_once("includes/top.php");
        ?>
        <div class="col-md-3 col-xs-12 sidebar">
            <?php if (empty($_SESSION['doctor_profilepic']) && $_SESSION['doctor_gender'] == 'F') { ?>
                <div class="col-sm-5 pad_btm20"><img src="<?= $remotelocation . "includes/upload/profilepic/profilepic2.png"; ?>" class="img-responsive" /></div>
            <?php } else if (empty($_SESSION['doctor_profilepic']) && $_SESSION['doctor_gender'] == 'M') { ?>
                <div class="col-sm-5 pad_btm20"><img src="<?= $remotelocation . "includes/upload/profilepic/profilepic3.png"; ?>" class="img-responsive" /></div>
            <?php } else if (isset($_SESSION['doctor_profilepic']) && !empty($_SESSION['doctor_profilepic'])) { ?>
                <div class="col-sm-5 pad_btm20"><img src="<?= $remotelocation . "includes/upload/profilepic/" . $_SESSION['doctor_profilepic']; ?>" class="img-responsive" /></div>
<?php } ?>
            <div class="col-sm-7 pad_top20"><h3 style="font-size:18px;"><?= (isset($_SESSION["emp_name"]) ? ucfirst($_SESSION["emp_name"]) : ''); ?></h3><br /><?php (isset($_SESSION["credential"]) && !empty($_SESSION["credential"]) ? ucwords($_SESSION["credential"]) : ''); ?></div>
            <div class="clear"></div>
            <ul class="sidemenu">                    
                <li><a href="<?= $remotelocation . "doctormessage_center.php"; ?>"><i class="fa fa-comments"></i>Message Center<?php if (!empty($total_msg)) { ?>( <span style="color:green;" id="comment_count"><?php echo $total_msg . "&nbsp"; ?></span>)<?php } ?></a></li>
                <li><a href="<?= $remotelocation . "doctormanage_transactions.php"; ?>"><i class="fa fa-usd"></i>My Transactions</a></li>
				<li><a href="<?=$remotelocation."doctormanage_bookings.php"; ?>"><i class="fa fa-bookmark"></i>Patient's Video Appointment</a></li>				<li><a href="<?=$remotelocation."doctortodoctor_bookings.php"; ?>"><i class="fa fa-bookmark"></i>Doctor's Video Appointment</a></li>
				<li><a href="<?=$remotelocation."today_doctormanage_booking.php"; ?>"><i class="fa fa-bookmark"></i>Today's Video Appointment</a></li>
                
				<li><a href="<?=$remotelocation."doctormanage_appointment.php"; ?>"><i class="fa fa-bookmark"></i>Appointment Listing</a></li>
                
                <li><a href="<?= $remotelocation . "doctorprofile_steps.php"; ?>"><i class="fa fa-list-alt"></i>My Profile</a></li>
                
                    <!--<li><a href="javascript:void(0);" onclick="window.open('<?=$remotelocation."video_appointment.php"; ?>', 'Video Appointment', 'width=500, height=500');"><i class="fa fa-video-camera"></i>Video Appointment</a></li>
                 
               <li><a href="<?= $remotelocation . "docmypatients.php"; ?>"><i class="fa fa-user-plus"></i>My Patients</a></li>-->
                <li><a href="<?= $remotelocation . "doctorchangeprofilepic.php"; ?>"><i class="fa fa-user"></i>Update Photo</a></li>
<!--                    <li><a href="#"><i class="fa fa-question"></i>Help & Support</a></li>-->
                <li><a href="<?= $remotelocation . "doctorlogout.php"; ?>"><i class="fa fa-power-off"></i>Logout</a></li>
            </ul>
        </div>