<?php
	session_start();
	session_destroy();
	session_unset();

?>
	<script type="text/javascript">
		window.location = "login.php"
	</script>