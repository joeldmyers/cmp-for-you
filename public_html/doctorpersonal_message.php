<?php

require_once("includes/top.php");

require_once("includes/doc_authentication.php");

require_once("inc/class.phpmailer.php");

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

 <style>
.fqs{ float:left; margin-left:8px;}
.fqs img{float:left; width:30px; margin:2px 0 0 0;}
#sidebar {display: none; position:absolute; z-index:10;}
.fqs:hover #sidebar {display:block; line-height:20px; left:210px; background:#ececec; border-radius:4x; border:1px solid #ccc; font-size:14px; font-weight:normal; padding:5px; width:40%; min-height:100px;  float:left;}
</style>

  <div class="container">

      <div class="col-md-9 col-xs-12">

    <h2 style="float:left; width:100%;" class="pad_btm20 pad_top10 pad_left10"><span style="float:left">Send Message</span>
     <div class="fqs" style="display:none">
    <img src="images/questionmark.png"  />
    <div id="sidebar">
    <p>
    <strong>Q. How do I send a fax?</strong><br/>
    Ans. 
1. Enter Sender Name. <br/>
2. Enter Sender Company Name. <br/>
3. Enter Fax Subject.<br/>
4. Enter Recipient Name.<br/>
5. Enter Fax Number.<br/>
6. Enter Recipient's Name.<br/>
7. When finished, select “Send”.<br/>
    </p>
    </div>
	</div></h2>
   
      
    <div style="float:left; width:100%;" class="searchbar">  



        <div class="docpanel-list">

    <style type="text/css">

        #accountForm {

            margin-top: 15px;

        }

    </style>

  


<?php

if(isset($_POST['submit'])){

$to = $_POST['email'];
$sub = $_POST['subject'];
$message = nl2br($_POST['message']);
$subject = "Doctor Message form";
$subject_respond = "Confirmation for enquiry from CMP for you";
$ip  = $_SERVER["REMOTE_ADDR"];
$date = date('Y-m-d H:i:s');

$image=$_FILES['file']['name'];
$tmp  =$_FILES['file']['tmp_name'];
$upload=$image;
//$upd=move_uploaded_file($tmp);
		
  $message_respond = "Your Enquiry has been submitted successfully";
	$body = '
    <html>
    <head>
      <title>Contact Us</title>
    </head>
    <body>
	<h3>Your Enquiry has been submitted successfully</h3>
      <table border=1 width="50%" align="center">
     <tr><td>Subject :</td><td>'.$sub.'</td></tr>
      <tr><td>Message :</td><td>'.$message.'</td></tr>
	  <tr><td>I.P.Address :</td><td>'.$_SERVER["REMOTE_ADDR"].'</td></tr>
    
      </table>
    </body>
    </html>';


$body_respond = '
    <html>
    <head>
      <title>Contact Us</title>
    </head>
	<center><h3>Your Enquiry has been submitted successfully</h3></center>
    <body>
      <table border=1 width="50%" align="center">
    <tr><td>Subject :</td><td>'.$sub.'</td></tr>
      <tr><td>Message :</td><td>'.$message.'</td></tr>
	  <tr><td>I.P.Address :</td><td>'.$_SERVER["REMOTE_ADDR"].'</td></tr>
    
      </table>
    </body>
    </html>';
	
			$mail = new PHPMailer();
			$mail->IsHTML(true);
			$mail->Charset = "iso-8859-1";
			$mail->From = "hello@webicules.com";
			$mail->FromName = "CMP for You";
			$mail->Subject = $subject;
			$mail->Body = $body;
			$mail->AddAddress($to, " ");
			$mail->AddAttachment($tmp,$image);
			$mail->Send();

	



if ($mail) {
echo '<p style="color:green">Message successfully sent!</p>';
} else {
echo '<p style="color:red">Message was not sent!</p>';
}

}
?>

     

    <form id="accountForm" method="post" class="form-horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

        <input type="hidden" name="action" value="_additionaltab">

        <div class="tab-content-new">

         <div class="tab-pane1" id="problems-tab">
              <div class="form-group">

                    <label class="col-xs-3 control-label">Email address</label>

                    <div class="col-xs-8">

                        <input type="email" class="form-control" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="" required/>

                    </div>

              </div>
                <div class="form-group">

                    <label class="col-xs-3 control-label">Subject</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="subject" name="subject" value="" required/>

                    </div>

                </div><div class="form-group">

                    <label class="col-xs-3 control-label">Message</label>
					
                    <div class="col-xs-8">
                    <select  name="template" onChange = "setTemplate(this);">
                        <option value="0">No Template</option>
		                <option value="1">Refer a Patient</option>
		                <option value="2">Send a Thank you Note</option>
              
                    </select>
						<textarea class="form-control" id="adminmessage" name="message" rows="6" required></textarea>
                    </div>

                </div>

             <div class="form-group" style="">

                    <label class="col-xs-3 control-label">Attach File</label>

                    <div class="col-xs-8">

                        <input type="file" multiple class="form-control" name="file" id="fileToUpload" onchange="fileSelected()">
                    </div>
           </div>

 				<div id="fileList" class=""><ul></ul></div>		
                <div class="form-group" style="margin-top: 15px;">

                    <div class="col-xs-5 col-xs-offset-3">

                        
                        <?php
$url=  htmlspecialchars($_SERVER['HTTP_REFERER']);
echo "<a href='$url' class='btn btn-primary btn-back'> Back / Cancel</a>" ;
?>

                        <a href=""><button  class="btn btn-primary btn-next1" name="submit" type="submit" id="btn-next1" style="margin-left:10px;" onClick="return validatefax()">Send</button></a>

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
            test0 = "";
            test1 = "I am writing to let you know I will be referring a patient to your office for consultation. We will provide them with your office contact information and will instruct them to schedule an appointment with your staff. Please do not hesitate to contact me with any questions you may have regarding this patient's history or records. Thank you for your assistance in this matter.";
            test2 = "Thank you for your recent referral of to my office. It was a pleasure meeting with your patient and I look forward to assisting you further with their care. Please contact me with any questions you have regarding my recommendations. Thank you again for this interesting referral. ";
            function setTemplate(t) {
				
                var otionValue = t.value;
                if (otionValue == "0") {
                  document.getElementById('adminmessage').innerHTML = test0;
                } else if (otionValue == "1")
                  document.getElementById('adminmessage').innerHTML = test1;
                  else if (otionValue == "2")
              document.getElementById('adminmessage').innerHTML = test2;
            }; 
</script>

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

    function validatefax(){

        var name = $('#recepient').val();

        var faxid = $('#faxid').val();

        var subject = $('#subject').val();

       

        

        if(name == ''){

            alert("Please enter Recepient Name");

            return false;

        }else if(faxid == ''){

            alert("Please enter Fax id");

            return false;

        }else if(subject == ''){

           alert("Please enter Subject to fax");

            return false; 
 

    }

</script>

<script>
//UPLOAD FUNCTIONS
function fileSelected() {
	//alert("hiiiiii!!");
    //var files = $('input[type="file"]')[0].files; // for multiple files
    var files = $('#fileToUpload').get(0).files;
    var fileDescription = [];
    var fileSizes = [];
    var fileExt = [];

    for (var i = 0; i < files.length; i++) {
        if (files[i]) {
            if (files[i].size > 1024 * 1024) {
                fileSizes[i] = (Math.round(files[i].size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
            }
            else {
                fileSizes[i] = (Math.round(files[i].size * 100 / 1024) / 100).toString() + 'KB';
            }

            fileExt[i] = getFileExtension(files[i].name);
            fileDescription[i] = '<p> <b>File(s) to upload :: </b> ' +
                          '<li><span>Name: ' + files[i].name + ', </span>' +
                          '<span>Size: ' + fileSizes[i] + ', </span>' +
                          '<span>Type: ' + files[i].type + ', </span>' +
                          '<span>Ext: ' + fileExt[i] + '</span></li>';
            $('#fileList ul').append(fileDescription[i]);
        }
    }


}

function getFileExtension(filename) {
    var a = filename.split(".");
    if (a.length === 1 || (a[0] === "" && a.length === 2)) {
        return "";
    }
    return a.pop();    // feel free to tack .toLowerCase() here if you want
}
function uploadFailed(evt) {
    toastr.error("Error: Upload attachment to the server failed.");
}
function uploadCanceled(evt) {
    toastr.error("The upload has been canceled by the user or the browser dropped the connection.");
}
function uploadComplete(evt) {
    /* This event is raised when the server send back a response */
    var fileInfo = JSON.parse(evt.target.responseText);
    //var fileInfo = evt.target.responseText;
    toastr.success('Fax attachment has been successfully uploaded to the server');
}
</script>


<?php require_once("includes/mfooter.php"); ?>


       <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
   		 <style>
                	@media(min-width:768px;)
					{
						li.dropdown{ display:block;}
					}
                 </style> 