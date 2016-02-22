<?php
 require_once("includes/top.php");
 require_once("includes/authentication.php");
$transactions =  $db->Execute("select", "SELECT * FROM ".TRANSACTIONS."  where patientid = '".$_SESSION['emp_id']."'");
?>
<?php require_once("includes/mheader.php"); ?>
<?php include 'includes/leftsidebar.php'; ?>
        <div class="col-md-6 col-xs-12">
                    <h2 class="pad_btm20 pad_top10 pad_left10">My Transactions</h2>
         <div class="searchbar">  
         <?php 
          echo "<table class='table'><thead>
                        <tr>
                            <th>Transaction Id</th>							 <th>Transaction Id</th>		
                            <th>Transaction Amount</th>					
                            <th>Type</th>
                            <th>Transaction Date</th>       
                       </tr></thead><tbody>"; 
         if(isset($transactions[0]['transaction_id']) && ($transactions[0]['transaction_id']) > 0 )
         {
            
           foreach($transactions as $transaction):
                echo "<tr>
                        <td>".$transaction['transaction_id']."</td>						<td>".$transaction['transaction_number']."</td>	
                        <td>".number_format($transaction['transaction_amount'],2)."</td>
                        <td>".$transaction['transaction_type']."</td>
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
<?php include 'includes/rightsidebar.php'; ?>           
<?php include 'includes/mfooter.php'; ?>
