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

 <style>
.fqs{ float:left; margin-left:8px;}
.fqs img{float:left; width:30px; margin:2px 0 0 0;}
#sidebar {display: none; position:absolute; z-index:10;}
.fqs:hover #sidebar {display:block; line-height:20px; left:210px; background:#ececec; border-radius:4x; border:1px solid #ccc; font-size:14px; font-weight:normal; padding:5px; width:40%; min-height:100px;  float:left;}
</style>

  <div class="container">

      <div class="col-md-9 col-xs-12">

    <h2 style="float:left; width:100%;" class="pad_btm20 pad_top10 pad_left10"><span style="float:left">Online Fax</span>
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
//Fax API
//use getAuthKeys or
$client_id = 'f1e17b5763c4478cad0e99a7e1fffa8a';
$client_secret = 'GEcISEN-hyTOvMwiwF0RyZQbSUeyI9TaFNSYK3UF8Ck';

$ACCESS_TOKEN = getAccessToken($client_id, $client_secret);


function getAccessToken($CLIENT_ID,  $CLIENT_SECRET) {
   // echo("Begin Request..");
   // echo('<br /><br />');

    $url = 'https://api.onlinefaxes.com/v2/oauth2/token?';

    $fields_string = "client_id=".$CLIENT_ID."&client_secret=".$CLIENT_SECRET."&grant_type=client_credentials";

    //url-ify the data for the POST


    //set the url
    $ch = curl_init();

    //echo($url.$fields_string);
    //echo('<br /><br />');

    curl_setopt($ch, CURLOPT_URL, $url.$fields_string);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, count($fields_string));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_CAINFO, '/home/anu888/ssl/certs/cmpforyou_com_bb745_b18f5_1474245098_be669cd3269b5e0e7.crt');


    //execute post
    $result = curl_exec($ch);
   // echo($result);
  //  echo('<br /><br />');
    $json = json_decode($result, true);
  //  echo $json['access_token'];
 //   echo $json['expires_in'];

    $httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   // var_dump($httpres);
    //echo('<br /><br />');
    //var_dump(curl_error($ch));
   // echo('<br /><br />');

    //close connection
    curl_close($ch);
    
    $response =  $json['access_token'];
    return $response;
  
}
if(isset($_POST['submit'])){
// OnlineFaxes API - SendFaxSimpleModel - PHP Curl Sample Code
$url = 'https://api.onlinefaxes.com/v2/fax/async/sendfax/simplemodel?';

$senderName = $_POST['senderName'];
$senderCompanyName = $_POST['senderCompanyName'];
$faxSubject = $_POST['faxSubject'];
$faxNotes = $_POST['faxNotes'];
$recipientName = $_POST['recipientName'];
$recipientFaxNo = $_POST['recipientFaxNo'];

$fields = array(
  'senderName' => urlencode($senderName),
  'senderCompanyName' => urlencode($senderCompanyName),
  'faxSubject' => urlencode($faxSubject),
  'faxNotes' => urlencode($faxNotes),
  'recipientName' => urlencode($recipientName),
  'recipientFaxNo' => urlencode($recipientFaxNo),
 );

$fields_string = "";

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
$fields_string = str_replace('+','%20',$fields_string);
$fields_string = rtrim($fields_string, '&');



$json_faxSenderObj = json_encode($faxSenderObj);

		$image=$_FILES['file']['name'];
		if($image!=NULL){
		list($txt, $extension) = explode(".", $_FILES['file']['name']);
		$tmp  =$_FILES['file']['tmp_name'];
		$upload="fax-attachments/".$image;
		move_uploaded_file($tmp,$upload);

$file1 = "fax-attachments/".$image;
//$file2 = realpath('Test.pdf');

$params  = "--ABC1234--"
    . "\r\n"
    . "Content-Disposition:form-data;name=SenderDetail"
    . "\r\n\r\n"
    . $json_faxSenderObj
    . "\r\n"
	
    . "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=RecipientList"
    . "\r\n\r\n"
    . $json_faxRecipientList
    . "\r\n";
	if($extension=='doc'){
$params  .=    "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=File1;filename=RFP-RFQ-Template.doc"
    . "\r\n"
    . "Content-Type:image/doc"
    . "\r\n\r\n"
    . file_get_contents($file1)
    . "\r\n";
	}
	else if($extension=='pdf'){
$params  .=    "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=File1;filename=Test.pdf"
    . "\r\n"
    . "Content-Type:image/pdf"
    . "\r\n\r\n"
    . file_get_contents($file1)
    . "\r\n";
	}
    
	else {
$params  .=    "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=File1;filename=seo.jpg"
    . "\r\n"
    . "Content-Type:image/png"
    . "\r\n\r\n"
    . file_get_contents($file1)
    . "\r\n";
	}
	
	
  $params  .= "--ABC1234--";
$multipart_boundary = "ABC1234"; 
}


$request_headers    = array();
$request_headers[]  = 'Authorization: ofx '.$ACCESS_TOKEN;
if($image!=NULL){
$request_headers[]  = 'Content-Length: ' . strlen($params);
$request_headers[]  = 'Content-Type: multipart/form-data; boundary=' . $multipart_boundary;
}

// send the request now
//set the url
$ch = curl_init();

//echo($url.$fields_string);
//echo('<br /><br />');

curl_setopt($ch, CURLOPT_URL, $url.$fields_string);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, '/home/anu888/ssl/certs/cacert.pem');

$result = curl_exec($ch);
$result = json_decode($result);

//echo '<pre>';
//print_r($result);
//echo('<br /><br />');

$httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//var_dump($httpres);
//echo('<br /><br />');
//var_dump(curl_error($ch));
//echo('<br /><br />');
curl_close($ch);


$fax_status=$result->Item1;

if ($fax_status='OK') {
echo '<p style="color:green">Fax successfully sent!</p>';
} else {
echo '<p style="color:red">Fax was not sent!</p>';
}
//echo $result->Item2;
}
?>

     

    <form id="accountForm" method="post" class="form-horizontal" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

        <input type="hidden" name="action" value="_additionaltab">

        <div class="tab-content-new">

         <div class="tab-pane1" id="problems-tab">
              <div class="form-group">

                    <label class="col-xs-3 control-label">Sender Name</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="senderName" name="senderName" value="<?php echo $_SESSION["emp_name"] ?>" readonly required/>

                    </div>

                </div><div class="form-group">

                    <label class="col-xs-3 control-label">Sender Company Name</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="senderCompanyName" name="senderCompanyName" value="" />

                    </div>

                </div><div class="form-group">

                    <label class="col-xs-3 control-label">Fax Subject</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="faxSubject" name="faxSubject" value="" required/>

                    </div>

                </div>
             <div class="form-group">

                    <label class="col-xs-3 control-label">Enter Recipient Name</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" id="recipientName" name="recipientName" value="" required/>

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-xs-3 control-label">Enter Fax Number</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" maxlength="10" id="recipientFaxNo" name="recipientFaxNo"  onkeypress="return isNumberKey(event)" value="" required/>

                    </div>

                </div>

                 <div class="form-group">

                    <label class="col-xs-3 control-label">Fax Notes</label>

                    <div class="col-xs-8">
					<select  name="template" onChange = "setTemplate(this);">
                        <option value="0">No Template</option>
		                <option value="1">Refer a Patient</option>
		                <option value="2">Send a Thank you Note</option>
              
                    </select>
                       
					<textarea class="form-control" id="faxNotes" name="faxNotes" rows="6" required></textarea>
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
                  document.getElementById('faxNotes').innerHTML = test0;
                } else if (otionValue == "1")
                  document.getElementById('faxNotes').innerHTML = test1;
                  else if (otionValue == "2")
              document.getElementById('faxNotes').innerHTML = test2;
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