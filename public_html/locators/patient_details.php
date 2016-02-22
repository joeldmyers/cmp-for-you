<?php
require_once("../includes/top.php");
$id=$_GET['id'];
$data=$db->Execute("select", "SELECT * FROM `tbl_patients` where patient_id=".$id );
if(empty($data)){
    echo "Sorry!! we are unable to fetch the information.";
}
/*$name=$data[0]['name'];
$address=$data[0]['address'];

$city=$data[0]['city'];
$state=$data[0]['doctor_state'];
$zipcode=$data[0]['zipcode'];*/
$address2="";

 if (isset($data[0]['patient_gender']) && $data[0]['patient_gender'] == 'm') {
	$img=$remotelocation . "includes/images/profilepic3.png";
 } else {
	$img=$remotelocation . "includes/images/profilepic2.png";
 }
 $bodyparts = $db->Execute("select", "select bodyparts_id,patient_bodyparts FROM " . BODYPARTS ." WHERE bodyparts_id=".$data[0]['patient_body_pain_area']);
 $bodypartsdata = $db->Execute("select", "select  symptom_id,symptom_descr FROM " . SYMPTOMS . " where symptom_id  ='" . $data[0]['patient_bodysymptoms'] . "'");
if($data[0]['patient_medication1']){
 $medication1 = $db->Execute("select", "select  medication_id,medication_name FROM " . MEDICATION ." WHERE medication_id=".$data[0]['patient_medication1']);
} else {
	$medication1[0]['medication_name']="NA";
}
if($data[0]['patient_medication2']){
 $medication2 = $db->Execute("select", "select  medication_id,medication_name FROM " . MEDICATION ." WHERE medication_id=".$data[0]['patient_medication2']);
} else {
	$medication2[0]['medication_name']="NA";
}
if($data[0]['patient_medication3']){
 $medication3 = $db->Execute("select", "select  medication_id,medication_name FROM " . MEDICATION ." WHERE medication_id=".$data[0]['patient_medication3']);
} else {
	$medication3[0]['medication_name']="NA";
}

if($data[0]['patient_is_insured']==3){
	$insu="Don't Know";
} else if($data[0]['patient_is_insured']==2){
$insu="No";
} else if($data[0]['patient_is_insured']==1){
$insu="Yes";
}  else {
$insu="";
}
if($data[0]['patient_is_medicare']==1){
	$medicare="Yes";
} else if($data[0]['patient_is_medicare']==2){
$medicare="No";
} else {
$medicare="";
}
if($data[0]['patient_has_medicarid']==1){
	$medicarid="Yes";
} else if($data[0]['patient_has_medicarid']==2){
$medicarid="No";
} else {
$medicarid="";
}
if($data[0]['patient_has_vainsurance']==1){
	$vainsurance="Yes";
} else if($data[0]['patient_has_vainsurance']==2){
$vainsurance="No";
} else {
$vainsurance="";
}
$inscompdata = $db->Execute("select", "select  * FROM " . INSURANCE ." WHERE ins_id=".$data[0]['patient_inscompanies']);
 ?>
<html>
<head>
<link href="https://cmpforyou.com/includes/css/font-awesome.min.css" rel="stylesheet">
      <link href="https://cmpforyou.com/includes/css/bootstrap.min.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container">
  <div class="row">
  	<div class="col-md-6">
    
      <div class="panel panel-default">
			<div class="panel-body">
              		<div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <h2><?php echo $data[0]['patient_fname']." ".$data[0]['patient_lname']; ?></h2>
                            <p><strong>Address: </strong><?php echo $data[0]['patient_streetaddress']; ?></p>
                            <p><strong>Country: </strong><?php echo $data[0]['patient_country']; ?></p>
                           <p><strong>City: </strong><?php echo $data[0]['patient_city']; ?></p>
						   <p><strong>Zipcode: </strong><?php echo $data[0]['patient_zipcode']; ?></p>
							<p><strong>Primary Sympotoms: </strong><?php echo $data[0]['patient_symptoms1']; ?></p>
							<p><strong>Secondary Sympotoms: </strong><?php echo $data[0]['patient_symptoms2']; ?></p>
							<p><strong>Tertairy Sympotoms: </strong><?php echo $data[0]['patient_symptoms3']; ?></p>
						  <p><strong>Body Pain Area: </strong><?php echo $bodyparts[0]['patient_bodyparts']; ?></p>
						<p><strong>Body Pain Level: </strong><?php echo $bodyparts[0]['patient_body_pain_level']; ?></p>
						<p><strong>Body Symptoms: </strong><?php echo $bodypartsdata[0]['symptom_descr']; ?></p>
                       	<p><strong>Treatment Place: </strong><?php echo $data[0]['patient_teatment_place']; ?></p>
						<p><strong>Primary Medication: </strong><?php echo $medication1[0]['medication_name']; ?></p>
						<p><strong>Secondary Medication: </strong><?php echo $medication2[0]['medication_name']; ?></p>
						<p><strong>Tertiary Medication: </strong><?php echo $medication3[0]['medication_name']; ?></p>
						<p><strong>Insurance Status: </strong><?php echo $insu; ?></p>
						<p><strong>Medicare: </strong><?php echo $medicare; ?></p>
						<p><strong>Medicaid: </strong><?php echo $medicaid; ?></p>
						<p><strong>VA Insurance: </strong><?php echo $vainsurance; ?></p>
						<p><strong>Name Of Your Insurance Company: </strong><?php echo $inscompdata[0]['ins_name']; ?></p>
						<p><strong>Summary of health problems: </strong><?php echo $data[0]['patient_healthsummary']; ?></p>
                        </div><!--/col-->          
                        <div class="col-xs-12 col-sm-4 text-center">
                                <img src="<?php echo $img; ?>" alt="" class="center-block img-circle img-responsive">
                                
						<p><strong>DOB: </strong><?php echo $data[0]['patient_dob']; ?></p>
												
                        </div><!--/col-->
						

              		</div><!--/row-->
              </div><!--/panel-body-->
          </div><!--/panel-->

    
    
    </div>
<script>
	function close_box(){
		 $('#fancybox-close').trigger('click');
	}
</script>
</body>
</html>