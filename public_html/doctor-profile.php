<?php require_once("includes/top.php");extract($_REQUEST);if(isset($doctoruid) && $doctoruid!='')	{	$data = $db->Execute("select", "SELECT *,concat(coalesce(first_name,''),' ',coalesce(middle_name,''),' ',coalesce(last_name,'')) as name,concat(coalesce(street_address_line1,''),' ',coalesce(street_address_line2,'')) as address,city,doctor_state FROM ".MEDIC. " where doctor_pseudoname like '%".$doctoruid."%' ");
 $name=$data[0]['name'];
$address=$data[0]['address'];

$city=$data[0]['city'];
$state=$data[0]['doctor_state'];
$zipcode=$data[0]['zipcode'];
 if (isset($data[0]['gender']) && $data[0]['gender'] == 'M') {
	$img=$remotelocation . "includes/images/profilepic3.png";
	$gender="Male";
 } else {
	$img=$remotelocation . "includes/images/profilepic2.png";
	$gender="Female";
 }}?><?php include 'includes/header.php'; ?><div class="container">
        <section style="padding-bottom: 50px; padding-top: 50px;">
            <div class="row">
                <div class="col-md-4">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img align="middle" alt="User profile picture" src="<?php echo $img; ?>" class="profile-user-img img-responsive img-circle">

              <h3 style="margin-left:-120px" class="profile-username text-center"><?php echo $name; ?></h3>

              <p style="margin-left:-150px" class="text-muted text-center"><?php echo $data[0]['credential']; ?></p>

              <ul class="list-group list-group-unbordered">
			  
                <li class="list-group-item">
                  <b>Userid</b> <a class="pull-right"><?php echo $data[0]['doctor_pseudoname']; ?></a>
                </li>
				 <li class="list-group-item">
                  <b>Gender</b> <a class="pull-right"><?php echo $gender; ?></a>
                </li>
				 <li class="list-group-item">
                  <b>DOB</b> <a class="pull-right"><?php echo $data[0]['doctor_dob']; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Email id</b> <a class="pull-right"><?php echo str_repeat("X",strlen($data[0]['email'])) ?></a>
                </li>
               
              </ul>

              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Doctor</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

              <p class="text-muted">
                <?php echo $data[0]['credential']; ?>  from the <?php echo $data[0]['medical_school']; ?> Medical school in <?php echo $data[0]['graduation_year']; ?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

              <p class="text-muted"><?php echo $state; ?>,<?php echo $city; ?>,<?php echo $zipcode; ?></p>

              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Speciality</strong>

              <p>
                <span class="label label-danger"><?php echo $data[0]['primary_speciality']; ?></span>
                <span class="label label-success"><?php echo $data[0]['secondary_speciality_1']; ?></span>
               
              </p>

              <hr>

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

              <p><?php echo $data[0]['about_doctor']; ?></p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
                <div class="col-md-8">
                     <div class="description">
                         
                     <p>
						     <table class="table table-striped table-bordered table-condensed">
								<tr class="success">
									<th colspan="2"  class="text-center">Address Details</th>
								</tr>
								<tr>
									<th>Street Address Line 1</th>
									<td><?php echo $data[0]['street_address_line1']; ?></th>
								</tr>
								<tr>
									<th>Street Address Line 2</th>
									<td><?php echo $data[0]['street_address_line2']; ?></th>
								</tr>
								<tr>
									<th>Country</th>
									<td>Unites States</th>
								</tr>
								<tr>
									<th>State</th>
									<td><?php echo $data[0]['doctor_state']; ?></th>
								</tr>
								<tr>
									<th>City</th>
									<td><?php echo $data[0]['city']; ?></th>
								</tr>
								<tr>
									<th>Zip Code</th>
									<td><?php echo $data[0]['zipcode']; ?></th>
								</tr>
							</table> 
                     </p>
					 <hr> 
						<p>
						     <table class="table table-striped table-bordered table-condensed">
								<tr class="success">
									<th colspan="2"  class="text-center">Personal Information Details</th>
								</tr>
								<tr>
									<th>Credential</th>
									<td><?php echo $data[0]['credential']; ?></th>
								</tr>
								<tr>
									<th>Medical School Name</th>
									<td><?php echo $data[0]['medical_school']; ?></th>
								</tr>
								<tr>
									<th>Graduation Year</th>
									<td><?php echo $data[0]['graduation_year']; ?></th>
								</tr>
								<tr>
									<th>Pac Id</th>
									<td><?php echo $data[0]['pac_id']; ?></th>
								</tr>
								<tr>
									<th>NPI</th>
									<td><?php echo $data[0]['npi']; ?></th>
								</tr>
								<tr>
									<th>Professional Enroll Id</th>
									<td><?php echo $data[0]['professional_enroll_id']; ?></th>
								</tr>
								<tr>
									<th>Group Practice Pac Id</th>
									<td><?php echo $data[0]['group_practice_pac_id']; ?></th>
								</tr>
								<tr>
									<th>No Of Group Practice Members</th>
									<td><?php echo $data[0]['no_group_practice_members']; ?></th>
								</tr>
							</table> 
                     </p>					 
                    <p>
						     <table class="table table-striped table-bordered table-condensed">
								<tr class="success">
									<th colspan="2"  class="text-center">Speciality Information</th>
								</tr>
								<tr>
									<th>Primary Speciality</th>
									<td><?php echo $data[0]['primary_speciality']; ?></th>
								</tr>
								<tr>
									<th>Secondary Speciality 1</th>
									<td><?php echo $data[0]['secondary_speciality_1']; ?></th>
								</tr>
								<tr>
									<th>Secondary Speciality 2</th>
									<td><?php echo $data[0]['secondary_speciality_2']; ?></th>
								</tr>
								<tr>
									<th>Secondary Speciality 3</th>
									<td><?php echo $data[0]['secondary_speciality_3']; ?></th>
								</tr>
								<tr>
									<th>Video Consultation Fee</th>
									<td>$<?php echo $data[0]['video_consult_fee']; ?></th>
								</tr>
								<tr>
									<th>Office Consultation Fee</th>
									<td>$<?php echo $data[0]['person_consult_fee']; ?></th>
								</tr>
								<tr>
									<th>Organisation Legal Name</th>
									<td><?php echo $data[0]['organization_legal_name']; ?></th>
								</tr>
								<tr>
									<th>Organisation DBA Name</th>
									<td><?php echo $data[0]['organization_dba_name']; ?></th>
								</tr>
							</table> 
                     </p>		
                                 
                 
                    </div>
            </div>
            <!-- ROW END -->


        </section>
        <!-- SECTION END -->
    </div>
</div>	