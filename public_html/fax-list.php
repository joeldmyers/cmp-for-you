<?php 
require_once("includes/top.php");
require_once("includes/doc_authentication.php");
require_once("fax-lists-fetch-data.php");
$TRUNCATE=mysql_query("TRUNCATE onlinefaxes_faxlist");
foreach ($result as $fetch){
$fax_insert = "INSERT INTO onlinefaxes_faxlist   SET  Id='" . mysql_real_escape_string($fetch->Id) . "', MsgNo='" . mysql_real_escape_string($fetch->MsgNo) . "', FolderId='" . mysql_real_escape_string($fetch->FolderId) . "', CreatedDate='" . mysql_real_escape_string($fetch->CreatedDate) . "', SenderName='" . mysql_real_escape_string($fetch->SenderName). "', Subject='" . mysql_real_escape_string($fetch->Subject) . "', RecpName='" . mysql_real_escape_string($fetch->RecpName) . "', RecpAddress='" . mysql_real_escape_string($fetch->RecpAddress) . "', MsgStatus='" . mysql_real_escape_string($fetch->MsgStatus) . "' ";
mysql_query($fax_insert);
}

 extract($_REQUEST);
if(isset($vals) && isset($ids)){
	$update="UPDATE ".BOOKING." SET status='$vals' WHERE booking_id='$ids' ";	$res=mysql_query($update);		}
	$booking =  $db->Execute("select", "SELECT *,DATE_FORMAT(start_time,'%m/%d/%Y %h:%i %p') as starttime,DATE_FORMAT(end_time,'%m/%d/%Y %h:%i %p') as endtime FROM ".BOOKING."  where bookdoc_id = '".$_SESSION['emp_id']."' ORDER BY booking_id DESC");	function patient_name($ids){	$doctordata =mysql_query("SELECT concat_ws(' ',patient_fname,patient_lname) as name FROM " . PATIENTS . "  where patient_id = '" . $ids . "'");	
	$doctordata=mysql_fetch_assoc($doctordata);
	$docname = $doctordata['name'];	return $docname;}
?>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>
        <div class="col-md-6 col-xs-12" style="width:70%">
                    <h2 class="pad_btm20 pad_top10 pad_left10">Fax Lists:</h2>
                    <div style="margin: 5px 10px 0 0; position: absolute;  top: 8px; left: 160px;">
                    <form method="post" name="myform"/>
                    <select  name="faxlist" onchange="if (this.value) window.location.href=this.value" class="form-control">
                <option value="fax-list.php?id=1001" <?php if($_GET['id']=='1001'){echo "selected";}?>>Received</option>
                        <option value="fax-list.php?id=1002" <?php if($_GET['id']=='1002'){echo "selected";}?>>Processing</option>
                        <option value="fax-list.php?id=1003" <?php if($_GET['id']=='1003' || $_GET['id']==NULL){echo "selected";}?>>Outbox / Sent</option>
                        <option value="fax-list.php?id=1004" <?php if($_GET['id']=='1004'){echo "selected";}?>>Failed</option>
                        <option value="fax-list.php?id=1007" <?php if($_GET['id']=='1007'){echo "selected";}?>>Deleted</option>
                    </select>
                    </div>
                    <div style="margin: 5px 10px 0 0; position: absolute; right: 0; top: 25px;"><i class="fa fa-download"></i> <label>Download</label>&nbsp;&nbsp;&nbsp;<i class="fa fa-trash-o"></i> <label>Delete</label></div>
         <div class="searchbar">  
        
        <table id="example" class="table table-striped table-bordered table-hover ">
                                    <thead>
                                    <tr>
                                            <th>S.N</th>
                                            <th>Message</th>
                                            <th>Created On</th>
                                            <th>Subject</th>
                                            <th>Recipient's Name</th>
                                            <th>Fax Number</th>
                                            <th>Status</th>
                                            <?php if($_GET['id']=='1003' || $_GET['id']=='1004' || $_GET['id']==NULL){ ?>
                                            <th>Action</th>
                                            <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
									$i=1;
									
									$list=mysql_query("select * from onlinefaxes_faxlist WHERE `SenderName`='".$_SESSION["emp_name"]."'");
									
                                   while($listdata=mysql_fetch_array($list)) {
                                    ?>
                                               
                                        <tr>
                                            <td><?php echo $i?></td>
                                            <td><?php echo $listdata['MsgNo']; ?></td>
                                            <td><?php echo $listdata['CreatedDate']; ?></td>
                                            <td><?php echo $listdata['Subject']; ?></td>
                                            <td><?php echo $listdata['RecpName']; ?></td>
                                            <td><?php echo $listdata['RecpAddress']; ?></td>
                                            <td><?php echo $listdata['MsgStatus']; ?></td>
                                            <?php if($_GET['id']=='1003' || $_GET['id']=='1004' || $_GET['id']==NULL){ ?>
                           <td> <a href="" data-toggle="modal" data-target="#<?php echo $listdata['Id']; ?>"  title='Click here to download'><i class="fa fa-download"></i></a>  &nbsp;&nbsp;&nbsp; 
                           <a  href='fax-lists-fetch-data.php?faxid=<?php echo $listdata['Id']; ?>' onclick="return confirm('Are you sure want to delete this?');" title='Click here to delete'><i class="fa fa-trash-o"></i></a> </td>
                                 			<?php } ?>
                                        </tr>
<div class="modal fade" id="<?php echo $listdata['Id']; ?>" tabindex="-1" role="dialog" >

  <div class="modal-dialog  modal-sm" role="document">
                        <div class="modal-content">
    <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title" style="color: #204d91;">Download Fax <i class="fa fa-download fa-lg"></i></h3>
                          </div>
                          <div class="modal-body">
                <p>Click on the link below to download file.</p>
                <p id="FaxDownloadLink"><a href='https://api.onlinefaxes.com/v2/document/async/downloadfaxpdf/10180032002964295/Fax_<?php echo $listdata['Id'].".pdf"; ?>'><?php echo $listdata['Id'].".pdf"; ?></a></p>
            </div>
                          
     
    </div>
  </div>
</div>
                                                
                                       <?php	$i++; } ?> 
                                         </tbody>
                                    </table>
        
        
        
 
        
        
        </div>    
        </div>                        <script>	function update_status(vals,ids){		if(vals==1){			var status="pending";		}		if(vals==0){			var status="Rejecting";		}		if(vals==2){			var status="Approving";		}		var confir=confirm("Are you sure to "+status+" this booking");		if(confir){			window.location="doctormanage_bookings.php?vals="+vals+"&ids="+ids;		}	}</script>
<?php //include 'includes/docrightsidebar.php'; ?>  
         
<?php include 'includes/docfooter.php'; ?>
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.9/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">   
$(document).ready(function() {
    $('#example').DataTable();
} );
</script> 