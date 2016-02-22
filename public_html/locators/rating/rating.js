
    $(document).ready(function() {
 $('.ratings_stars').hover(

            // Handles the mouseover

            function() {

                $(this).prevAll().andSelf().addClass('ratings_over');
               

            },

            // Handles the mouseout

            function() {

                $(this).prevAll().andSelf().removeClass('ratings_over');

            }

        );
//send ajax request to rate.php
        $('.ratings_stars').bind('click', function() {
			
			var id=$(this).parent().attr("id");
			
		    var num=$(this).attr("class");
			var doctor_id=$("#doctor_id").val();
			var patient_id=$("#patient_id").val();
			var booking_id=$("#booking_id").val();
			var poststr="id="+id+"&stars="+num+"&doctor_id="+doctor_id+"&patient_id="+patient_id+"&booking_id="+booking_id;
			
		$.ajax({url:"rate.php",cache:0,data:poststr,success:function(result){
		
   document.getElementById(id).innerHTML=result;}
   });	
		});

 
        });

        
