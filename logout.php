<meta charset="utf8">
<?php
	session_start();
	if($_SESSION) {
		$_SESSION = array();
		session_destroy();
		unset($_SESSION);
	}
	else {
		echo "You're not logged";			
	}
	echo '<script type="text/javascript"> window.location = "index.php?page=home"; </script>';
	exit();
?>