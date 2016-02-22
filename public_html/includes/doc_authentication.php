<?php
	if(isset($_SESSION['emp_id']) ){
		        
            
    }  else  {
 		session_destroy();
 		redirect("doctor_login.php");
 		exit;
    }
    
?>