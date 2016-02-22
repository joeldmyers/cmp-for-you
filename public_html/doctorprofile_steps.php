<?php

require_once("includes/top.php");

require_once("includes/doc_authentication.php");

global $db;

$state_list = $db->Execute("select", "select sta_id,sta_code,sta_name FROM " . STATES . " where sta_couid = '1'");

//$state_list = $db->Execute("select", "select sta_id,sta_name FROM " . STATES . " where sta_couid = '1'");

// echo "<pre/>"; print_r($state_list); exit;

?>

<?php require_once("includes/docheader.php"); ?>  

<?php require_once("includes/docleftsidebar.php"); ?>  

    <script src="<?=$remotelocation;?>includes/js/jquery.js"></script>

    <script src="<?=$remotelocation;?>includes/js/jquery.themepunch.plugins.min.js"></script>			

    <script src="<?=$remotelocation;?>includes/js/jquery.themepunch.revolution.min.js"></script>

    <script src="<?=$remotelocation;?>includes/js/medical.min.js"></script>	

    <script src="<?=$remotelocation;?>includes/js/jquery.validate.min.js"></script>

    <script src="<?=$remotelocation;?>includes/js/bootstrap.min.js"></script>

    

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript" src="<?=$remotelocation;?>includes/js/jsDatePick.min.1.3.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="<?=$remotelocation;?>includes/css/jsDatePick_ltr.min.css" />

<script type="text/javascript">

            window.onload = function(){

            new JsDatePick({

                    useMode:2,

                    target:"doctor_dob",

                    dateFormat:"%m/%d/%Y",

                    /*selectedDate:{				This is an example of what the full configuration offers.

                     day:5,						For full documentation about these settings please see the full version of the code.

                     month:9,

                     year:2006

                     },

                     yearsRange:[1978,2020],

                     limitToToday:false,

                     cellColorScheme:"beige",

                     dateFormat:"%m-%d-%Y",

                     imgPath:"img/",

                     weekStartDay:1*/

                    isStripped:false,

                    selectedDate:{

                    year:1991,

                            month:4,

                            day:16

                    },

                    yearsRange: new Array(1900, 1997),

                    limitToToday:false,

            });

            };

        </script>

<div class="container">

    <div class="col-md-9 col-xs-12">

    <h2 class="pad_btm20 pad_top10 pad_left10">Profile Steps</h2>

    <div class="searchbar">  



        <div class="docpanel-list">

    <style type="text/css">

        #accountForm {

            margin-top: 15px;

        }

        .cal_image {  background: url(includes/images/calendra.gif) no-repeat;  width: 20px; height: 20px;margin-top: 10px !important;}

        .monthYearPicker{    color:#FFFFFF !important;}

        #calendarDiv table{ clear: both;}

       .cal_image{ position: absolute; top:0 ;right: 0; border: 0; }

        .input-group-addon{ border-left: 1px solid #dcdcdc!important; border-right:0; padding: 16px; display: inline-block; position: absolute; top:0; right: 20px}

    </style>  

     

    <div class="tabs-new margin-top-40">

        <ul class="nav-new nav-tabs-new">

           <li><a href="<?=$remotelocation."doctorprofile_steps.php";?>" style="cursor:pointer;">Address</a></li>

            <li class="active"><a href="<?=$remotelocation."doctorpersonal_detailtab.php";?>" style="cursor:pointer;">Personal Information </a></li>

            <li><a href="<?=$remotelocation."doctorspeciality_tab.php";?>" style="cursor:pointer;">Speciality </a></li> 
            

        </ul>

    </div>

    <?php 

    $message = '';

    $email = $_SESSION["emp_email"];

    if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == '_docprofilesteps'){

        $address1 = trim($_POST['address1']);

        $address2 = trim($_POST['address2']);

        $state = trim($_POST['state']);

        $dob = date("Y-m-d", strtotime($_POST['doctor_dob']));

        $phone = trim($_POST['telephone']);

        $city = trim($_POST['city']);

        $country = trim($_POST['country']);

        $zipcode = trim($_POST['zipcode']);
		
		$fax = trim($_POST['fax']);

        $statecode = $db->Execute("select", "select  sta_code FROM " . STATES . " where sta_code ='".$state."'");
        $city_list = $db->Execute("select", "select distinct(city) FROM " . CITYSTATE . " where state = '".trim($state)."' order by city asc");
       //echo  "update " . MEDIC . "  SET  street_address_line1='" . trim(mysql_real_escape_string($address1)) . "',street_address_line2='" . trim(mysql_real_escape_string($address2)) . "',doctor_state='" . trim(mysql_real_escape_string($statecode[0]['sta_code'])) . "',country='" . trim(mysql_real_escape_string($country)) . "',zipcode='" . trim(mysql_real_escape_string($zipcode)) . "',doctor_dob='" . trim(mysql_real_escape_string($dob)) . "',doctor_telephone='" . trim(mysql_real_escape_string($phone)) ."',city='" . trim(mysql_real_escape_string($city)) ."' where `email`='" . $email . "'"; exit;

        $updateAddress = $db->Execute("update", "update " . MEDIC . "  SET  city='" . trim(mysql_real_escape_string($city)) . "',street_address_line1='" . trim(mysql_real_escape_string($address1)) . "',street_address_line2='" . trim(mysql_real_escape_string($address2)) . "',doctor_state='" . trim(mysql_real_escape_string($statecode[0]['sta_code'])) . "',country='" . trim(mysql_real_escape_string($country)) . "',zipcode='" . trim(mysql_real_escape_string($zipcode)) . "',doctor_dob='" . trim(mysql_real_escape_string($dob)) . "',doctor_telephone='" . trim(mysql_real_escape_string($phone)) ."',city='" . trim(mysql_real_escape_string($city)) ."', fax='" . trim(mysql_real_escape_string($fax)) ."' where `email`='" . $email . "'");

        

        echo"<script>window.location.href='doctorpersonal_detailtab.php'</script>";

    }else{

        $userdata = $db->Execute("select", "select  street_address_line1,street_address_line2,country,zipcode,doctor_state,doctor_dob,doctor_telephone,city,fax FROM " . MEDIC . " where email ='".$email."'");
       $city_list = $db->Execute("select", "select distinct(city) FROM " . CITYSTATE . " where state = '".trim($userdata[0]['doctor_state'])."' order by city asc");

        }

//       echo '<pre>'; print_r($userdata); exit();

    ?>

    <form id="accountForm" method="post" class="form-horizontal" action="<?php $_SERVER['PHP_SELF']; ?>">

        <input type="hidden" name="action" value="_docprofilesteps">

        <div class="tab-content-new">

            <div class="tab-pane1">

                <div class="form-group">

                    <label class="col-xs-3 control-label">Dob</label>

                    <div class="col-xs-8">

                        <input type="text" id="doctor_dob" class="form-control required" readonly="readonly" name="doctor_dob" value="<?=(isset($userdata[0]['doctor_dob']) && !empty($userdata[0]['doctor_dob']) && intval($userdata[0]['doctor_dob']) > 0 ? date('m/d/Y', strtotime($userdata[0]['doctor_dob'])) : '');?>" />

<!--                        <span class="input-group-addon">

                        <input type="button" class="cal_image"  onclick="displayCalendar(document.getElementById('doctor_dob'),'dd-mm-yyyy',this)">

                        </span>-->

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-xs-3 control-label">Telephone No.</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" name="telephone" value="<?=(isset($userdata[0]['doctor_telephone']) && !empty($userdata[0]['doctor_telephone']) ? $userdata[0]['doctor_telephone'] : '');?>" id="telephone" onkeypress="return isNumberKey(event)" maxlength="10"/>

                    </div>

                </div>
				<div class="form-group">

                    <label class="col-xs-3 control-label">Fax</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" name="fax" value="<?=(isset($userdata[0]['fax']) && !empty($userdata[0]['fax']) ? $userdata[0]['fax'] :'' )?>" id="fax" maxlength="10" onkeypress="return isNumberKey(event)"/>

                    </div>

                </div>
                <div class="form-group">

                    <label class="col-xs-3 control-label">Street Address Line 1</label>

                    <div class="col-xs-8">

                        <textarea class="form-control" name="address1" rows="3" coloumn="3" id="address1"><?=(isset($userdata[0]['street_address_line1']) && !empty($userdata[0]['street_address_line1']) ? $userdata[0]['street_address_line1'] : '');?></textarea>

                    </div>

                </div>

                

                <div class="form-group">

                    <label class="col-xs-3 control-label">Street Address Line 2</label>

                    <div class="col-xs-8">

                        <textarea class="form-control" name="address2" rows="3" coloumn="3" id="address2"><?=(isset($userdata[0]['street_address_line2']) && !empty($userdata[0]['street_address_line2']) ? $userdata[0]['street_address_line2'] : '');?></textarea>

                    </div>

                </div>



                


<div class="form-group">

                    <label class="col-xs-3 control-label">Country</label>

<!--                    <div class="col-xs-5" style="width:41.7%;">

                        <select class="form-control" name="country" id="country">

                            <option value="">Select a country</option>

                            <?php if(isset($userdata[0]['country'])){

                                 $selected="selected";

                            }else{

                                $selected = "";

                            } ?>

                            <option value="US" <?php echo $selected;?>>United States</option>

                        </select>

                    </div>-->

                    <div class="col-xs-8">

                        <input type="text" class="form-control" name="country" value="United States" id="country" onkeypress="return isNumberKey(event)" readonly="readonly"/>

                    </div>

                </div>
                <div class="form-group">

                    <label class="col-xs-3 control-label">State</label>

                    <div class="col-xs-8">

                        <select class="form-control" name="state" id="state" onchange="populateCity();">

                            <option value="">Select a state</option>

                            <?php

                            if (isset($state_list) && !empty($state_list)) {

                                foreach ($state_list as $data) {

                                    if(isset($userdata[0]['doctor_state']) && trim($data['sta_code']) == $userdata[0]['doctor_state'] ){

                                        echo '<option value="'.trim($data['sta_code']).'" selected="selected" >'.$data['sta_name'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.trim($data['sta_code']).'">'.$data['sta_name'].'</option>';

                                    } 

                                }

                            }

                            ?>

                        </select>

                    </div>

                </div>

<div class="form-group">

                    <label class="col-xs-3 control-label">City</label>

                    <div class="col-xs-8">
                        <select class="form-control" name="city" id="city" >

                            <option value="">Select City</option>

                            <?php

                            if (isset($city_list) && !empty($city_list)) {

                                foreach ($city_list as $data) {

                                    if(isset($userdata[0]['city']) && $data['city'] == $userdata[0]['city'] ){

                                        echo '<option value="'.trim($data['city']).'" selected="selected" >'.$data['city'].'</option>';

                                        

                                    }else{

                                        echo '<option value="'.trim($data['city']).'">'.$data['city'].'</option>';

                                    } 

                                }

                            }

                            ?>

                        </select>
                        <!--<input type="text" class="form-control" name="city" id="city" value="<?=(isset($userdata[0]['city']) && !empty($userdata[0]['city']) ? $userdata[0]['city'] : '');?>"/>-->

                    </div>

                </div>

                

                <div class="form-group">

                    <label class="col-xs-3 control-label">Zip Code</label>

                    <div class="col-xs-8">

                        <input type="text" class="form-control" name="zipcode" value="<?=(isset($userdata[0]['zipcode']) && !empty($userdata[0]['zipcode']) ? $userdata[0]['zipcode'] :'' )?>" id="zipcode" maxlength="6" onkeypress="return isNumberKey(event)"/>

                    </div>

                </div>
                
                

                <div class="form-group" style="margin-top: 15px;">

                    <div class="col-xs-5 col-xs-offset-3">

                       <button  class="btn btn-primary btn-next" id="btn-next" name="submit" type="submit" onclick="return validatedoctorprofilesteps()" >Save and continue</button></a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

</div>

    </div>

    </div>

<script>

      function isNumberKey(evt)

      {

         var charCode = (evt.which) ? evt.which : event.keyCode

         if (charCode > 31 && (charCode < 48 || charCode > 57))

            return false;



         return true;

      }

</script>

<script>

    function validatedoctorprofilesteps(){

        var dob = $('#doctor_dob').val();

        var telephone = $('#telephone').val();

        var address1 = $('#address1').val();

        var city = $('#city').val();

        var state = $('#state').val();

        var country = $('#country').val();

        var zipcode = $('#zipcode').val();
		 var fax = $('#fax').val();

        if(dob == ''){

            alert("Please enter dob");

            return false;

        }else if(telephone == ''){

            alert("Please enter telephone");

            return false;

        }else if(address1 == ''){

           alert("Please enter street address line 1");

            return false; 

        }else if(city == ''){

            alert("Please enter city");

            return false;

        }else if(state == ''){

            alert("Please enter state");

            return false;

        }else if(country == ''){

            alert("Please enter country");

            return false;

        }else if(zipcode == ''){

           alert("Please enter zipcode");

            return false; 

        }else if(fax == ''){

           alert("Please enter fax no.");

            return false; 

        }

        

    }
    function populateCity(){
    var state =   $('#state').val();
     $.ajax({
            type: "POST",
            url: '<?php echo $remotelocation."populatecity.php";?>',
            data: {
                state_id:state
            },
            success: function(data) {
                 $('#city').empty();
                $('#city').append(data);
                }
        });
    }

</script>

<?php //require_once("includes/mfooter.php"); ?>