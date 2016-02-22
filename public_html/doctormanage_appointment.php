<?php require_once("includes/top.php");
 require_once("includes/doc_authentication.php");
 extract($_REQUEST);

	$booking =  $db->Execute("select", "SELECT *,DATE_FORMAT(start_time,'%m/%d/%Y %h:%i %p') as starttime,DATE_FORMAT(end_time,'%m/%d/%Y %h:%i %p') as endtime FROM tbl_office_booking  where bookdoc_id = '".$_SESSION['emp_id']."' ORDER BY booking_id DESC");	function patient_name($ids){	$doctordata =mysql_query("SELECT concat_ws(' ',patient_fname,patient_lname) as name FROM " . PATIENTS . "  where patient_id = '" . $ids . "'");	
	$doctordata=mysql_fetch_assoc($doctordata);
	$docname = $doctordata['name'];	return $docname;}
?>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>
        <div class="col-md-6 col-xs-12" style="width:70%">
                    <h2 class="pad_btm20 pad_top10 pad_left10">My Appointment Details</h2>
         <div class="searchbar">  
         <?php 
          echo "<table class='table'><thead> <tr><th>Booking Id</th><th>Booking Type</th>  <th>Transaction Id</th>                           <th>Patient Name</th>                            <th>Start Time</th>                            <th>End Time</th>					                       </tr></thead><tbody>";
		  if(isset($booking[0]['booking_id']) && ($booking[0]['booking_id']) > 0 ) { 
		  foreach($booking as $transaction):
		 
		  $ids=$transaction['booking_id'];
		  
		 
		  $status="<input type='radio' name='$radio_name' value='1' $status_pvalue onclick='update_status(1,$ids)'/>Pending<br/><input type='radio' name='$radio_name' value='0' $status_rvalue onclick='update_status(0,$ids)'/>Rejected<br/><input type='radio' name='$radio_name' value='2' $status_avalue onclick='update_status(2,$ids)'/>Allowed";
		  echo "<tr><td>".str_pad($transaction['booking_id'],4,0,STR_PAD_LEFT)."</td> <td>".$transaction['type']."</td><td>".$transaction['transaction_number']."</td>   <td>".patient_name($transaction['booking_patient_id'])."</td>                       <td>".$transaction['starttime']."</td>                        <td>".$transaction['endtime']."</td></tr>";           endforeach;            } else {             echo "<tr><td colspan='4'> No records found..!!</td></tr>";         }               echo "</tbody></table>";        ?>
        </div>    
        </div>                        
<?php //include 'includes/docrightsidebar.php'; ?>           
<?php include 'includes/docfooter.php'; ?>
