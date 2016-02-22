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







 <iframe src="//www.practicefusion.com"  frameborder="0" width="100%" height="500px" style="margin-top:-30px">
</iframe> 

    





<?php include 'includes/docfooter.php'; ?>


