<?php
require_once("includes/top.php");

require_once("includes/authentication.php");
echo $_POST['action'];
if ($_POST['action'] == 'sendmessage1' && $_POST['action'] != "") {
echo "<pre>"; 
    mysql_query("BEGIN");
    try {
		$image=$_FILES['file']['name'];
		$tmp  =$_FILES['file']['tmp_name'];
		$upload="patient-files/".$image;
		$upd=move_uploaded_file($tmp,$upload);
        $sql = "insert into  messages (p_id,d_id,subject,msg,file) values ('" . trim(mysql_real_escape_string($_POST['sender_id'])) . "','" . trim(mysql_real_escape_string($_POST['recevier_id'])) . "','" . trim(mysql_real_escape_string($_POST['subject'])) . "','" . trim(mysql_real_escape_string($_POST['message'])) . "', '".$image."' ) ";
	   
        $result = mysql_query($sql);
		
        if (!$result) {
            throw new Exception(mysql_error());
        }
        mysql_query("COMMIT");
        //echo "Your message has been sent";
		print '<script type="text/javascript">';
		print 'alert("Your message has been sent")';
		print '</script>'; 
		print "<script>location.href = 'searchdoctor.php'</script>";
		exit();
		//header('location:searchdoctor.php');
    } catch (Exception $e) {
        mysql_query("ROLLBACK");
        $error = $e->getMessage();
        echo $error;
    }



//    $subject = trim($_POST['subject']);
//
//    $receiverid = trim($_POST['receiver']);
//
//    $senderid = trim($_POST['sender']);
//
//    $message = trim($_POST['message']);
//
//    //echo "insert into " . MESSAGES . " (message,sender_id,receiver_id,subject,date) values ('" . trim(mysql_real_escape_string($message)) . "' , '" .$senderid. "','" .$receiverid. "','" . trim(mysql_real_escape_string($subject)) . "','" . date('Y-m-d') . "')" ; exit;
//
//    $saveMessage = $db->Execute("insert", "insert into " . MESSAGES . " (message,sender_id,receiver_id,subject,user_type,date) values ('" . trim(mysql_real_escape_string($message)) . "' , '" . $senderid . "','" . $receiverid . "','" . trim(mysql_real_escape_string($subject)) . "','DOCTOR','" . date('Y-m-d') . "') ");
//
//    $message = "Your message has been sent";
//
//    echo $message;
//
//    exit();
} elseif ($_POST['action'] == 'changemsgboxcolor_doctor') {

    $sql = "update  messages set read_status='1' where msend_id='" . trim(mysql_real_escape_string($_POST['id'])) . "' ";
    $result = mysql_query($sql);

    $sql = "update  reply set read_status='1' where  msend_id='" . trim(mysql_real_escape_string($_POST['id'])) . "' and reciever_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' ";
    $result = mysql_query($sql);


//    $sql = "select * from  messages  where d_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' and read_status='0' ";
//    $result = mysql_query($sql);
//    $num = mysql_num_rows($result);
//    $count = $num . ' Unread&nbsp';

    $sql1 = " select * from messages  where d_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "'  and read_status='0'";
    $result1 = mysql_query($sql1);
    $num1 = mysql_num_rows($result1);

    $sql2 = "select * from reply where reciever_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "'  and read_status='0' ";
    $result2 = mysql_query($sql2);
    $num2 = mysql_num_rows($result2);
    $total_msg = $num1 + $num2 . ' Unread';


    echo $total_msg;
    exit();
} elseif ($_POST['action'] == 'changemsgboxcolor_patient') {

    $sql = "update reply set read_status='1' where msend_id='" . trim(mysql_real_escape_string($_POST['id'])) . "' ";
    $result = mysql_query($sql);

    $sql = "select * from  reply  where reciever_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' and read_status='0' ";
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);
    $count = $num . ' Unread&nbsp';

    echo $count;
    exit();
} elseif ($_POST['action'] == 'doctorsendmessage') {

    $subject = trim($_POST['subject']);

    $receiverid = trim($_POST['receiver']);

    $senderid = trim($_POST['sender']);

    $message = trim($_POST['message']);

    //echo "insert into " . REPLY . " (message_id,replier_id,reply_text,date,reply_usertype) values ('" . $replyid . "' , '" .$replierid. "','" .trim(mysql_real_escape_string($replytext)). "','"  . date('Y-m-d') . "','PATIENT') "; exit;

    $saveMessage = $db->Execute("insert", "insert into " . MESSAGES . " (message,sender_id,receiver_id,subject,user_type,date) values ('" . trim(mysql_real_escape_string($message)) . "' , '" . $senderid . "','" . $receiverid . "','" . trim(mysql_real_escape_string($subject)) . "','PATIENT','" . date('Y-m-d') . "') ");

    $message = "Your message has been sent ";

    echo $message;

    exit();
} elseif ($_POST['action'] == 'replymessage') {


    mysql_query("BEGIN");
    try {
        $sql = "insert into  reply (msend_id,reciever_id,replier_id,msg_text,user_type) values ('" . trim(mysql_real_escape_string($_POST['replyid'])) . "','" . trim(mysql_real_escape_string($_POST['recieverid'])) . "','" . trim(mysql_real_escape_string($_POST['replierid'])) . "','" . trim(mysql_real_escape_string($_POST['replytext'])) . "','Patient' ) ";
        $result = mysql_query($sql);
        if (!$result) {
            throw new Exception(mysql_error());
        }
        $mreply_id = mysql_insert_id();

        // echo "Reply have been sent successfully ";


        $sql = "select * from  reply as reply where  reply.mreply_id= '" . $mreply_id . "'  limit 1";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            while ($record = mysql_fetch_array($result)) {

                if ($record['user_type'] == 'Doctor') {
                    $sql1 = "select * from  medic  where doctor_id= '" . $record['replier_id'] . "' ";
                    $result1 = mysql_query($sql1);
                    $record1 = mysql_fetch_array($result1);
                    $name = ucwords(strtolower($record1['first_name'])) . "  " . ucwords(strtolower($record1['last_name']));
                    $image_path = "";
                    if (empty($record1['doctor_profilepic']) && $record1['gender'] == 'M') {
                        $image_path = "profilepic3.png";
                    } elseif (empty($record1['doctor_profilepic']) && $record1['gender'] == 'F') {
                        $image_path = "profilepic2.png";
                    } elseif (isset($record1['doctor_profilepic']) && !empty($record1['doctor_profilepic'])) {
                        $image_path = $record1['doctor_profilepic'];
                    }
                } else {

                    $sql1 = "select * from tbl_patients where patient_id= '" . $record['replier_id'] . "' ";
                    $result1 = mysql_query($sql1);
                    $record1 = mysql_fetch_array($result1);
                    $name = ucwords(strtolower($record1['patient_fname'])) . "  " . ucwords(strtolower($record1['patient_lname']));

                    if (empty($record1['patient_profilepic']) && $record1['patient_gender'] == 'm') {
                        $image_path = "patientmaleimg.png";
                    } elseif (empty($record1['patient_profilepic']) && $record1['patient_gender'] == 'f') {
                        $image_path = "patientfemaleimg.png";
                    } elseif (isset($record1['patient_profilepic']) && !empty($record1['patient_profilepic'])) {
                        $image_path = $record1['patient_profilepic'];
                    }
                }
                ?>
                <div>
                    <div class="col-md-1 docpanel-img">
                        <div class="docpanel-img">
                            <img width="100%" src="<?php echo $remotelocation . "includes/upload/profilepic/" . $image_path; ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="docpanel-text1"><h1><?php echo $record['user_type']; ?>:-  <?php echo $name; ?></h1><p><?php echo date("F j, Y H:i:s", strtotime($record['date_send'])) ?></p></div>
                    </div>
                    <div class="col-md-12" style="text-align:justify; margin-top:15px"><?php echo $record['msg_text'] ?></div>
                    <div class="clear"></div>
                </div> 
                <!-- reply messages show-->
                <?php
            }
        }

        mysql_query("COMMIT");
    } catch (Exception $e) {
        mysql_query("ROLLBACK");
        $error = $e->getMessage();
        echo $error;
    }


//    $replyid = trim($_POST['replyid']);
//
//    $replytext = trim($_POST['replytext']);
//
//    $replierid = trim($_POST['replierid']);
//
//    $receiverid = trim($_POST['recieverid']);
//
//    $subject = trim($_POST['subject']);
//
//    //echo "insert into " . REPLY . " (message_id,replier_id,reply_text,date,reply_usertype) values ('" . $replyid . "' , '" .$replierid. "','" .trim(mysql_real_escape_string($replytext)). "','"  . date('Y-m-d') . "','PATIENT') "; exit;
//
//    $saveReply = $db->Execute("insert", "insert into " . MESSAGES . " (message,sender_id,receiver_id,subject,date,user_type) values ('" . trim(mysql_real_escape_string($replytext)) . "','" . $replierid . "','" . $receiverid . "','" . $subject . "','" . date('Y-m-d') . "','PATIENT') ");
//
//    $message = "Reply have been sent successfully ";
//
//    echo $message;
//
//    exit();
} elseif ($_POST['action'] == 'doctorreplymessage') {



    mysql_query("BEGIN");
    try {
        $sql = "insert into  reply (msend_id,reciever_id,replier_id,msg_text,user_type) values ('" . trim(mysql_real_escape_string($_POST['replyid'])) . "','" . trim(mysql_real_escape_string($_POST['recieverid'])) . "','" . trim(mysql_real_escape_string($_POST['replierid'])) . "','" . trim(mysql_real_escape_string($_POST['replytext'])) . "','Doctor' ) ";
        $result = mysql_query($sql);
        if (!$result) {
            throw new Exception(mysql_error());
        }
        $mreply_id = mysql_insert_id();

        // echo "Reply have been sent successfully ";



        $sql = "select * from  reply as reply where  reply.mreply_id= '" . $mreply_id . "'  limit 1";
        $result = mysql_query($sql);
        if (mysql_num_rows($result) > 0) {
            while ($record = mysql_fetch_array($result)) {

                if ($record['user_type'] == 'Doctor') {
                    $sql1 = "select * from  medic  where doctor_id= '" . $record['replier_id'] . "' ";
                    $result1 = mysql_query($sql1);
                    $record1 = mysql_fetch_array($result1);
                    $name = ucwords(strtolower($record1['first_name'])) . "  " . ucwords(strtolower($record1['last_name']));
                    $image_path = "";

                    if (empty($record1['doctor_profilepic']) && $record1['gender'] == 'M') {
                        $image_path = "profilepic3.png";
                    } elseif (empty($record1['doctor_profilepic']) && $record1['gender'] == 'F') {
                        $image_path = "profilepic2.png";
                    } elseif (isset($record1['doctor_profilepic']) && !empty($record1['doctor_profilepic'])) {
                        $image_path = $record1['doctor_profilepic'];
                    }
                } else {

                    $sql1 = "select * from tbl_patients where patient_id= '" . $record['replier_id'] . "' ";
                    $result1 = mysql_query($sql1);
                    $record1 = mysql_fetch_array($result1);
                    $name = ucwords(strtolower($record1['patient_fname'])) . "  " . ucwords(strtolower($record1['patient_lname']));

                    if (empty($record1['patient_profilepic']) && $record1['patient_gender'] == 'm') {
                        $image_path = "patientmaleimg.png";
                    } elseif (empty($record1['patient_profilepic']) && $record1['patient_gender'] == 'f') {
                        $image_path = "patientfemaleimg.png";
                    } elseif (isset($record1['patient_profilepic']) && !empty($record1['patient_profilepic'])) {
                        $image_path = $record1['patient_profilepic'];
                    }
                }
                ?>
                <div>
                    <div class="col-md-1 docpanel-img">
                        <div class="docpanel-img">

                            <img width="100%" src="<?php echo $remotelocation . "includes/upload/profilepic/" . $image_path; ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="docpanel-text1"><h1><?php echo $record['user_type']; ?>:-  <?php echo $name; ?></h1><p><?php echo date("F j, Y H:i:s", strtotime($record['date_send'])) ?></p></div>
                    </div>
                    <div class="col-md-12" style="text-align:justify; margin-top:15px"><?php echo $record['msg_text'] ?></div>
                    <div class="clear"></div>
                </div> 
                <!-- reply messages show-->
                <?php
            }
        }

        mysql_query("COMMIT");
        //return json_decode($data);
    } catch (Exception $e) {
        mysql_query("ROLLBACK");
        $error = $e->getMessage();
        echo $error;
    }

//    $replyid = trim($_POST['replyid']);
//
//    $replytext = trim($_POST['replytext']);
//
//    $replierid = trim($_POST['replierid']);
//
//    $receiverid = trim($_POST['recieverid']);
//
//    $subject = trim($_POST['subject']);
//
//    //echo "insert into " . REPLY . " (message_id,replier_id,reply_text,date,reply_usertype) values ('" . $replyid . "' , '" .$replierid. "','" .trim(mysql_real_escape_string($replytext)). "','"  . date('Y-m-d') . "','PATIENT') "; exit;
//
//    $saveReply = $db->Execute("insert", "insert into " . MESSAGES . " (message,sender_id,receiver_id,subject,date,user_type) values ('" . trim(mysql_real_escape_string($replytext)) . "','" . $replierid . "','" . $receiverid . "','" . $subject . "','" . date('Y-m-d') . "','DOCTOR') ");
//
//    $message = "Reply have been sent successfully ";
//
//    echo $message;
//
//    exit();
} elseif ($_POST['action'] == '_getpatientimage') {

    $id = trim($_POST['id']);

    $gender = trim($_POST['gender']);

    if ($_POST['gender'] == 'm') {

        $getImage = $db->Execute("select", "select patient_male as img  FROM " . BODYPARTS . " where bodyparts_id = '" . $id . "' ");
    } elseif ($_POST['gender'] == 'f') {

        $getImage = $db->Execute("select", "select patient_female as img FROM " . BODYPARTS . " where bodyparts_id = '" . $id . "' ");
    }

    $imgPath = $remotelocation . "includes/images/bodyparts/" . $getImage[0]['img'];

    echo "<img src='" . $imgPath . "' border='0' style='width:250px;height:350px;' />";

    exit();
} elseif ($_POST['action'] == '_getsymptoms') {

    $id = trim($_POST['id']);

    $getData = $db->Execute("select", "select DISTINCT(symptom_descr),symptom_id  FROM " . SYMPTOMS . " where bodyparts_id = '" . $id . "' ");
    ?>

    <option value = "">Select State</option>

    <?php
    foreach ($getData as $bodysymptoms) {
        ?>

        <option value="<?php echo $bodysymptoms['symptom_id']; ?>"><?php echo $bodysymptoms['symptom_descr']; ?></option>

        <?php
    }
}
?>