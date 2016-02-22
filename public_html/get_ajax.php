<?php 
include 'dbconnect.php';

if(isset($_POST['single_id']))
{
    $select_id = $_POST['single_id'];
    $gender = @$_POST['gender'];
    
    $sql = "select `area_descr` from  body_areas where `area_id` = '".$select_id."'";
    $query = mysqli_query($db_conx,$sql);
    $body_name = mysqli_fetch_row($query);
    
    if($gender == 'Male')
    {
    	$sql = "select * from symptoms where `symptom_area` = '".$body_name[0]."' && `symptom_sex` = '".$gender."'";	
    	$result = mysqli_query($db_conx,$sql); 
        $count = mysqli_num_rows($result); ?>
    	<h4 class="heading">Select <?php echo $body_name[0]; ?> Category</h4>
    	<?php
        $index = 0;
        if($count != 0){
        	while($man_body_name = mysqli_fetch_assoc($result))
        	{ 
        	    echo "<label class='radio-inline' for='male_sym_".$index."'>";
    			echo "<input type='radio' id='male_sym_".$index."' name='male_sym_id' class='male_sym_id' value='".$man_body_name['symptom_descr']."'>";
    			echo $man_body_name['symptom_descr']."</label>";
                $index++;
        	}
        }else{
            echo "<h5 class='text-center'>Category ".$body_name[0]." Not Found!</h5>";
        }
    }
    if($gender == 'Female')
    {
    	$sql = "select * from symptoms where `symptom_area` = '".$body_name[0]."' && `symptom_sex` = '".$gender."'";
    	$result = mysqli_query($db_conx,$sql);
        $count = mysqli_num_rows($result); ?>
    	<h4 class="heading">Select <?php echo $body_name[0]; ?> Category</h4>
    	<?php
        if($count != 0){
        	while($man_body_name = mysqli_fetch_assoc($result))
        	{ 
        	    echo "<label class='radio-inline' for='fem_sym_".$index."'>";
    			echo "<input type='radio' id='fem_sym_".$index."' name='fem_sym_id' class='fem_sym_id' value='".$man_body_name['symptom_descr']."'>";
    			echo $man_body_name['symptom_descr']."</label>";
                $index++;
        	}
        }else{
            echo "<h5 class='text-center'>Category ".$body_name[0]." Not Found!</h5>";
        }
    }
}
