<?php
	$file = 'images/users_avatar/default.jpg';
	$newfile = 'images/users_avatar/new_user.png';

	echo "<p>$file</p>";
	echo "<p>$newfile</p>";

	if (!copy($file, $newfile)) {
    	echo "La copie $file du fichier a échoué...\n";
	}
	else {
		echo "La copie a fonctionné\n";
	}
?>