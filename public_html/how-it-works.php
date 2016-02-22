0<?php 
include("inc/functions.php");
require_once("includes/top.php"); 

require_once("includes/header.php"); 

?>
 

<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen">   
<script type="text/javascript">

    function toggle_visibility(id) {

       var e = document.getElementById(id);

       if(e.style.display == 'block')

          e.style.display = 'none';

       else

          e.style.display = 'block';

    }

</script>
<style>

.popup1 {position:fixed; width:100%; height:100%; display:none; top:0px; left:0px; z-index:999999999;}

.popup1 .overlaybg {background:#000; position:fixed; width:100%; height:100%; left:0px; top:0px; opacity:0.9;}

.popup1 .login-center {width:1000px; margin:0 auto;}

.popup1 .videopopup {background:#fff; width:560px; padding:20px 0px; margin:58px 0px 0px 210px; position:relative; z-index:999999;}

.popup1 .videopopup iframe, .popup1 .videopopup object {width:560px; height:315px;}

.popup1 .videopopup .sharelinks {clear:both; padding:10px 0px 0px 0px;}

.popup1 .videopopup .sharelinks .fbbutton, .popup1 .videopopup .sharelinks .twbutton {float:left; margin:0px 10px 0px 0px; height:15px; width:70px!important; position:relative; z-index:999999;}

.popup1 .videopopup .sharelinks img {width:auto!important; height:auto!important;}

.popup1 .videopopup .close {position:relative; text-align:right; clear:both; font-size:18px; text-transform:uppercase; color:#000!important; font-weight:normal;  font-family: 'dosisbold'; clear:both;}

.popup1 .videopopup .close a {color:#000; text-decoration:none;}

.popup1 .videopopup .close a:hover {color:#000;}

</style>
<div class="container">

    <div class="innercontent row margin-top-40" style="margin:40px 0 60px 0;">
 
    <div class="col-md-12 form-container" style="padding:10px 60px; border:#e3e2e2 1px solid;">

<h3 class="text-center margin-top-30" style="margin-bottom:25px">We have three videos</h3> 




<div class="col-sm-4  col-md-4 text-center">
<div><strong> How it works for Doctors & Nurses</strong></div><br/>

<div>
          
<iframe src="//player.vimeo.com/video/140017547" width="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>
</div>


    
    
    
    <div class="col-sm-4  col-md-4 text-center">
    
    <div><strong>How it works for Patients</strong></div><br/>
<div>
<iframe src="//player.vimeo.com/video/140017167" width="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

          
    </div>
    
    
    </div>
    
    
     <div class="col-sm-4  col-md-4 text-center">
 <div><strong>How The Video Consultation works</strong></div><br/>
<div><iframe src="//player.vimeo.com/video/140016385" width="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          <!--div id="videopopup2" class="popup1">

            <div class="overlaybg"></div>

            <div class="login-center">

              <div class="videopopup">

                <div class="close"><a href="#" onClick="toggle_visibility('videopopup2');">CLOSE</a></div>

                <iframe width="420" height="315" src="//www.youtube.com/embed/<?php echo get_youtube_video_value('https://www.youtube.com/watch?v=zyAMmSXm5ZE'); ?>" frameborder="0" allowfullscreen></iframe>                

                <div class="clear"></div>

              </div>

            </div>

          </div-->

        </div>
    </div>
    
      <div class="clearfix"></div>
    
    </div>
    
  
      <div> 

</div>
         
       


</div></div>


   <?php require_once("includes/footer.php"); ?>                    
            

    