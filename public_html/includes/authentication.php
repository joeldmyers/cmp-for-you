<?php
	if(isset($_SESSION['emp_id']) ){
		        
            
    }  else  {
 		session_destroy();
 		redirect("login.php");
 		exit;
    }
    
?>