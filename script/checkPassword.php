<?php

function DBConnection() {
	$dbconn = new PDO('sqlite:../db/db_project.sqlite');
	if($dbconn) {
		return $dbconn;
	}
	else {
		echo '<p class="loginFailed">//Database connection... FAILED';
	}
}

$db = DBConnection();
include("../hash.php");
$hashed_password = crypt(''.$_POST['currentPass'].'', "$hash");

$query = 'SELECT * FROM users WHERE name = "'.$_POST['username'].'" AND password = "'.$hashed_password.'"';

$return = $db->query($query);

$line = $return->fetch();

if(empty($line)) {
	echo "wrong";
}
else {
	echo 'right';
}

?>