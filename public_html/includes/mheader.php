<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="Member Panel">
      <meta name="author" content="Pradeep Singhal">
      <link rel="shortcut icon" href="favicon.ico">
      <title>Member Panel</title>
      <!-- Bootstrap core CSS -->        
      <link href="<?= $remotelocation . "includes/css/font-awesome.min.css"; ?>" rel="stylesheet">
      <link href="<?= $remotelocation . "includes/css/bootstrap.min.css"; ?>" rel="stylesheet">
      <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
      <!-- Just for debugging purposes. Don't actually copy this line! -->        <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->        <!--[if lt IE 9]>          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>        <![endif]-->        <!-- Custom styles for this template -->        
      <link href="<?= $remotelocation . "includes/css/custom-theme.css"; ?>" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>    <script src="<?=$remotelocation."includes/js/bootstrap.min.js"; ?>"></script>        <!--[if lt IE 9]>          
      <link href="css/ie8.css" rel="stylesheet">
      <![endif]-->        <!--[if lt IE 8]>          
      <link href="css/ie7.css" rel="stylesheet">
      <![endif]-->        
   </head>
   <body class="home">
      <!-- Header -->        
      <header class="header">
         <div class="container-fluid">
            <div class="col-md-6 col-xs-12">
               <div class="navbar-header">                        <a class="logo" href="<?= $remotelocation; ?>">                            <img src="<?= $remotelocation . "includes/images/logo3.png"; ?>" class="img-responsive" width="100%" />                        </a>                    </div>
            </div>
            <div class="col-md-6 col-xs-12">
               <div class="dropdown pull-right profilemenu">
                  <a id="dLabel" class="profile" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">                            <?php if (empty($_SESSION['patient_profilepic']) && $_SESSION['patient_gender'] == 'f') { ?>                                <img src="<?= $remotelocation . "includes/upload/profilepic/patientfemaleimg.jpg"; ?>" style="width:30px;height:30px;" />                             <?php } else if (empty($_SESSION['patient_profilepic']) && $_SESSION['patient_gender'] == 'm') { ?>                                <img src="<?= $remotelocation . "includes/upload/profilepic/patientmaleimg.png"; ?>" style="width:30px;height:30px;" />                            <?php } else if (isset($_SESSION['patient_profilepic']) && !empty($_SESSION['patient_profilepic'])) { ?>                                <img src="<?= $remotelocation . "includes/upload/profilepic/" . $_SESSION['patient_profilepic']; ?>" style="width:30px;height:30px;"/>                            <?php } ?>Hi <?= (isset($_SESSION["emp_name"]) ? ucfirst($_SESSION["emp_name"]) : ''); ?><span class="caret"></span></a>                        
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                     <li><a href="<?= $remotelocation . "profile_steps.php"; ?>">My Profile</a> </li>
                     <li><a href="<?= $remotelocation . "changepassword.php"; ?>">Change Password</a> </li>
                     <li><a href="<?= $remotelocation . "changeprofilepic.php"; ?>">Update Photo</a> </li>
                     <li><a href="<?= $remotelocation . "logout.php"; ?>" >Logout</a> </li>
                  </ul>
               </div>
               <!--/.nav-collapse -->                    
               <ul class="notifications pull-right notify">
                  <li> <a href="<?= $remotelocation . "patientdashboard.php"; ?>">My Dashboard </a> </li>
                  <li> <a href="<?= $remotelocation . "searchdoctor.php"; ?>">Search Doctor</a> </li>
               <li> <a href="<?= $remotelocation . "searchfacility.php"; ?>">Search Facility</a> </li>
			   <li> <a href="<?= $remotelocation . "othersearchlinks.php"; ?>">Other Search Links</a> </li>
               </ul>
            </div>
         </div>
      </header>