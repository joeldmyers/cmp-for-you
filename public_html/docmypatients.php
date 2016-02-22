<?php
require_once("includes/top.php");
require_once("includes/doc_authentication.php");
if (isset($_SESSION['emp_id']) && $_SESSION['emp_id'] > 0) {
    $mypatients = $db->Execute("select", "SELECT * FROM " . BOOKING . " as b INNER JOIN  " .PATIENTS. " as p on b.booking_patient_id = p.patient_id WHERE  b.bookdoc_id = '" . $_SESSION['emp_id'] . "' ORDER BY b.date desc LIMIT 0,10");
}
$errorMsg = '';
?>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>
<?php //$patientdetail = $db->Execute("select", "SELECT * FROM " . PATIENTS . "  WHERE  patient_id= '" . trim($mypatients[0]['booking_patient_id']) . "'");?>
<div class="col-md-6 col-xs-12">
    <h2 class="pad_btm20 pad_top10 pad_left10">My Patients</h2>
    <div class="searchbar">  

        <div class="docpanel-list">
            <div class="row">


                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>

                            <th>First Name</th>

                            <th>Last Name</th>

                            <th>Email</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if(isset($mypatients) && !empty($mypatients)){
                              foreach ($mypatients as $value) {
                            ?>

                        <tr>
                            <td></td>

                            <td><?php echo $value['patient_fname']?></td>

                            <td><?php echo $value['patient_lname']?></td>

                            <td><?php echo $value['patient_email']?></td>

                            <td>
                               <a href="#" class="fa fa-pencil"></a> | <a href="#" class="fa fa-trash"></a> 
                                 
                            </td>

                        </tr>
                        
                        <?php }
                        }else{  
                          echo "<table  width='80%' align='center'  border='0' cellspacing='0' cellpadding='0'><tr><td  align='center' class='warning'>
			   <br />No Records Found<br /><br />
		          </td></tr></table>";
                        }?>
                      

                    </tbody>

                </table>



            </div>
        </div>
        <div class="clearfix"></div>
    </div>    
</div>

<?php include 'includes/docrightsidebar.php'; ?>

<?php include 'includes/docfooter.php'; ?>

<style>
    .name-fld2 label{ padding:5px; border-radius:5px; font-size:12px;}
    .docpanel-list{ padding:10px; margin:20px 0;}
    .clc-head1{ border-bottom:1px solid #dcdcdc; padding:10px; margin:15px}
    .clc-dcpanel{ background:#fff; padding:10px; border:1px solid #dcdcdc; margin:15px;}
    .docpanel-img{ width:50px; height:50px; margin-right:10px;}
    .docpanel-text1 h1{ font-size:14px; color:#333; padding:0; margin:0}
    .docpanel-text1 { font-size:12px; color:#333; padding:0; margin:0}
    .ico-mytrac a{ padding:10px }
    .text-block{ margin:10px 0}
    .text-block p{ margin:10px 0}
</style>


