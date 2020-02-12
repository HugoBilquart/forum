<?php
	function DBConnection() {
		$dbconn = new PDO('sqlite:db/db.sqlite');
		if($dbconn) {
			return $dbconn;
		}
		else {
			echo '<p class="loginFailed">//Database connection... FAILED';
		}
	}

	function roleStr($role) {
		switch ($role) {
			case '1':
				return "Member";
			break;
		
			case '2':
				return "Moderator";
			break;

			case '3':
				return "Administrator";
			break;
		}
	}

	function showRole($role) {
		switch ($role) {
			case '1':
				$_SESSION['role'] = "member";
			break;
		
			case '2':
				$_SESSION['role'] = "moderator";
			break;

			case '3':
				$_SESSION['role'] = "admin";
			break;
		}
	}

	function roleINT_CHAR($role) {
		switch ($role) {
			case '0':
				$roleChar = "Banned";
				break;

			case '1':
				$roleChar = "Member";
				break;
			
			case '2':
				$roleChar = "Moderator";
				break;

			case '3':
				$roleChar = "Admin";
				break;
		}
		return $roleChar;
	}

	function topicTableTop() {
		echo '	<table class="topics"><tr style="background-color: #009996"><td id="corner" class="topic_folder"></td><th>Topic name</th><th>Number of message</th><th>Last message</th></tr>';
	}

	function createNewAvatar($user) {
		$file = 'images/users_avatar/default.jpg';
		$newfile = 'images/users_avatar/'.$user.'.png';
		if (!copy($file, $newfile)) {
    		echo "<p class='failed'>Failed to create new user avatar\n</p>";
		}
		else {
			return $newfile;
		}
	}

	function countRole($role) {
		$connBDD = DBConnection();
		$req = "SELECT count(id) AS count FROM users WHERE role=$role";
		$results = $connBDD->query($req);
		$fetch = $results->fetch();
		return $fetch['count'];
	}

	function displaySelect($muted,$role) {
		$connBDD = DBConnection();
		$req = 'SELECT id,name,role FROM users WHERE isMuted='.$muted.' AND role = '.$role;
		
		$results = $connBDD->query($req);
		$tab = $results->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($tab)) {
			?>
			<option value="">-- Select a member --</option>
			<?php
			foreach ($tab as $key => $value) {
				echo '<option value="'.$tab[$key]['id'].'">'.$tab[$key]['id'].' | '.$tab[$key]['name'].'</option>';
			}
		}
		else {
			echo '<option value="" disabled selected>No member to select...</option>';
		}
		
	}

	function displaySelectRole($role) {
		$connBDD = DBConnection();
		$request = $connBDD->query('SELECT id,name FROM users WHERE role = '.$role);
		$tab = $request->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($tab)) {
			?>
			<option value="">-- Select a member --</option>
			<?php
			foreach ($tab as $key => $value) {
				echo '<option value="'.$tab[$key]['id'].'">'.$tab[$key]['id'].' | '.$tab[$key]['name'].'</option>';
			}
		}
		else {
			echo '<option value="">No result...</option>';
		}
	}

	function age($birthdate) {
		$currentDate = new DateTime();
		$birthdate = DateTime::createFromFormat('j/m/Y',$birthdate);
		$date_results = date_diff($currentDate,$birthdate);
		$new_age = intval($date_results->format("%y"));
		return $new_age;
	}

	function birthday() {
		$connBDD = DBConnection();
		
		$results = $connBDD->query('SELECT name,role,birth_date,profile_pic FROM users WHERE role > 0 AND birth_date LIKE "'.date('d/m').'%"');

		$tab = $results->fetchAll();
		
		if(count($tab) > 0) {
			switch (count($tab)) {
				case 1:
					echo $tab[0]['name'];
					break;
				
				case 2:
					echo $tab[0]['name'].' & '.$tab[1]['name'];
					break;

				default:
					for($i = 0 ; $i <= count($tab) - 1 ; $i++) {
						if($i == count($tab) -1) {
							echo ' & '.$tab[$i]['name'];
						}
						else if($i == count($tab) - 2) {
							echo $tab[$i]['name'];
						}
						else {
							echo $tab[$i]['name'].', ';
						}
					}
			}
			echo "</p>";
			foreach ($tab as $key => $value) {
				echo '<p><img class="birthday_pp" src="'.$tab[$key]['profile_pic'].'" alt="'.$tab[$key]['profile_pic'].'"> '.$tab[$key]['name'].' is now ' .age($tab[$key]['birth_date']);
			}
		}
		else {
			echo '<span class="no_birthday">nobody</span></p>';
		}
	}

	function lastRegistered() {
		$connBDD = DBConnection();

		$results = $connBDD->query('SELECT name,profile_pic,registration_date FROM users WHERE role > 0 ORDER BY id DESC LIMIT 1');

		$info = $results->fetch();

		return $info;
	}

	function lastMessage() {
		$connBDD = DBConnection();
		$req = 'SELECT users.name,users.profile_pic,topics.id,topics.theme,topics.topic_name,messages.publish_date,messages.content FROM messages JOIN users ON messages.id_user = users.id JOIN topics ON messages.id_topic = topics.id ORDER BY messages.id DESC LIMIT 1';
		$results = $connBDD->query($req);

		$info = $results->fetch();
		
		if(empty($info)) {
			return $info;
		}
		else {
			if(strlen($info["content"]) > 50) {
				$info["content"] = substr($info["content"],0,50).' <a href="index.php?page=topic&value='.$info['id'].'">Read More...</a>' ;
			}
			return $info;
		}	
	}

	function getUserInfoProfile($user) {
		$connBDD = DBConnection();
		
		$req = $connBDD->prepare('SELECT id,name,password,role,profile_pic,country,birth_date,biography,signature,registration_date FROM users WHERE name = :user ');
		$req->execute(array(
			'user' => $user
		));
		$info = $req->fetch();
		$req->closeCursor();
		if(empty($info)) {
			return 404;
		}
		else {
			$info['role'] = roleStr($info['role']);
		
			if(empty($info['registration_date']))	
				$info['registration_date'] = "the beginning";

			if($_SESSION && $_SESSION['userID'] == $info['id']) {
				if(empty($info['biography']))
					$info['biography'] = "<span class='emptyDetail'>Introduce yourself in some words...</span>";

				if(empty($info['signature']))
					$info['signature'] = "<span class='emptyDetail'>Write a message that will appear at the end of your messages</span>";
			}
			else {
				if(empty($info['biography']))
					$info['biography'] = "<span class='emptyDetail'>No biography</span>";

				if(empty($info['signature']))
					$info['signature'] = "<span class='emptyDetail'>No signature</span>";
			}

			if(empty($info['country']))
				$info['country'] = "<span class='emptyDetail'>Undefined</span>";

			if(empty($info['birth_date']))
				$info['birth_date'] = "<span class='emptyDetail'>Undefined</span>";
				
			return $info;
		}		
	}
?>