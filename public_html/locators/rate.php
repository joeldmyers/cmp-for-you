<?php
header("Cache-Control: no-cache");
header("Pragma: nocache");
$units=5;
require_once("../includes/top.php");
$id_sent = preg_replace("/[^0-9]/","",$_REQUEST['id']);
$vote_sent = preg_replace("/[^0-9]/","",$_REQUEST['stars']);
$ip =$_SERVER['REMOTE_ADDR'] ;
extract($_REQUEST);

$q=mysql_num_rows(mysql_query("select * from ratings where id_rate=$id_sent AND doctor_id='$doctor_id' AND user_id='$patient_id' AND conf_id='$booking_id'"));
if(!$q)mysql_query("insert into ratings (id_rate,date,user_id,doctor_id,conf_id) values ($id_sent,curdate(),'$patient_id','$doctor_id','$booking_id')");
if ($vote_sent > $units) die("Sorry, vote appears to be invalid."); // kill the script because normal users will never see this.



//connecting to the database to get some information
$query = mysql_query("SELECT SUM(total_votes) as total_votes, SUM(total_value) as total_value  FROM ratings WHERE id_rate='$id_sent' AND doctor_id='$doctor_id' GROUP BY doctor_id ")or die(" Error: ".mysql_error());
$numbers = mysql_fetch_assoc($query);
$count = $numbers['total_votes']; //how many votes total
$current_rating = $numbers['total_value']; //total number of rating added together and stored
$sum = $vote_sent+$current_rating; // add together the current vote value and the total vote value
$tense = ($count==1) ? "vote" : "votes"; //plural form votes/vote

// checking to see if the first vote has been tallied
// or increment the current number of votes
($sum==0 ? $added=0 : $added=$count+1);

//IP check when voting
$voted=mysql_num_rows(mysql_query("SELECT * FROM ratings where id_rate=$id_sent AND doctor_id='$doctor_id' AND user_id='$patient_id' AND conf_id='$booking_id' AND total_votes!=0 "));
				
if(!$voted) {     //if the user hasn't yet voted, then vote normally...

	if (($vote_sent >= 1 && $vote_sent <= $units)) { // keep votes within range, make sure IP matches 
	
		$update = "UPDATE ratings SET total_votes='".$added."', total_value='".$sum."' WHERE id_rate=$id_sent AND doctor_id='$doctor_id' AND user_id='$patient_id' AND conf_id='$booking_id'";
		
		$result = mysql_query($update);	
		//if($result)	setcookie("rating_".$id_sent,1, time()+ 2592000);
	} 
} //end for the "if(!$voted)"
// these are new queries to get the new values!
$newtotals = mysql_query("SELECT sum(total_votes) as total_votes, SUM(total_value) as total_value  FROM ratings WHERE id_rate=$id_sent AND doctor_id='$doctor_id' ")or die(" Error: ".mysql_error());
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
                      }

$new_back[] .= ' <div class="total_votes"><p class="voted"> Rating: <strong>'.@number_format($sum/$added,1).'</strong>/'.$units.' ('.$count.' '.$tense.' cast) ';
if(!$voted)$new_back[] .= '<span class="thanks">Thanks for voting!</span></p>';
else {$new_back[] .= '<span class="invalid">Already voted for this item</span></p></div>';}
$allnewback = join("\n", $new_back);

// ========================


$output = $allnewback;
echo $output;
?>