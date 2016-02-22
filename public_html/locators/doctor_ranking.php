<?php
require_once("../includes/top.php");
 extract($_REQUEST);
 $booking =  $db->Execute("select", "SELECT * FROM ".BOOKING."  where status=2 AND booking_patient_id = '".$_SESSION['emp_id']."' AND booking_id='$bookingid'");
 $doctor_id=$booking[0]['bookdoc_id'];
 $patient_id=$_SESSION['emp_id'];
$voted1="";
$voted="";
$units=5;
/******************************************************* 1st ranking *********************************/
$id_sent1=1;
if($id_sent1==1){

$voted=mysql_num_rows(mysql_query("SELECT * FROM ratings WHERE id_rate=$id_sent1 AND doctor_id='$doctor_id' "));
									

$newtotals = mysql_query("SELECT sum(total_votes) as total_votes, SUM(total_value) as total_value  FROM ratings WHERE id_rate=$id_sent1 AND doctor_id='$doctor_id' GROUP BY doctor_id")or die(" Error: ".mysql_error());
$numbers = mysql_fetch_assoc($newtotals);
$count = $numbers['total_votes'];//how many votes total
$current_rating = $numbers['total_value'];//total number of rating added together and stored
$tense = ($count==1) ? "vote" : "votes"; //plural form votes/vote

// $new_back is what gets 'drawn' on your page after a successful 'AJAX/Javascript' vote
if($voted){$sum=$current_rating; $added=$count;}
$new_back = array();
for($i=0;$i<5;$i++){
	$j=$i+1;
	if($i<@number_format($current_rating/$count,1)-0.5) $class="ratings_stars ratings_vote";
	else $class="ratings_stars";
$new_back[] .= '<div class="star_'.$j.' '.$class.'"></div>';
 $new_back[] .= ' <div class="total_votes"><p class="voted"> Rating: <strong>'.@number_format($sum/$added,1).'</strong>/'.$units.' ('.$count.' '.$tense.' cast) ';
if(!$voted)$new_back[] .= '<span class="thanks">Thanks for voting!</span></p>';
else {$new_back[] .= '<span class="invalid">Already voted for this item</span></p></div>';}
$allnewback = join("\n", $new_back);                     }
}
/********************************************** 2nd ranking **************************************/
$id_sent=2;
if($id_sent==2){

$voted1=mysql_num_rows(mysql_query("SELECT * FROM ratings WHERE id_rate=$id_sent AND doctor_id='$doctor_id' "));
									

$newtotals1 = mysql_query("SELECT sum(total_votes) as total_votes, SUM(total_value) as total_value  FROM ratings WHERE id_rate=$id_sent AND doctor_id='$doctor_id' GROUP BY doctor_id")or die(" Error: ".mysql_error());
$numbers1 = mysql_fetch_assoc($newtotals1);
$count1 = $numbers1['total_votes'];//how many votes total
$current_rating1 = $numbers1['total_value'];//total number of rating added together and stored
$tense1 = ($count1==1) ? "vote" : "votes"; //plural form votes/vote

// $new_back is what gets 'drawn' on your page after a successful 'AJAX/Javascript' vote
if($voted1){$sum1=$current_rating1; $added1=$count1;}
$new_back1 = array();
for($i=0;$i<5;$i++){
	$j=$i+1;
	if($i<@number_format($current_rating1/$count1,1)-0.5) $class="ratings_stars ratings_vote";
	else $class="ratings_stars";
$new_back1[] .= '<div class="star_'.$j.' '.$class.'"></div>';
 $new_back1[] .= ' <div class="total_votes"><p class="voted"> Rating: <strong>'.@number_format($sum1/$added1,1).'</strong>/'.$units.' ('.$count1.' '.$tense1.' cast) ';
if(!$voted1)$new_back1[] .= '<span class="thanks">Thanks for voting!</span></p>';
else {$new_back1[] .= '<span class="invalid">Already voted for this item</span></p></div>';}
$allnewback1 = join("\n", $new_back1);                     }
}
/******************************************************************************************************/

/********************************************** 2nd ranking **************************************/
$id_sent3=3;
if($id_sent3==3){

$voted2=mysql_num_rows(mysql_query("SELECT * FROM ratings WHERE id_rate=$id_sent3 AND doctor_id='$doctor_id' "));
									

$newtotals2= mysql_query("SELECT sum(total_votes) as total_votes, SUM(total_value) as total_value  FROM ratings WHERE id_rate=$id_sent3 AND doctor_id='$doctor_id' GROUP BY doctor_id")or die(" Error: ".mysql_error());
$numbers2 = mysql_fetch_assoc($newtotals2);
$count2 = $numbers2['total_votes'];//how many votes total
$current_rating2 = $numbers2['total_value'];//total number of rating added together and stored
$tense2 = ($count2==1) ? "vote" : "votes"; //plural form votes/vote

// $new_back is what gets 'drawn' on your page after a successful 'AJAX/Javascript' vote
if($voted2){$sum2=$current_rating2; $added2=$count2;}
$new_back2 = array();
for($i=0;$i<5;$i++){
	$j=$i+1;
	if($i<@number_format($current_rating2/$count2,1)-0.5) $class="ratings_stars ratings_vote";
	else $class="ratings_stars";
$new_back2[] .= '<div class="star_'.$j.' '.$class.'"></div>';
 $new_back2[] .= ' <div class="total_votes"><p class="voted"> Rating: <strong>'.@number_format($sum2/$added2,1).'</strong>/'.$units.' ('.$count2.' '.$tense2.' cast) ';
if(!$voted2)$new_back2[] .= '<span class="thanks">Thanks for voting!</span></p>';
else {$new_back2[] .= '<span class="invalid">Already voted for this item</span></p></div>';}
$allnewback2 = join("\n", $new_back2);                     }
}
/******************************************************************************************************/
?>
<html>
<head>
<script src="rating/jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="rating/rating.css" />
<script type="text/javascript" src="rating/rating.js"></script>
</head>
<body>
<input type="hidden" id="doctor_id" value="<?php echo  $doctor_id; ?>"/>
<input type="hidden" id="patient_id" value="<?php echo  $patient_id; ?>"/>
<input type="hidden" id="booking_id" value="<?php echo  $bookingid; ?>"/>
<div class='product'>
           <strong> Rate:  Overall experience  after a visit with the physician</strong><br/>
            <div id="rating_1" class="ratings">
				<?php
					if($voted){
					echo $allnewback ;
				?>
				
				<?php
				} else {
				?>
                <div class="star_1 ratings_stars"></div>
                <div class="star_2 ratings_stars"></div>
                <div class="star_3 ratings_stars"></div>
                <div class="star_4 ratings_stars"></div>
                <div class="star_5 ratings_stars"></div>
				 <div class="total_votes">vote data</div>
				<?php
				}
				?>
               
            </div>
        </div>
        <div class='product'>
            <strong>Rate: Courtesy and professionalism of the physician</strong><br/>
            <div id="rating_2" class="ratings">
			<?php
					if($voted1){
					echo $allnewback1 ;
				?>
				
				<?php
				} else {
				?>
                <div class="star_1 ratings_stars"></div>
                <div class="star_2 ratings_stars"></div>
                <div class="star_3 ratings_stars"></div>
                <div class="star_4 ratings_stars"></div>
                <div class="star_5 ratings_stars"></div>
                <div class="total_votes">vote data</div>
			<?php
			}
?>			
            </div>
			 <div class='product'>
           <strong> Rate: The way the physician treated patients at the bedside</strong><br/>
            <div id="rating_3" class="ratings">
			<?php
					if($voted2){
					echo $allnewback2;
				?>
				
				<?php
				} else {
				?>
                <div class="star_1 ratings_stars"></div>
                <div class="star_2 ratings_stars"></div>
                <div class="star_3 ratings_stars"></div>
                <div class="star_4 ratings_stars"></div>
                <div class="star_5 ratings_stars"></div>
                <div class="total_votes">vote data</div>
			<?php
			}
?>			
            </div>
			
			
			
			
        </div></body></html>