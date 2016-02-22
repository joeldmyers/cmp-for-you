<?php
require_once("includes/top.php");
require_once("includes/doc_authentication.php");
if (isset($_SESSION['emp_id']) && $_SESSION['emp_id'] > 0) {
    //echo "SELECT * FROM " . MESSAGES . " AS `messages` INNER JOIN " . PATIENTS . " AS `patient` ON patient.patient_id = messages.sender_id WHERE (messages.receiver_id= '".$_SESSION['emp_id']."' and messages.user_type = 'DOCTOR') ORDER BY messages.date desc LIMIT 0,10"; exit;
    // $doctorMessages = $db->Execute("select", "SELECT * FROM " . MESSAGES . " AS `messages` INNER JOIN " . PATIENTS . " AS `pat` ON pat.patient_id = messages.sender_id WHERE (messages.receiver_id= '".$_SESSION['emp_id']."' and messages.user_type = 'DOCTOR') ORDER BY messages.message_id desc");

    $sql = "select * from  messages as msg inner join  tbl_patients  as pt on msg.p_id=pt.patient_id where  msg.d_id= '" . $_SESSION['emp_id'] . "'  ORDER BY msg.msend_id desc";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        while ($record = mysql_fetch_array($result)) {
            $doctorMessages[] = $record;
        }
    }
}
$errorMsg = '';
?>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>
<script>
    function viewbox(id)
    {
        row = "row_" + id;
        var dataString = 'action=changemsgboxcolor_doctor&id=' + id;

        $.ajax({
            type: "POST",
            url: "<?= $remotelocation . "getajaxdata.php" ?>",
            data: dataString,
            success: function (data) {
                if (data)
                {
                    $("#comment_count").html(data);

                    $("#" + row).css("background-color", "");
                }
            }
        });

        ids = "box_" + id; // console.log(ids);
        if ($("#" + ids).hasClass("hidden"))
        {
            $("#" + ids).removeClass('hidden');
        } else {
            $("#" + ids).addClass('hidden');
        }
    }
</script>
<script>
    function viewsmallbox(id)
    {
        ids = "box1_" + id; // console.log(ids);
        if ($("#" + ids).hasClass("hidden"))
        {
            $("#" + ids).removeClass('hidden');
        } else {
            $("#" + ids).addClass('hidden');
        }
    }
</script>
<div class="col-md-6 col-xs-12">
    <h2 class="pad_btm20 pad_top10 pad_left10">Message Center</h2>
    <div class="searchbar">  

        <div class="docpanel-list">
            <?php
            if (!empty($doctorMessages)) {
                foreach ($doctorMessages as $value) {

                    $sql1 = " select * from messages  where d_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' and  msend_id='".$value['msend_id']."' and read_status='0'";
                    $result1 = mysql_query($sql1);
                    $num1 = mysql_num_rows($result1);

                    $sql2 = "select * from reply where reciever_id='" . trim(mysql_real_escape_string($_SESSION['emp_id'])) . "' and  msend_id='".$value['msend_id']."' and read_status='0' ";
                    $result2 = mysql_query($sql2);
                    $num2 = mysql_num_rows($result2);
                    $total_msg = $num1 + $num2;

                    if ($total_msg == 0) {
                        $color = "";
                    } else {
                        $color = "#F0CCCC";
                    }
                    ?>
                    <div class="row" style="background-color: <?php echo $color; ?>" id="row_<?= $value['msend_id']; ?>">
                        <a href="javascript:void(0);" onclick="return viewbox(<?= $value['msend_id']; ?>);" style="text-decoration: none !important;">
                            <div class="clc-head1">
                                <!--                    <div class="col-md-2">
                                                        <div class="name-fld0"><img width="40%" height="40%" src="<?php echo $remotelocation . "includes/images/pan1.jpg" ?>"></div>
                                                        </div>-->
                                <div class="col-md-4">
                                    <div class="name-fld" style="color:black;">From: <?php echo ucwords(strtolower($value['patient_fname'])) . "  " . ucwords(strtolower($value['patient_lname'])); ?></div>
                                </div>
                                <div class="col-md-5">
                                    <div class="name-fld1">
                                        <?php echo substr($value['subject'], 0, 15) ?>
                                    </div>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <div class="name-fld2 pull-right"><label class=" bg-danger pull-right"><?php echo date('m/d/Y ', strtotime($value['date_send'])) ?></label></div>
                                </div>
                                <div class="clear"></div>
                            </div></a>
                        <!--  Reply Message -->
                        <div class="clc-dcpanel  hidden" id="box_<?= $value['msend_id']; ?>" >

                            <div class="col-md-5 ico-mytrac pull-right">                                
                                <a  class="pull-right" href="javascript:void(0);" onclick="return viewsmallbox(<?= $value['msend_id']; ?>);"><img src="<?php echo $remotelocation . "includes/images/back.png" ?>"> </a>                                
                            </div>
                            <div class="clear"></div>    
                            <div id="successmessage<?= $value['msend_id'] ?>" style="color:green;text-align:center" ></div>
                            <form action="" method="post" enctype="multipart/form-data" id="reply" name="reply" class="" > 
                                <div class="text-block hidden" id="box1_<?= $value['msend_id'] ?>">
                                    <input type="hidden" id="replyid" value="<?php echo $value['msend_id'] ?>"  />
                                    <input type="hidden" id="receiverid<?= $value['msend_id'] ?>" value="<?php echo $value['p_id'] ?>"  />
                                    <input type="hidden" id="subject" value="<?php echo $value['subject'] ?>"  />
                                    <input type="hidden" id="replierid" value="<?php echo $_SESSION['emp_id'] ?>">

                                    <p><textarea id="replytext<?= $value['msend_id'] ?>" class="form-control" name="message" cols="" rows=""></textarea></p>
                                    <p><input type="submit" class="btn btn-default reply" name="doctorreplymessage" value="Reply" id="submit_<?= $value['msend_id'] ?>" ></p>
                                </div>
                            </form>

                            <!-- reply messages show-->
                            <div id="commentload<?php echo $value['msend_id']; ?>"></div> 
                            <div> 
                                <?php
                                $sql = "select * from  reply as reply where  reply.msend_id= '" . $value['msend_id'] . "'  ORDER BY reply.mreply_id desc";
                                $result = mysql_query($sql);
                                if (mysql_num_rows($result) > 0) {
                                    while ($record = mysql_fetch_array($result)) {
                                        $image_path = "";
                                        if ($record['user_type'] == 'Doctor') {
                                            $sql1 = "select * from  ".MEDIC."  where doctor_id= '" . $record['replier_id'] . "' ";
                                            $result1 = mysql_query($sql1);
                                            $record1 = mysql_fetch_array($result1);
                                            $name = ucwords(strtolower($record1['first_name'])) . "  " . ucwords(strtolower($record1['last_name']));

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
                                                <div class="docpanel-text1">
                                                <h1><?php echo $record['user_type']; ?>:- <?php echo $name; ?></h1>
                                                <p><?php echo date("F j, Y H:i:s", strtotime($record['date_send'])) ?></p>
                                                
  </div>
                                            </div>
                                            <div class="col-md-12" style="text-align:justify; margin-top:15px"><?php echo $record['msg_text'] ?></div>
                                            <div class="clear"></div>
                                        </div> 
                                        <!-- reply messages show-->
                                    <?php }
                                }
                                ?>
                            </div>

                            <div>
                                <div class="col-md-1 docpanel-img">
                                    <div class="docpanel-img">
                                        <?php if (empty($value['patient_profilepic']) && $value['patient_gender'] == 'm') { ?>
                                            <img width="100%" src="<?php echo $remotelocation . "includes/upload/profilepic/patientmaleimg.png" ?>">
                                        <?php } elseif (empty($value['patient_profilepic']) && $value['patient_gender'] == 'f') { ?>
                                            <img width="100%" src="<?php echo $remotelocation . "includes/upload/profilepic/patientfemaleimg.jpg" ?>">
                                        <?php } elseif (isset($value['patient_profilepic']) && !empty($value['patient_profilepic'])) { ?>
                                            <img width="100%" src="<?= $remotelocation . "includes/upload/profilepic/" . $value['patient_profilepic']; ?>">
        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="docpanel-text1"><h1>Patient: <?php echo ucwords(strtolower($value['patient_fname'])) . "  " . ucwords(strtolower($value['patient_lname'])) ?></h1><p><?php echo date("F j, Y H:i:s", strtotime($value['date_send'])) ?></p>
                                    <?php if($value['file']!=NULL){ ?>
                          <p><a href="patient-files/<?php echo $value['file']; ?>"/>Download Attachment</p> <?php } ?>
                          </div>
                                </div>                             
                                <div class="col-md-12" style="text-align:justify; margin-top:15px"><?php echo $value['msg'] ?></div>
                                <div class="clear"></div>

                                <div class="clear"></div>
                            </div> 


                        </div>
                        <!-- End Reply Message -->
                    </div>
                    <?php
                }
            } else {

                echo "No messages found";
            }
            ?>
        </div>
        <div class="clearfix"></div>
    </div>    
</div>

<?php include 'includes/docrightsidebar.php'; ?>

<?php include 'includes/docfooter.php'; ?>

<style>
    .name-fld2 label{ padding:5px; border-radius:5px; font-size:12px;}
    .docpanel-list{ background:#f1f1f1; padding:10px; margin:20px 0;}
    .clc-head1{ border-bottom:1px solid #dcdcdc; padding:10px; margin:15px}
    .clc-dcpanel{ background:#fff; padding:10px; border:1px solid #dcdcdc; margin:15px;}
    .docpanel-img{ width:50px; height:50px; margin-right:10px;}
    .docpanel-text1 h1{ font-size:14px; color:#333; padding:0; margin:0}
    .docpanel-text1 { font-size:12px; color:#333; padding:0; margin:0}
    .ico-mytrac a{ padding:10px }
    .text-block{ margin:10px 0}
    .text-block p{ margin:10px 0}
</style>
<script type="text/javascript" >
    $(function () {
        $(".reply").click(function () {

            var arr = ($(this).attr('id').split("_"));
            var replyid = arr[1];
            var replytext = $("#replytext" + arr[1]).val();
            var replierid = $("#replierid").val();
            var recieverid = $("#receiverid"+replyid).val();
            var subject = $("#subject").val();
            var action = "doctorreplymessage";
            var dataString = 'replytext=' + replytext + '&replyid=' + replyid + '&action=' + action + '&replierid=' + replierid + '&recieverid=' + recieverid + '&subject=' + subject;
            if (replytext == '') {
                alert("Please enter some text to reply");
                return false;
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?= $remotelocation . "getajaxdata.php" ?>",
                    data: dataString,
                    success: function (data) {
                        if (data)
                        {
                            $("#box1_" + arr[1]).addClass('hidden');
                            $("#successmessage" + arr[1]).append('Reply have been sent successfully');
                            $("#commentload" + replyid).append(data);
                            $("#replytext" + replyid).val('');
                        }
                    }
                });

                return false;
            }
        });
    });
</script>