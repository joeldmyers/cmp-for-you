<?php
$subject="Testing mail";
	//$message="The following questions are deactivated due to caps Limit.<br/></b>";
	$message="TEST Mail"; /*for testing purpose*/	
$headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                $headers .= 'From: Support <noreply@cmpforyou.com>' . "\r\n";
                $headers .= "X-Priority: 1\r\n"; 
$to="rasdev@itsapp2you.com,info@cmpforyou.com";
echo mail($to,$subject,$message,$headers);exit;
?>