<?php
 require_once("includes/top.php");
 require_once("includes/doc_authentication.php");
$transactions =  $db->Execute("select", "SELECT * FROM ".TRANSACTIONS."  where doctorid = '".$_SESSION['emp_id']."'"); function patient_name($ids){	 $doctordata =mysql_query("SELECT concat_ws(' ',patient_fname,patient_lname) as name FROM " . PATIENTS . "  where patient_id = '" . $ids . "'");		 $doctordata=mysql_fetch_assoc($doctordata);	 $docname = $doctordata['name'];	 return $docname; }
?>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>
        <div class="col-md-6 col-xs-12" style="width:70%">
                    <h2 class="pad_btm20 pad_top10 pad_left10">My Transactions</h2>
         <div class="searchbar">  
         <?php 
          echo "<table class='table'><thead>
                        <tr>
                            <th>Transaction Id</th>							 <th>Patient Name</th>
                            <th>Transaction Amount</th>							 <th>Type</th>	
                            <th>Transaction Status</th>
                            <th>Transaction Date</th>       
                       </tr></thead><tbody>"; 
         if(isset($transactions[0]['transaction_id']) && ($transactions[0]['transaction_id']) > 0 )
         {
            
           foreach($transactions as $transaction):
                echo "<tr>
                        <td>".$transaction['transaction_id']."</td>						 <td>".patient_name($transaction['patientid'])."</td>
                        <td>".number_format($transaction['transaction_amount'],2)."</td>						 <td>".$transaction['transaction_type']."</td>
                        <td>".$transaction['transaction_number']."</td>
                        <td>".date('m/d/Y',strtotime($transaction['transaction_date']))."</td>
                        </tr>";
           endforeach;   
         } else {
             echo "<tr><td colspan='4'> No records found..!!</td></tr>";
         }
         echo "</tbody></table>";
        ?>
        </div>    
        </div>                        
<?php //include 'includes/docrightsidebar.php'; ?>           
<?php include 'includes/docfooter.php'; ?>
