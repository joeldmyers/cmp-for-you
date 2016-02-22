<?php
require_once("includes/top.php");
require_once("includes/authentication.php");
$alldate = $_POST['sheduledatec'];
$doctor_id = $_POST['doctor_id'];
//$doctordata = $db->Execute("select", "SELECT * FROM " . MEDIC . "  where id = '" . $doctor_id . "'");
//$docemail = $doctordata[0]['email'];
extract($_REQUEST);
if($token_id){
	$invalid=1;
	$sql=mysql_query("SELECT * FROM " . TRANSACTIONS . "  WHERE transaction_type='video' AND patientid='" . $_SESSION['emp_id'] . "' AND doctorid='$doctor_id' AND transaction_status=1 AND transaction_number='".trim($token_id)."'");
	$nos=mysql_num_rows($sql);
	echo mysql_error();
	if($nos>0){
		$invalid=1;
	} else {
		$invalid=0;
	}
} else {
	$invalid=0;
}
$userdata =  $db->Execute("select", "SELECT * FROM " . PATIENTS . "  where patient_id = '" . $_SESSION['emp_id'] . "'");
$patient_name=$userdata[0]['patient_fname']." ".$userdata[0]['patient_lname'];$patient_email=$userdata[0]['patient_email'];$patient_phone=$userdata[0]['patient_telephone'];
if (isset($_POST['submit']) && $_POST['action'] == 'appiontment') {
    $docemail = $_POST['email'];
    $docid = $_POST['doc_id'];
    $appointment_time = $_POST['allsheduletime'];
	$token_id = $_POST['token_id'];
	/*****************************************************************************************/
	$explode=explode("</p>",trim($appointment_time));
	array_pop($explode);
	foreach($explode as $value){
	$value=str_replace("<p>","",$value);
	$value=str_replace("</p>","",$value);
	$value_time=explode("-",$value);
	if(@$value_time[1]!=''){	
		$am=strpos(@$value_time[1],"AM");
		$pm=strpos(@$value_time[1],"PM");
	if($pm){	
		$time=$value_time[0]." PM";	
		$start_time=date("Y-m-d H:i",strtotime($time));
	} else {
		$start_time= date("Y-m-d H:i",strtotime($value_time[0]));	
	}	
		$endTime = date("Y-m-d H:i",strtotime("+15 minutes", strtotime($start_time)));
	}
	$db->Execute("insert", "insert into " . BOOKING . " (booking_patient_id,bookdoc_id,patient_availability,date,start_time,end_time,transaction_number) values ('" . $_SESSION['emp_id'] . "' , '" . $docid . "','" . trim(mysql_real_escape_string($appointment_time)) . "','" . date('Y-m-d') ."','$start_time','$endTime','".$token_id."') ");				}			/*********************************************************************************************/
 
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $headers .= 'From: "' . $_SESSION['emp_email'] . '"' . "\r\n";
    $subject = "Appointment Confirmation";
    $mailmessage = '<table width="500" border="0" align="center" cellpadding="0" cellspacing="10" style="font-family:Arial, Helvetica, sans-serif; background-color:#e9e8e8; border:1px solid #cfcfcf;">
    <tr>
     <td colspan="2" style="font-size:12px; color:#000; padding:4px 10px;"></td>
    </tr>
    <tr>
    <td colspan="2" style="font-size:12px;  color:#000; padding:4px 10px;"><strong> User Name </strong></td>
     <td colspan="2" style="font-size:12px;  color:#000; padding:4px 10px;"><strong>' . $userdata[0]['patient_fname']. '</strong></td>
    </tr>
    <tr>
    <td colspan="2" style="font-size:12px;  color:#000; padding:4px 10px;"><strong>Email</strong></td>
     <td colspan="2" style="font-size:12px;  color:#000; padding:4px 10px;"><strong> ' . $userdata[0]['patient_email'] . '</strong></td>
    </tr>
    <tr>
    <td colspan="2" style="font-size:12px;  color:#000; padding:4px 10px;"><strong>Phone</strong></td>
     <td colspan="2" style="font-size:12px;  color:#000; padding:4px 10px;"><strong>' . $_POST['phone'] . '</strong></td>
    </tr>
    <tr>
    <td colspan="2" style="font-size:12px;  color:#000; padding:4px 10px;"><strong>Your Appointment Time</strong></td>
     <td colspan="2" style="font-size:12px;  color:#000; padding:4px 10px;"><strong> ' . $appointment_time . '</strong></td>
    </tr>
    <tr>
     <td style="font-size:14px;  color:#000; font-weight:bold; padding:0px 0px 0px 10px;">Best Regards </td>
    </tr>
    <tr>
     <td style="font-size:14px; color:#000; font-weight:bold; padding:0px 0px 0px 10px;">Cmforyou</td>
    </tr>
    </table>';
    @mail($docemail, $subject, $mailmessage, $headers);
    $_POST = array();
    $message = " <div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a><strong>Success!</strong> Thanks for your booking request with us .  Your booking will be confirmed By Email </div>";
   
}
?>

<link href="<?php echo $remotelocation . 'includes/css/Org_6958.css' ?>" rel="stylesheet" type="text/css">
<link href="<?php echo $remotelocation . 'includes/css/Inbound_6958.css' ?>" rel="stylesheet" type="text/css">
<link href="<?php echo $remotelocation . 'includes/css/bluetheme_6958.css' ?>" rel="stylesheet" type="text/css">
<style type="text/css">
    @-webkit-keyframes bounce_circle {
        0% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        25% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        50% {
            opacity: 0.5;
            background-color: #FE9E0C;
        }

        75% {
            opacity: 0.3;
            background-color: #FE9E0C;
        }

        100% {
            opacity: 0.1;
            background-color: #FE9E0C;
        }
    }

    @-ms-keyframes bounce_circle {
        0% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        25% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        50% {
            opacity: 0.5;
            background-color: #FE9E0C;
        }

        75% {
            opacity: 0.3;
            background-color: #FE9E0C;
        }

        100% {
            opacity: 0.1;
            background-color: #FE9E0C;
        }
    }

    @-moz-keyframes bounce_circle {
        0% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        25% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        50% {
            opacity: 0.5;
            background-color: #FE9E0C;
        }

        75% {
            opacity: 0.3;
            background-color: #FE9E0C;
        }

        100% {
            opacity: 0.1;
            background-color: #FE9E0C;
        }
    }

    @-o-keyframes bounce_circle {
        0% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        25% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        50% {
            opacity: 0.5;
            background-color: #FE9E0C;
        }

        75% {
            opacity: 0.3;
            background-color: #FE9E0C;
        }

        100% {
            opacity: 0.1;
            background-color: #FE9E0C;
        }
    }

    @keyframes bounce_circle {
        0% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        25% {
            opacity: 1;
            background-color: #FE9E0C;
        }

        50% {
            opacity: 0.5;
            background-color: #FE9E0C;
        }

        75% {
            opacity: 0.3;
            background-color: #FE9E0C;
        }

        100% {
            opacity: 0.1;
            background-color: #FE9E0C;
        }
    }
    .field{
        margin-left: 200;
        margin-top: 5;
        padding-left: 10;
    }
</style>
<div class="aspNetHidden">
</div>
<?php require_once("includes/mheader.php"); ?>
<?php include 'includes/leftsidebar.php'; ?>
<div class="col-md-5 col-xs-12">
    <h2 class="pad_btm20 pad_top10 pad_left10">Booking Confirmation</h2>
    <div class="searchbar">
        <div align="center" class="customerSiteSection" id="customerSiteSectionFrame" style=" margin: 0 auto; background :#f9f9f9;">
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                <tbody><tr>
                        <td valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; zoom:1; box-sizing: border-box;">
                                <tbody><tr>
                                        <td colspan="2">
                                            <div style="width: 100%;max-width: 1024px; margin: 0 auto; padding:0;box-sizing: border-box; background :#f9f9f9;">

                                                <div id="initialLoading" style="width: 100%; min-height: 540px; display: none; background-color: white;">

                                                </div>
                                                <div id="wizardDisplay" style="display: block;">

                                                    <div style="position: relative; width: 0; float: left;">
                                                        <div id="LeftColumn" align="left" style="float: left; width: 191px; margin-left: 12px;">
                                                            <div class="grayBox gradient" style="width: 100%;">
                                                                <div class="lightBox">
                                                                    <div class="grayBoxHead">
                                                                        <table cellpadding="0" cellspacing="0" border="0" width="191">
                                                                            <tbody><tr valign="top">
                                                                                    <td style="max-width:76px;">
                                                                                        <div id="image" class="rad8 siteImg" style="float: left;max-width: 69px; padding-right: 0px;">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>

                                                                                    </td>
                                                                                </tr>
                                                                            </tbody></table>
                                                                    </div>


                                                                    <div class="vSpace">&nbsp;</div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div id="" style="box-sizing: border-box; float: left; width: 100%">       
                                                        <!--<div id="BlueBox" class="blueBox gradient" style="height: 500px; box-sizing: border-box; width: 100%;background-color:#FAF6F6">-->
                                                        <?php if ($_REQUEST['s'] != 'y') { ?>
                                                            <?php
                                                            if (isset($message)) {

                                                                echo $message;
                                                            }
                                                            ?>
                                                            <h3 align="center" style="font-size:15px">Please fill the form so that the doctor can confirm  your Appointment </h3><br/>
                                                            <div class="formclass"> 
                                                                <form method="post" action="" class="form-horizontal">
																<input type="hidden" name="token_id" value="<?php echo @$_REQUEST['token_id'] ?>">
                                                                    <input type="hidden" name="action" value="appiontment">
                                                                    <div class="form-group">
                                                                        <label class="col-xs-3 control-label">Name</label>
                                                                        <div class="col-xs-5">
                                                                            <input type="text" id="name" class="form-control required"  name="name" value="<?php echo $patient_name; ?>" />
                                                                        </div>
                                                                    </div>
                                                                   
                                                                    <div class="form-group">
                                                                        <label class="col-xs-3 control-label">Email</label>
                                                                        <div class="col-xs-5">
                                                                            <input type="text" id="email" class="form-control required"  name="email" value="<?php echo $patient_email; ?>"  />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-xs-3 control-label">Phone</label>
                                                                        <div class="col-xs-5">
                                                                            <input type="text" id="phone" class="form-control required"  name="phone" value="<?php echo $patient_phone; ?>"  maxlength="10" minlength="10" required/>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="allsheduletime" value="<?= $alldate ?>" >
                                                                    <input type="hidden" name="doc_id" value="<?php echo $doctor_id;?>" >
                                                                    <div class="form-group" style="margin-top: 15px; text-align:center;">
																	<?php
																		if($invalid==1 && $message==''){
																	?>
                                                                        <div class="col-xs-5 col-xs-offset-3">
                                                                            <button  class="btn btn-default" id="submit" name="submit" type="submit">Done</button></a>
                                                                        </div>
																	<?php
																	} else if($invalid==0 && $message=='') {
																	?>
																	 <div class="col-xs-5 col-xs-offset-3">
																		<strong style='color:red'>Your Payment Transaction is Invalid, Please contact us </strong>
																	 </div>
																	<?php
																	}
																	?>
                                                                    </div>
                                                                </form>
                                                            <?php } else { ?>
                                                                <div class="formclass">  
                                                                    <p>Thank you for submitting your Booking request. Your request will be reviewed and a time will be confirmed with you.</p>

                                                                </div>
                                                            <?php } ?>
                                                            <!--                                                            </div>  -->

                                                        </div>

                                                        <div id="timebooked" style="float:left;"></div> 
                                                    </div>
                                                    <div id="PopUpDivContainer" style="position: absolute; top: 0; left: 0; z-index: 1000">
                                                        <div id="result-2" class="opaque" >
                                                        </div>

                                                    </div>

                                                </div>


                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>

                                        <td style="vertical-align: top;">
                                            <div style=" position: relative; left: 52px; top:30px;">


                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div style="width:100%; position: relative; left: 25px;">


                                            </div>
                                        </td>
                                    </tr>

                                </tbody></table>
                        </td>
                    </tr>

                </tbody></table>
        </div>
    </div>
</div>
</form>
<?php include 'includes/rightsidebar.php'; ?>
<?php include 'includes/mfooter.php'; ?>