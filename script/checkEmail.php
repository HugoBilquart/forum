<?php

function DBConnection() {
	$dbconn = new PDO('sqlite:../db/db.sqlite');
	if($dbconn) {
		return $dbconn;
	}
	else {
		echo '<p class="loginFailed">//Database connection... FAILED';
	}
}

$db = DBConnection();
$query = 'SELECT * FROM users WHERE email ="'.$_POST['new_email'].'"';

$return = $db->query($query);

$line = $return->fetch();

if(empty($line)) {
	echo "available";
}
else {
	echo 'used';
}

?>