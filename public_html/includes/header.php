<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>Medical</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        
    <!-- Css files -->    
    <link rel="icon" href="<?=$remotelocation;?>includes/images/favicon.ico" type="image/x-icon">
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>   
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>    
    <link href="<?=$remotelocation;?>includes/css/style.css" rel="stylesheet">
    <link href="<?=$remotelocation;?>includes/css/font-awesome.css" rel="stylesheet">
    <link href="<?=$remotelocation;?>includes/css/bootstrap.css" rel="stylesheet">             
    <link rel="stylesheet" href="<?=$remotelocation;?>includes/css/revolution_style.css" media="screen">
    <!-- End Css files -->    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
    <!-- Add js Files -->
    <script src="<?=$remotelocation;?>includes/js/jquery.js"></script>
    <script src="<?=$remotelocation;?>includes/js/jquery.themepunch.plugins.min.js"></script>			
    <script src="<?=$remotelocation;?>includes/js/jquery.themepunch.revolution.min.js"></script>
    <script src="<?=$remotelocation;?>includes/js/medical.min.js"></script>	
    <script src="<?=$remotelocation;?>includes/js/jquery.validate.min.js"></script>
   
    <script src="<?=$remotelocation;?>includes/js/bootstrap.min.js"></script>
    <!-- End JS files -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-33756403-1', 'auto');
  ga('send', 'pageview');

</script>
  </head>
  <body>
    <div id="wrapper">
    <div class="top-sec">
        <div class="container"></div>
            <div id="top-sec-detail" class="top-sec-detail" style="display: block;">
            <div class="container">
            <div class="row">
            	<div class="col-md-6 no-padding-left">
            		<div style="font-size:16px; font-family: 'Roboto', sans-serif; text-transform:capitalize; padding-top:9px; font-weight:300;">Get help from a real doctor now</div>
                </div>
                <div class="col-md-6">
                <ul class="social2">
                	<li><a href="http://tinyurl.com/qgnl8e5"><i class="fa fa-facebook"></i></a></li>
                	<li><a href="https://twitter.com/CmpFor"><i class="fa fa-twitter"></i></a></li>
					<li><a href="https://www.pinterest.com/CMPForYou/"><i class="fa fa-pinterest"></i></a></li>
					<li><a href="https://www.linkedin.com/company/custom-med-patient-services"><i class="fa fa-linkedin"></i></a></li>
					<li><a href="https://instagram.com/custommedpatientservices/"><i class="fa fa-instagram"></i></a></li>
                	<li><a href="#."><i class="fa fa-google-plus"></i></a></li>
                	
					
					
					
                </ul>
                </div>
            </div>
              </div>
            </div>
      </div>
    <header>
        <div class="container no-padding-left">            
            <!-- Brand and toggle get grouped for better mobile display -->
            <nav class="navbar navbar-default" role="navigation">
              <div class="navbar-header" style="padding-top:15px; padding-bottom:15px;">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=$remotelocation;?>"><img src="<?=$remotelocation;?>includes/images/logo.png" alt="image" title="Logo"></a>
              </div>
     
            <!-- Collect the nav links, forms, and other content for toggling --> 
              <div class="collapse navbar-collapse navbar-right medical-nav mymdnav" id="bs-example-navbar-collapse-1"> 
                <ul class="nav navbar-nav">
                   <li><a href="<?=$remotelocation;?>">Home</a></li>
                   <li><a href="<?=$remotelocation;?>aboutus.php">About Us</a></li>
                   <li><a href="<?=$remotelocation;?>overview.php">Overview</a></li>
                 <li><a href="<?=$remotelocation;?>how-it-works.php">How it Works</a></li>
                  <!-- <li><a href="<?=$remotelocation;?>videomeeting.php">Video Meeting</a></li>-->
                   <?php if(isset($_SESSION["emp_usertype"]) && !empty($_SESSION["emp_usertype"]) && $_SESSION["emp_usertype"] == 'DOCTOR'){
                       echo "<li><a href='doctorlogout.php'>Logout</a></li>";
                   }elseif(isset($_SESSION["emp_usertype"]) && !empty($_SESSION["emp_usertype"]) && $_SESSION["emp_usertype"] == 'PATIENT') {
                       echo "<li><a href='logout.php'>Logout</a></li>";
                   }else{
                    ?>
                   <li class="dropdown drop1"><a href="javascript:void(0);" >Sign Up <span class="caret"></span></a>
                   <ul class="dropdown-menu">
                           <li ><a href="<?=$remotelocation;?>patient_reg.php">Patient Registration</a></li>
                           <li ><a href="<?=$remotelocation;?>doctor_reg.php">Doctor/Nurse Registration</a></li>
                                                     
  </ul></li>
                   <li class="dropdown"><a href="javascript:void(0);" >Login  <span class="caret"></span></a> 
                       <ul class="dropdown-menu" >
                           <li ><a href="<?=$remotelocation;?>login.php">Patient Login</a></li>
                           <li ><a href="<?=$remotelocation;?>doctor_login.php">Doctor/Nurse Login</a></li>
                                                     
  </ul></li>
                   <?php }?>
                </ul>
              </div><!-- /.navbar-collapse -->    
            </nav>
        </div>
    </header>
<?php //get_banner(); ?>
   