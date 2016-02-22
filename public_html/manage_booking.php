<?php require_once("includes/top.php");
 require_once("includes/authentication.php");
 $booking =  $db->Execute("select", "SELECT *,DATE_FORMAT(start_time,'%m/%d/%Y %h:%i %p') as starttime,DATE_FORMAT(end_time,'%m/%d/%Y %h:%i %p') as endtime FROM ".BOOKING."  where booking_patient_id = '".$_SESSION['emp_id']."' ORDER BY booking_id DESC");
 function doctor_name($ids){
 $doctordata =mysql_query("SELECT concat_ws(' ',first_name,middle_name,last_name) as name FROM " . MEDIC . "  where doctor_id = '" . $ids . "' ");
 $doctordata=mysql_fetch_assoc($doctordata);
 $docname = $doctordata['name'];
 return $docname;
 }
 ?>
 <?php require_once("includes/mheader.php"); ?>
 <?php include 'includes/leftsidebar.php'; ?> 
 <div class="col-md-6 col-xs-12" style="width:70%"> 
 <h2 class="pad_btm20 pad_top10 pad_left10">My Booking Details</h2>
 <div class="searchbar"> 
  <?php echo "<table class='table'><thead> 
  <tr>
  <th>Booking Id</th>
  <th>Transaction Id</th>  
  <th>Doctor Name</th>  
  <th>Start Time</th> 
  <th>End Time</th> 
  <th>Status</th>
  </tr></thead><tbody>"; 
  if(isset($booking[0]['booking_id']) && ($booking[0]['booking_id']) > 0 ){ 
  foreach($booking as $transaction):
  if($transaction['status']==1){
  $status="Pending ";
  } else if($transaction['status']==0){	
  $status="Rejected ";	
  } else if($transaction['status']==2){	
  $status="Approved";}     
  echo "<tr>  
  <td>".str_pad($transaction['booking_id'],4,0,STR_PAD_LEFT)."</td>
   <td>".$transaction['transaction_number']."</td>
  <td>".doctor_name($transaction['bookdoc_id'])."</td> 
  <td>".$transaction['starttime']."</td>  
  <td>".$transaction['endtime']."</td>
  <td>".$status."</td>  
  </tr>";
  endforeach;  
  } else { 
  echo "<tr><td colspan='4'> No records found..!!</td></tr>"; } 
  echo "</tbody></table>";        ?>
  </div> 
  </div>  
  <?php //include 'includes/rightsidebar.php'; ?> 
  <?php include 'includes/mfooter.php'; ?>