<?php
	session_start();
	session_destroy();
	session_unset();

?>
	<script type="text/javascript">
		window.location = "doctor_login.php"
	</script>