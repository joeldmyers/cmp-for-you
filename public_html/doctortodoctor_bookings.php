<?php require_once("includes/top.php");
 require_once("includes/doc_authentication.php");
 $today_date=date("Y-m-d");
 $booking =  $db->Execute("select", "SELECT *,DATE_FORMAT(start_time,'%Y-%m-%d') as cur_datetime,DATE_FORMAT(start_time,'%m/%d/%Y %h:%i %p') as starttime,DATE_FORMAT(end_time,'%m/%d/%Y %h:%i %p') as endtime FROM tbl_doctor_video  where status=1 AND (booking_fdoctor_id = '".$_SESSION['emp_id']."' OR  bookdoc_id = '".$_SESSION['emp_id']."') AND DATE_FORMAT(start_time,'%Y-%m-%d') >= '$today_date'");
 
 function doctor_name($ids){
	 $doctordata =mysql_query("SELECT concat_ws(' ',first_name,last_name,middle_name) as name FROM " . MEDIC . "  where doctor_id = '" . $ids . "'");
	
	 $doctordata=mysql_fetch_assoc($doctordata);
	 $docname = $doctordata['name'];
	 return $docname;
 }
 
 ?>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>
 <div class="col-md-6 col-xs-12" style="width:70%"> 
 <h2 class="pad_btm20 pad_top10 pad_left10">Video Booking Details</h2>
 <div class="searchbar"> 
  <?php echo "<table class='table'><thead> 
  <tr>
  <th>Booking Id</th>
  <th>Doctor's Name</th>  
  <th>Start Time</th> 
  <th>End Time</th> 
  <th>Status</th>
  <th>&nbsp;</th>
  </tr></thead><tbody>"; 
  if(isset($booking[0]['booking_id']) && ($booking[0]['booking_id']) > 0 ){ 
  foreach($booking as $transaction):
	$img="<img src='img/video.png'/>";
  
	$ids=$transaction['booking_id'] ; 
	if($transaction['cur_datetime']==$today_date){
		$icon="<img src='img/new.gif'/>";
		$img= "<a href=\"javascript:void(0);\" onclick=\"window.open('call_doctor_doctor_appointment.php?bookingid=$ids', 'Video Appointment', 'width=500, height=500');\">".$img."</a>";
	} else {
		$icon="";
		$text_value="";
	}
	$status="Active";
  echo "<tr>  
  <td>".str_pad($transaction['booking_id'],4,0,STR_PAD_LEFT).$icon."</td>
  <td>".doctor_name($transaction['bookdoc_id'])."</td> 
  <td>".$transaction['starttime']."</td>  
  <td>".$transaction['endtime']."</td>
  <td>".$status."</td>  
  <td>".$img."</td>
  </tr>";
  endforeach;  
  } else { 
  echo "<tr><td colspan='4'> No records found..!!</td></tr>"; } 
  echo "</tbody></table>";        ?>
  </div> 
  </div>  
<?php //include 'includes/docrightsidebar.php'; ?>           
<?php include 'includes/docfooter.php'; ?>