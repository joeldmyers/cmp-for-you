 <?php


require_once("includes/top.php");


require_once("includes/authentication.php");


?>


    <link href="<?php echo $remotelocation.'includes/css/Org_6958.css'?>" rel="stylesheet" type="text/css">


    <link href="<?php echo $remotelocation.'includes/css/Inbound_6958.css'?>" rel="stylesheet" type="text/css">


    <link href="<?php echo $remotelocation.'includes/css/bluetheme_6958.css'?>" rel="stylesheet" type="text/css">


    <style type="text/css">


        @-webkit-keyframes bounce_circle {


            0% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            25% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            50% {


                opacity: 0.5;


                background-color: #FE9E0C;


            }





            75% {


                opacity: 0.3;


                background-color: #FE9E0C;


            }





            100% {


                opacity: 0.1;


                background-color: #FE9E0C;


            }


        }





        @-ms-keyframes bounce_circle {


            0% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            25% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            50% {


                opacity: 0.5;


                background-color: #FE9E0C;


            }





            75% {


                opacity: 0.3;


                background-color: #FE9E0C;


            }





            100% {


                opacity: 0.1;


                background-color: #FE9E0C;


            }


        }





        @-moz-keyframes bounce_circle {


            0% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            25% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            50% {


                opacity: 0.5;


                background-color: #FE9E0C;


            }





            75% {


                opacity: 0.3;


                background-color: #FE9E0C;


            }





            100% {


                opacity: 0.1;


                background-color: #FE9E0C;


            }


        }





        @-o-keyframes bounce_circle {


            0% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            25% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            50% {


                opacity: 0.5;


                background-color: #FE9E0C;


            }





            75% {


                opacity: 0.3;


                background-color: #FE9E0C;


            }





            100% {


                opacity: 0.1;


                background-color: #FE9E0C;


            }


        }





        @keyframes bounce_circle {


            0% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            25% {


                opacity: 1;


                background-color: #FE9E0C;


            }





            50% {


                opacity: 0.5;


                background-color: #FE9E0C;


            }





            75% {


                opacity: 0.3;


                background-color: #FE9E0C;


            }





            100% {


                opacity: 0.1;


                background-color: #FE9E0C;


            }


        }


    </style>


<div class="aspNetHidden">


</div>


<?php require_once("includes/mheader.php"); ?>


<?php include 'includes/leftsidebar.php'; ?>


        


    <div class="col-md-5 col-xs-12">


    <h2 class="pad_btm20 pad_top10 pad_left10">Office Appointment</h2>


    <div class="searchbar">


     <div align="center" class="customerSiteSection" id="customerSiteSectionFrame" style=" margin: 0 auto; background :#ffffff;">


            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">


                <tbody><tr>


                    <td valign="top">


                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; zoom:1; box-sizing: border-box;">


                            <tbody><tr>


                                <td colspan="2">


                                    <div style="width: 100%;max-width: 1024px; margin: 0 auto; padding:0;box-sizing: border-box; background :#f9f9f9;">


                                        


    <div id="initialLoading" style="width: 100%; min-height: 540px; display: none; background-color: white;">


        


    </div>


    <div id="wizardDisplay" style="display: block;">


        


        <div style="position: relative; width: 0; float: left;">


            <div id="LeftColumn" align="left" style="float: left; width: 191px; margin-left: 12px;">


                <div class="grayBox gradient" style="width: 100%;">


                    <div class="lightBox">


                        <div class="grayBoxHead">


                            <table cellpadding="0" cellspacing="0" border="0" width="191">


                                <tbody><tr valign="top">


                                    <td style="max-width:76px;">


                                        <div id="image" class="rad8 siteImg" style="float: left;max-width: 69px; padding-right: 0px;">


                                        </div>


                                    </td>


                                    <td>


                                        


                                    </td>


                                </tr>


                            </tbody></table>


                        </div>


                        


                        


                        <div class="vSpace">&nbsp;</div>





                    </div>


                </div>


                


            </div>


        </div>


        


        <div id="" style="box-sizing: border-box; float: left; width: 100%">


            <!--<div id="BlueBox" class="blueBox gradient" style="height: 720px; box-sizing: border-box; width: 100%;">-->


<div class="calender" style="float:left;" >  


<?php


error_reporting(0);


class Calendar{


    var $currentMonth;


    var $currentYear;


    var $currentDate;


    var $currentTime;


    var $prevTime;


    var $nextTime;


    var $weekDays;





    # func to initialise calendar


    function LoadCalendar($timeStamp){


        if(!empty($timeStamp)){


            $this->currentYear =  date('Y',$timeStamp);


            $this->currentMonth = date('m',$timeStamp); 


        }else{


            $this->currentYear =  date('Y');


            $this->currentMonth = date('m');


        }


        $this->currentDate = date('d');


        $this->currentTime = mktime(0, 0, 0, $this->currentMonth , 1 , $this->currentYear);


        $prevMonth = $this->currentMonth - 1;


        $nextMonth = $this->currentMonth + 1;


        //$this->prevTime = mktime(0, 0, 0, $prevMonth , 1 , $this->currentYear);


        $this->nextTime = mktime(0, 0, 0, $nextMonth , 1 , $this->currentYear);


        $this->weekDays = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');


    }





    # func to display calendar


    function DisplayCalendar($timeStamp=""){


        $this->LoadCalendar($timeStamp);


        $currentTime = mktime(0, 0, 0, $this->currentMonth , 1 , $this->currentYear);


        $prevMonth = $this->currentMonth - 1;


        $nextMonth = $this->currentMonth + 1;


        $prevTime = mktime(0, 0, 0, $prevMonth , 1 , $this->currentYear);


        $nextTime = mktime(0, 0, 0, $nextMonth , 1 , $this->currentYear);





        $monthText = date("F",$currentTime);


        $yearText = date("Y",$currentTime);


        $dayText = date("d",$currentTime);





        $totalDays = date('t', $currentTime);


        $firstDay = date("D", $currentTime);


        $weekDays = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');


        $listMonth = array();


        $prevMonthText = date('F', $prevTime);        


        $nextMonthText = date('F', $nextTime);





        $today = mktime();


        # to find the day of 1st day in a month


        $index = array_search($firstDay, $weekDays);


        # starts to print calender


        $body='


     <style>











            #BlueBox{


            background-color:#ffffff;


 }


            .calender{width:100%;}


           


            .header_tab{


                padding:10px 0 20px 0;


                font-size:15px;


                color:#5e5e5e;


            }





            .header_tab A{


                text-decoration:none;


                color:#0092e8;


            }


            .cal_tab{


                padding:0px;


                text-align:center;


                vertical-align:center;


                width:90%;


            }


            .cal_tab td A{


                color:black;


                text-decoration:none;


            }


            .cal_tab .cal_head{


                background-color:#5e5e5e;


                color:white;


                height:50px;


            }    


            


            .copy {


                width:950px;


                float:center;


                text-align:center;


                font-size:9px;


                color:#515151;                


                padding-top:4px;


                padding-right:50px;


            }         


            


            .copy a {


                font-family:Arial;


                font-size:9px;


                color:#7F7F7F;


                text-decoration:none;


                display:inline-block;


                padding:0 10px 0 10px;


            }


            table#caltab tr td:hover{background-color:#e7e7e7}


            #timebooked p{


            font-size:12px 1 px solid;


        }


            </style>


        <head><meta http-equiv="Content-Type" content="text/html; charset=shift_jis">


        <body>


        <table align="center" border="0" class="opaque header_tab" >


            <tr>


                <th style="padding-right:150px;">';


if($currentTime >= $prevTime){


                $body.='<a class="noBorder" href="'.$remotelocation.'office_appointment.php?timeStamp='.$prevTime.'">'."<<".'</a>';


            }


                $body.='</td>


                <th><b>'.$monthText . " - ".$yearText.'</b></td>


                <th style="padding-left:150px;"><a class="noBorder" href="'.$remotelocation.'?timeStamp='.$nextTime.'">'.">>".'</a></td>


            </tr>


        </table>


        <p>


        </p>


        <table align="center" id="caltab" cellpadding="0px" cellspacing="0px" border="1px" class="cal_tab">


            <tr class="cal_head">


                <td>Sun</td>


                <td>Mon</td>


                <td>Tue</td>


                <td>Wed</td>


                <td>Thu</td>


                <td>Fri</td>


                <td>Sat</td>


            </tr>';





        $w = 0;


        for($m = 1; $m <= $totalDays ; ){


            $d = ($m == 1) ? $index : 0;


            for( ; $d <= 6 ; $d++){


                $listMonth[$w][$d] = $m;


                $m++;


                if($m > $totalDays){break;}


            }


            $w++;


        }





        # to get total details of a month


        $j=1;


        foreach($listMonth as $styleIdx => $listWeek){


            $body.= "<tr>";


            for($i = 0 ; $i <= 6 ; $i++){


                if (isset($listWeek[$i])) {


                    $day = $listWeek[$i] < 10 ? "0".$listWeek[$i] : $listWeek[$i];


                    $month = $this->currentMonth;


                    $content = "<a  ";





                    $content.="onclick='get_day(";


                    $content.='"'.$day.'","'.$monthText.'","'.$yearText.'","'.$i.$j.'"';


                    $content.=" );' ";


                    $content.= ">$day</a>";





                } else {


                    $content = "&nbsp;";


                }


                $style = ($styleIdx % 2) ? "background-color:white" : "";


                if($listWeek[$i] == date('d')){ 


                    $style = "background-color:white;"; 


                }


                $body.= "<td width='200px' id='currentdate".$i.$j."' height='70px'> {$content}</td>";


            }


            $body.= "</tr>";


        $j++;}


        $body.= '</table>


             


              ';


echo $body;





            


    }





    # func to display calendar header


    function DisplayHeader($class="",$td_class=""){


        $tmp = "<tr id='col'>";


        foreach($this->weekDays as $day){


            $tmp .= "<td class='$td_class'>$day</td>";


        }


        $tmp .= "</tr>";


        return $tmp;


    }





}





# call calendar class to display calendar


$calendar = New Calendar();


$calendar->DisplayCalendar($_GET['timeStamp']);


?>


</div>


<div id="selectdate" style="margin-top:200px;" ><strong style="font-size:14px;">Click on dates in white cells


to see available times</strong>


    <img src="<?php echo $remotelocation.'includes/images/arrow.jpg'?>">


</div>


<b id="available" style="margin-bottom:20px;display:none;font-size:12px" ></b></br>


<div class="scroll" style="margin-left:20px" >


<div class="timeshedule1" style="float: left;


  width: 25%;


  margin: 35px 1% 35px 0;


  font-size: 11px;display:none;


}">


<!--<strong style="font-size: 11px;">Morning</strong></br></br>-->


<input type="checkbox" onclick="get_shedule_time()" id="chk1" name="chk[]" style="margin-bottom: 10px;" value="7:00 - 7:30 AM"> 07:00 AM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value="7:30 - 8:00 AM"> 07:30 AM<br><input type="checkbox" onclick="get_shedule_time()" id="chk1" name="chk[]" style="margin-bottom: 10px;" value="8:00 - 8:30 AM"> 08:00 AM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value="8:30 - 9:00 AM"> 08:30 AM<br><input type="checkbox" onclick="get_shedule_time()" id="chk1" name="chk[]" style="margin-bottom: 10px;" value="9:00 - 9:30 AM">09:00 AM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value=" 9:30 - 10:00 AM"> 09:30 AM<br><input type="checkbox" onclick="get_shedule_time()" id="chk1" name="chk[]" style="margin-bottom: 10px;" value="10:00 - 10:30 AM">10:00 AM<br>


</div>





<div class="timeshedule2" style="float: left;


  width: 25%;


  margin: 35px 1%;


  font-size: 11px;display:none;


}">


<!--<strong style="font-size: 11px;">After Noon</strong></br></br>-->





<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value="10:30 - 11:00 AM"> 10:30 AM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value=" 11:00 - 11:30 AM">11:00 AM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value="11:30 - 12:00 AM">11:30 AM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value="12:00 - 12:30 PM">12:00 PM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value="12:30 - 01:00 PM">12:30 PM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk2" name="chk[]"  style="margin-bottom: 10px;" value="1:00 - 1:30 PM"> 01:00 PM<br>

</div>





<div class="timeshedule3" style="float: left;


  width: 25%;


  margin: 35px 0px;


  font-size: 11px;display:none;">


  <!--<strong style="font-size: 11px;">Evening</strong></br></br>-->


  


<input type="checkbox" onclick="get_shedule_time()" id="chk10" name="chk[]" style="margin-bottom: 10px;" value="1:30 - 2:00 PM">01:30 PM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk11" name="chk[]"  style="margin-bottom: 10px;" value="2:00 - 2:30 PM">02:00 PM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk12" name="chk[]"  style="margin-bottom: 10px;" value="2:30 - 3:00 PM">02:30 PM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk13" name="chk[]"  style="margin-bottom: 10px;" value="3:30 - 3:30 PM">03:00 PM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk14" name="chk[]" style="margin-bottom: 10px;" value="3:30 - 4:00 PM">03:30 PM<br>


<input type="checkbox" onclick="get_shedule_time()" id="chk14" name="chk[]" style="margin-bottom: 10px;" value="4:00 - 4:30 PM">04:00 PM<br>


</div>





<div class="timeshedule4" style="float: left;


  width: 20%;


  margin: 35px 0;


  font-size: 11px;display:none;">


  <!--<strong style="font-size: 11px;">After Evening</strong></br></br>-->


  <input type="checkbox" onclick="get_shedule_time()" id="chk26" name="chk[]" style="margin-bottom: 10px;" value="4:30 - 5:00 PM">4:30 PM  </br>


<input type="checkbox" onclick="get_shedule_time()" id="chk26" name="chk[]" style="margin-bottom: 10px;" value="5:00 - 5:30 PM">5:00 PM</br>  


<input type="checkbox" onclick="get_shedule_time()" id="chk26" name="chk[]" style="margin-bottom: 10px;" value="5:30 - 6:00 PM">5:30 PM</br>


<input type="checkbox" onclick="get_shedule_time()" id="chk26" name="chk[]" style="margin-bottom: 10px;" value="6:00 - 6:30 PM">6:00 PM</br>


<input type="checkbox" onclick="get_shedule_time()" id="chk26" name="chk[]" style="margin-bottom: 10px;" value="6:30 - 7:00 PM">6:30 PM</br>


<input type="checkbox" onclick="get_shedule_time()" id="chk26" name="chk[]" style="margin-bottom: 10px;" value="7:00 - 7:30 PM"> 7:00 PM  </br>

</div>


</div>


<div class="submit" id="nextsubmit" style="display:none">


<form action="office_booking_confirm.php" method="post" >


    <input type="hidden" name="sheduledatec" id="sheduledatec">
 <input type="hidden" name="token_id" value="<?php echo @$_REQUEST['token_id'] ?>">
	<input type="hidden" name="doc_id" value="<?php echo @$_REQUEST['id'] ?>">
	<input type="hidden" name="doctor_id" value="<?php echo @ $_REQUEST['id'] ?>">
    <input type ="submit" name="submit" class="btn btn-default pull-right"  value="Next">


</form>


</div>  





</div>


<div id="timebooked" style="float: left;


  margin-left: 0px;


  border: 2px solid red;
margin-bottom:20px;

  width: 93%;


  padding-left: 50px;display:none;


  "></div> 


        </div>


        <div id="PopUpDivContainer" style="position: absolute; top: 0; left: 0; z-index: 1000">


        <div id="result-2" class="opaque" style="position: fixed; left: 0px; top: 0px; bottom: 0px; right: 0px; z-index: 29990; width: 1349px; height: 775px; background-color: black;">


        </div>


           


    </div>


           


<?php if($_REQUEST['timeStamp']==""){?>


        <div id="inviteeWelcomePopUp" class="welcomePopupDiv" style="left: 0px; top: 50px; visibility: visible; display: block;" ispopupclick="1" onselectstart="return false;">


            <div class="welcomeHeaderBlue">


                <table cellpadding="0" cellspacing="0" style="width: 100%">


                    <tbody><tr>


                        <td style="width: 20px;">


                            


                        </td>


                        <td>


                            <span class="textHeader16" style="float: left; padding-left: 0px; width: auto; padding-top: 18px;">Your time zone


                            </span>


                            <!--- <span style="font-size: 18px; float: right; margin-top: 21px; margin-right: 16px; cursor: pointer;" onclick="ClosePopup('inviteeWelcomePopUp'),CloseInviteeWelcomePopUp(true);">X</span> --->


                        </td>


                    </tr>


                </tbody></table>





                <!--<div class="closetimepop">X </div>-->





            </div>


            <div style="padding: 22px 20px 22px 20px; -webkit-border-bottom-right-radius: 4px; -webkit-border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px;">


                <div style="text-align: left; font-size: 16px; margin: 0px 0px 10px 0px;">


                    All times will be displayed according to your time zone:


                </div>


                <div style="padding-top: 10px;">


                    








<table cellpadding="0" cellspacing="0">


<tbody><tr>


<td>


<select name="sltCountry" id="sltCountry" class="textInput2" style="width:184px;height:22px" >


<?php //$query=mysql_query("select * from country");


//while($res=mysql_fetch_array($query)){


?>


<option value="United States">United States</option>


<?php //}?>


</select>


        </td>


        <td style="width:10px">&nbsp;</td>


        <td>


               <select name="sltTimeZone" id="result12" class="textInput2" style="width:283px;height:22px;">


                <option value="0">Select time zone region</option>


                <option value="Eastern time (GMT-4)  [DST]">Eastern time (GMT-4)  [DST]</option>


                <option value="Central time (GMT-5)  [DST]">Central time (GMT-5)  [DST]</option>


                <option value="Arizona (GMT-7)">Arizona (GMT-7) </option>


                <option value="Mountain time (GMT-6)  [DST]">Mountain time (GMT-6)  [DST]</option>


                <option value="Pacific time (GMT-7)  [DST]">Pacific time (GMT-7)  [DST]</option>


                <option value="Alaska (GMT-8)  [DST]">Alaska (GMT-8)  [DST]</option>


                <option value="Alaska (Aleutian Islands) (GMT-9)  [DST]">Alaska (Aleutian Islands) (GMT-9)  [DST]</option>


                <option value="Hawaii (GMT-10) ">Hawaii (GMT-10) </option>


               </select>


        </td>


    </tr>


</tbody></table>


 </div>





                <div class="vSpace" style="height: 30px; padding-top: 22px;">


                    <div style="float: right;">


                        <input id="tzConfirmCloseB" type="submit" class="btn btn-default" value="Next" onclick="get_calender()">


                    </div>


                </div>


            </div>


            </form>


            <div class="welcomeFooterBlue" style="display: none;">&nbsp;</div>


               


        </div>


<?php } ?>





 


    </div>


    





    


    


    


    


 </div>


                                </td>


                            </tr>


                             


                            <tr>


                                <td>


                                    <div style=" position: relative; left: 25px;">


                                        


                                        


                                    </div>


                                </td>


                                <td style="vertical-align: top;">


                                    <div style=" position: relative; left: 52px; top:30px;">


                                        


                                        


                                    </div>


                                </td>


                            </tr>


                            <tr>


                                <td colspan="2">


                                    <div style="width:100%; position: relative; left: 25px;">


                                        


                                        


                                    </div>


                                </td>


                            </tr>


                                    


                        </tbody></table>


                    </td>


                </tr>

            </tbody></table>


        </div>


        </div>


        </div>


    </form>


<?php include 'includes/rightsidebar.php'; ?> 


 <?php include 'includes/mfooter.php'; ?> 


<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>


<script>


function get_calender(){


var idx= document.getElementById('result12').selectedIndex;


    var e= document.getElementById('result12');


    if(idx==0){


        e.focus();


        e.style.outlineColor = "red";


        return false;


    }else{





$('#inviteeWelcomePopUp').hide();


$('#result-2').removeAttr('style');


}


}


</script>





 <script>


function get_day(day,month,year,id){


var datetime=month+''+day+","+year;


$('tr td').removeAttr('style');


$('#currentdate'+id).css("background-color","#009900");


var msec = Date.parse(datetime);


var d = new Date(msec);


var days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];


$('.timeshedule1').show();


$('.timeshedule3').show();


$('.timeshedule4').show();


$('.timeshedule2').show();


$('#available').show();


$('#selectdate').hide();


document.getElementById("available").innerHTML = "Available starting times for  " +days[d.getDay()]+" "+month+"  "+day+","+year;


}


 </script> 


 <script>


 /*function get_timezone(cid){


  var cid=cid;


      if (window.XMLHttpRequest){


          var mygetrequest=new XMLHttpRequest();


            } else {


           var mygetrequest=new ActiveXObject("Microsoft.XMLHTTP");


           }


         mygetrequest.onreadystatechange=function(){


          if (mygetrequest.readyState==4){


     if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1)


     {


       document.getElementById('result12').innerHTML=mygetrequest.responseText


       }


        else{


          }


        }


       }


       var post='cid='+cid;


       mygetrequest.open("post","get-timezone.php",true);


       mygetrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset=UTF-8 ");


       mygetrequest.send(post);


      }


    get_timezone($('#sltCountry').val());*/


  </script>


  <script>


/*function get_shedule_time(time){


//alert(time);


    document.getElementById('timebooked').innerHTML=time;


}*/


  function get_shedule_time() {


           $('#timebooked').show();


           $('#nextsubmit').show();           


            var checkedvalue = "";


            var rows = document.getElementsByName('chk[]');


            var selectedRows = [];


            for (var i = 0, l = rows.length; i < l; i++) {


                if (rows[i].checked) {


                    selectedRows.push(rows[i]);


                    var sdate=$("#available").html();


                    var len=sdate.length;


                    var lastlen=len-29;     


                    var res= sdate.substr(sdate.length - lastlen);





                    var country=$("#sltCountry").val();


                    var zone =$("#result12").val();                                


                    checkedvalue= checkedvalue + "<p>"+res+" ,"+ rows[i].value +"</p>"; 


                    $("#sheduledatec").val(checkedvalue);               


                }


            }


            


        


            document.getElementById('timebooked').innerHTML=checkedvalue;


        }


  </script>


  <?php if($_REQUEST['timeStamp']!=""){?>


<script>


$('#result-2').removeAttr('style');


</script>


 <?php  } ?>
