<?php
ini_set('upload_max_filesize','10M');
require_once("includes/top.php");
 if (isset($_SESSION['emp_id']) && $_SESSION['emp_id'] > 0) {
    $userdata =  $db->Execute("select", "SELECT gender,doctor_id,doctor_profilepic FROM " . MEDIC . "  where doctor_id = '" .$_SESSION['emp_id']. "'");
    
 }
    $errorMsg = '';
    $email = $_SESSION["emp_email"];
    if(isset($_POST['updatepic']) && !empty($_POST['updatepic']) && $_POST['action'] == '_updateprofilepic'){
     if(isset($_FILES["profilepic"]["name"]) && !empty($_FILES["profilepic"]["name"])){
      $allowedExts = array( "jpeg", "jpg", "png");
      $temp = explode(".", $_FILES["profilepic"]["name"]);
      $extension = end($temp);
      if ((($_FILES["profilepic"]["type"] == "image/gif")|| ($_FILES["profilepic"]["type"] == "image/jpeg")
      || ($_FILES["profilepic"]["type"] == "image/jpg")|| ($_FILES["profilepic"]["type"] == "image/pjpeg")
      || ($_FILES["profilepic"]["type"] == "image/x-png")|| ($_FILES["profilepic"]["type"] == "image/png"))
      && in_array($extension, $allowedExts))
        {
           if($_FILES["profilepic"]["size"] < 5000000)
         {
        if ($_FILES["profilepic"]["error"] > 0)
          {
          $errorMsg = "Error in file uploading";
          }
        else 
          {
         
        $randString = time();
        $newFileName  = strtolower($randString.'_'.$_FILES["profilepic"]["name"]);

          if (file_exists("includes/upload/profilepic/" . $_FILES["profilepic"]["name"]))
            {
            $errorMsg =  $_FILES["profilepic"]["name"] . " already exists. ";
            }
          else
            {
            move_uploaded_file($_FILES["profilepic"]["tmp_name"], "includes/upload/profilepic/" . $newFileName);
            $file_uploaded =  $db->Execute("update", "update " . MEDIC . "  SET   doctor_profilepic='" . trim(mysql_real_escape_string($newFileName)) . "' where `doctor_id`='" . $userdata[0]['doctor_id'] . "'");
            $_SESSION['doctor_profilepic'] = $newFileName; 
            $successmsg =   "Profile Pic Uploaded Successfully";
            
            }
          }
        }else{
            print_r($_FILES["profilepic"]["size"]); exit;
            $errorMsg =  "File size is too large. Upload file of maximum 5MB";
        }
      }
      else
        {
        $errorMsg = "Invalid file. Allowed file types are( jpg,png,jpeg )";
        }
}else{
    $errorMsg = "Error in file Uploading..Plz try again";
}
    }
?>
<?php require_once("includes/docheader.php"); ?>
<?php include 'includes/docleftsidebar.php'; ?>
<?php $resultData = $db->Execute("select", "SELECT doctor_profilepic FROM " . MEDIC . "  where doctor_id  = '" .$_SESSION['emp_id']. "'");?>
        <div class="col-md-6 col-xs-12">
                    <h2 class="pad_btm20 pad_top10 pad_left10">Change Profile Picture</h2>
         <div class="searchbar">  
        <div id="loginbox" style="background-color:#fff; padding:55px;">                    
            <?php
            if (isset($successmsg) && !empty($successmsg)) {
                echo "<div class='text-left' style='color:green;'>".$successmsg."</div>";
            }
            if (isset($errorMsg) && !empty($errorMsg)) {
                echo "<div class='text-left' style='color:red;'>" . $errorMsg . "</div>";
            }
            ?>
<!--            <div style="font-size: 15px; font-weight: bold; text-align: center; padding-top: 0px; margin-bottom: 10px;">Change Profile Picture </div>-->
            <form action="" method="post" enctype="multipart/form-data" id="changepassword" name="changepassword" class="" > 
                <input type="hidden" name="action" value="_updateprofilepic">
                <?php if(empty($userdata[0]['doctor_profilepic']) && $userdata[0]['gender'] == 'F'){?>
                <img src="<?=$remotelocation."includes/upload/profilepic/profilepic2.png"; ?>" width="150px" height="150px"/>
                <?php }else if(empty($userdata[0]['doctor_profilepic']) && $userdata[0]['gender'] == 'M'){?>
                <img src="<?=$remotelocation."includes/upload/profilepic/profilepic3.png"; ?>" width="150px" height="150px"/>
                <?php }else if(isset ($resultData[0]['doctor_profilepic']) && !empty($resultData[0]['doctor_profilepic'])){?>
                <img src="<?=$remotelocation."includes/upload/profilepic/".$resultData[0]['doctor_profilepic']; ?>" width="150px" height="150px" />
                <?php }?>
                <div  class="form-group">
                    
                </div>
                <div  class="form-group">
                    <input id="profile-img" type="file" class="form-control" name="profilepic" accept="image/x-png, image/gif, image/jpeg"  placeholder="" />  
                </div>
                                         
                
                <div class="form-group2">
                                    <input type="submit" name="updatepic" class="btn btn-default" value="Upload Picture" id="updatepic" />
                                </div>
            </form>     
        </div> 
       <div class="clearfix"></div>
    </div>    
                    </div>
                        
           <?php include 'includes/rightsidebar.php'; ?>
           
<?php                            
include 'includes/mfooter.php'; ?>
