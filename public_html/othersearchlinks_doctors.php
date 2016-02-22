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

&nbsp;&nbsp;&nbsp;<h3> Other Search Links </h3><br/>

&nbsp;&nbsp;&nbsp;<a href = "searchpharmacies_doctors.php"><h4>Search Hospitals</h4></a><br/>

&nbsp;&nbsp;&nbsp;<a href = "searchpharmacies_doctors.php"><h4>Search Pharmacies</h4></a><br/>

&nbsp;&nbsp;&nbsp;<a href = "searchhomehealth_doctors.php"><h4>Search Home Health Services</h4></a><br/>

&nbsp;&nbsp;&nbsp;<a href = "searchnursing_doctors.php"><h4>Search Nursing Homes</h4></a><br/>

&nbsp;&nbsp;&nbsp;<a href = "searchoutpatient_doctors.php"><h4>Search Outpatient Clinic</h4></a><br/>

&nbsp;&nbsp;&nbsp;<a href = "https://www.practicefusion.com/" target="_blank" ><h4>GET Free  EHR</h4></a>

</div>


<?php include 'includes/docfooter.php'; ?>