<?php
require_once("top.php");
require_once("database_tables.php");
 if($_POST){
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$emailaddress = $_POST['emailaddress'];
$phonenumber = $_POST['phonenumber']; $symptoms = $_POST['symptoms']; $Insurance = $_POST['Insurance']; 
$gender=$_POST['gender'];
$appointmentdate = $_POST['appointmentdate'];
$appointment = date('Y-m-d H:i:s', strtotime($appointmentdate));
$message=$_POST['message'];
$from = "welcome@cmforyou.com";
$subject="Appointment Request";
//$request_date = $_POST['request_date'];
$inserteddata = $db->Execute("insert", "insert into" .' '.APPOINTMENT. "(first_name,last_name,emailaddress,phone_number,appointment_date,gender,message,symptoms,insurance) values ('$firstname', '$lastname', '$emailaddress','$phonenumber','$appointment','$gender','$message','$symptoms','$Insurance')");
if($inserteddata){
      mail($emailaddress, $subject, $message, $from);
	  echo "<a href = 'http://cmpforyou.com/patient_reg.php'>";
	  header('Location:http://cmpforyou.com/patient_reg.php');
 echo "An Email regarding your appointment has been sent to you ";
 exit;
}
 else {
echo "There are some error requesting your appointment";
exit;
}
}
?>
