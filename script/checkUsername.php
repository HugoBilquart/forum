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
$query = 'SELECT * FROM users WHERE name ="'.$_POST['new_username'].'"';

$return = $db->query($query);

$line = $return->fetch();

if(empty($line)) {
	echo "available";
}
else {
	echo 'taken';
}

?>