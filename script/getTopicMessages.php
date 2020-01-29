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
//$query = 'SELECT * FROM messages WHERE email ="'.$_POST['topic'].'"';

$request_message = 'SELECT users.id,messages.id AS message,name,role,signature,publish_date,content FROM users INNER JOIN messages ON users.id = messages.id_user WHERE messages.visible = 1 AND messages.id_topic ='.$_GET['topic'];

$return = $db->query($request_message);

$messages = $return->fetchAll();

echo json_encode($messages);

?>