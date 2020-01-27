<?php
	function DBConnection() {
		$dbconn = new PDO('sqlite:db/db_project.sqlite');
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

	function topic_folder($name) {
		$connBDD = DBConnection();
		$req = 'SELECT creation_date, complete FROM topics WHERE topic_name="'.$name.'"';
		$results = $connBDD->query($req);
		$line = $results->fetch();

		$creationDate = $line['creation_date'];
		$creationDate = date_create($creationDate);
		$currentDate = date_create(date('d-m-Y'));
		$date_results = date_diff($creationDate,$currentDate);
		$date_difference = intval($date_results->format("%a"));

		if($line['complete'] == '1') $topicPic = "images/folders/complete.png";
		
		else if($date_difference < 7) $topicPic = "images/folders/new.png";
		
		else $topicPic = "images/folders/default.png";
		
		return $topicPic;
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

	function fetchMember($role,$page,$task) {
		$connBDD = DBConnection();
		if($task == 'display') {
			if($page == "staffManagement") {
				$req = "SELECT id,name,role,profile_pic FROM users WHERE role=$role";
				$results = $connBDD->query($req);
				$tab = $results->fetchAll(PDO::FETCH_ASSOC);
				$nbRole = countRole($role);
				//echo '<table style="margin:auto;">';
				$x = 0;	
				for($i=0;$i<$nbRole;$i++) {
					$id = $tab[$i]['id'];
					$name = $tab[$i]['name'];
					$role = roleINT_CHAR($tab[$i]['role']);
					$profile_pic = $tab[$i]['profile_pic'];
					?>
						<div class="col-md-4 staffManagement_table">
							<div class="row staffManagement_user">
								<div class="col-sm-6 staffManagement_pp_area">
									<a href="index.php?page=profile&user=<?php echo $name; ?>" title="Profile of <?php echo $name; ?>">
										<img class="staffManagement_pp" src="<?php echo $profile_pic; ?>" alt="<?php echo $profile_pic; ?>">
									</a>
								</div>
								<div class="col-sm-6">
									<ul class="list-group"> 
										<li class="list-group-item">
											<?php echo $name.' [ N° '.$id.' ]'; ?>
										</li>
										<li class="list-group-item">
											<?php echo $role; ?>
										</li>
									</ul>
								</div>
							</div>
						</div>
					<?php

					/*if($i%3 == 0) {
						echo '<tr>';
					}
						echo "<td>";
						echo '
						<table class="staffManagement_user">
							<tr>
								<td rowspan="2" class="staffManagement_pp_area">
									<a href="index.php?page=profile&user='.$name.'" title="Profile of '.$name.'">
										<img class="staffManagement_pp" src="'.$profile_pic.'" alt="'.$profile_pic.'">
									</a>
								</td>
								<td>
									'.$name.' [ N° '.$id.' ]
								</td>';
						

						echo'</tr><tr><td>'.$role.'</td></tr></table>';
						echo "</td>";
						$x++;
					if($i > 3 && $x == 3) {
						$x = 0;
						echo '</tr>';
					}*/
				}
				//echo '</table>';
			}
			else if($page == 'userManagement') {
				$req = "SELECT id,name,role,profile_pic,isMuted FROM users WHERE role=$role";
				$results = $connBDD->query($req);
				$tab = $results->fetchAll(PDO::FETCH_ASSOC);
				$nbRole = countRole($role);
				echo '<table style="margin:auto;">';
				$x = 0;	
				for($i=0;$i<$nbRole;$i++) {
					$id = $tab[$i]['id'];
					$name = $tab[$i]['name'];
					$role = roleINT_CHAR($tab[$i]['role']);
					$profile_pic = $tab[$i]['profile_pic'];
					$isMuted = $tab[$i]['isMuted'];
					if($i%3 == 0) {
						echo '<tr>';
					}
						echo "<td style='padding: 1em;'>";
						echo '<table class="userManagement_user" id="'.$id.'"><tr><td rowspan="2"><img class="userManagement_pp" src="'.$profile_pic.'" alt="'.$profile_pic.'"></td><td>'.$name.' [ N° '.$id.' ]</td>';

							if($isMuted == 0) {
								echo '<td class="buttonArea"><input type="text" name="'.$id.'" value="🔊" title="User '.$id.' isn\'t muted" class="changeRoleButtonUser loginDone" size="1"></td></tr>';
							}
							else {
								echo '<td class="buttonArea"><input type="text" name="'.$id.'" value="🔇" title="User '.$id.' is muted" class="changeRoleButtonUser loginFailed" size="1"></td></tr>';
							}
						

						

							if($role == 'Banned') {
								echo'<tr><td>'.$role.'</td><td class="buttonArea"><input type="text" name="'.$id.'" value="🔒" title="User is banned" class="changeRoleButtonUser loginFailed" size="1"></td></tr></table>';
							}
							else {
								echo'<tr><td>'.$role.'</td><td class="buttonArea"><input type="text" name="'.$id.'" value="✅" title="User isn\'t banned" class="changeRoleButtonUser loginDone" size="1"></td></tr></table>';
								
							}

						echo "</td>";
						$x++;
					if($i > 3 && $x == 3) {
						$x = 0;
						echo '</tr>';
					}
				}
				echo '</table>';

			}
		}
	}

	function displaySelect($muted,$role) {
		$connBDD = DBConnection();
		$req = 'SELECT id,name,role FROM users WHERE isMuted='.$muted.' AND role = '.$role;
		
		$results = $connBDD->query($req);
		if($results) {
			$tab = $results->fetchAll(PDO::FETCH_ASSOC);
			foreach ($tab as $key => $value) {
				echo '<option value="'.$tab[$key]['id'].'">'.$tab[$key]['id'].' | '.$tab[$key]['name'].'</option>';
			}
		}
		else {
			echo '<option value="">No result...</option>';
		}
		
	}

	function displaySelectRole($role) {
		$connBDD = DBConnection();
		$req = 'SELECT id,name FROM users WHERE role = '.$role;
		$results = $connBDD->query($req);
		if($results) {
			$tab = $results->fetchAll(PDO::FETCH_ASSOC);
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
		$req = 'SELECT name,role,birth_date,profile_pic FROM users WHERE role > 0 AND birth_date LIKE "'.date('d/m').'%"';
		$req_count = 'SELECT count(id) AS count FROM users WHERE role > 0 AND birth_date LIKE "'.date('d/m').'%"';
		
		$results = $connBDD->query($req);
		$res_count = $connBDD->query($req_count)->fetch();

		$tab = $results->fetchAll();
		

		if($res_count['count'] > 0) {
			switch ($res_count['count']) {
				case 1:
					echo $tab[0]['name'];
					break;
				
				case 2:
					echo $tab[0]['name'].' & '.$tab[1]['name'];
					break;

				default:
					for($i = 0 ; $i <= $res_count['count'] - 1 ; $i++) {
						if($i == $res_count['count'] -1) {
							echo ' & '.$tab[$i]['name'];
						}
						else if($i == $res_count['count'] - 2) {
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
		$req = 'SELECT name,profile_pic,registration_date FROM users ORDER BY id DESC LIMIT 1';

		$results = $connBDD->query($req);

		$info = $results->fetch();

		return $info;
	}

	function lastMessage() {
		$connBDD = DBConnection();
		$req = 'SELECT users.name,users.profile_pic,topics.id,topics.theme,topics.topic_name,messages.publish_date,messages.content FROM messages JOIN users ON messages.id_user = users.id JOIN topics ON messages.id_topic = topics.id ORDER BY messages.id DESC LIMIT 1';
		$results = $connBDD->query($req);

		$info = $results->fetch();

		if(strlen($info["content"]) > 50) {
			$info["content"] = substr($info["content"],0,50).' <a href="index.php?page=topic&value='.$info['id'].'">Read More...</a>' ;
		}

		return $info;
	}

	function getUserInfoProfile($user) {
		$connBDD = DBConnection();
		
			$req = $connBDD->prepare('SELECT id,name,password,role,profile_pic,country,birth_date,biography,signature,registration_date,video_link FROM users WHERE name = :user ');
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

				if($info['video_link'] == '')
					$info['video_link'] = 'https://www.youtube.com/embed/iGpuQ0ioPrM';

				return $info;
			}
			

			
	}



	function email($for) {
		$email_adress = "hugo.bilquart@gmail.com";
		$boundary = "-----=".md5(rand());

		$line_break = "\r\n";

		$header = "From: \"SYSTEM\"<system@it-solutions.com>".$line_break;

		$header.= "Reply-to: \"SYSTEM\" <system@it-solutions.com>".$line_break;

		$header.= "MIME-Version: 1.0".$line_break;

		$header.= "Content-Type: multipart/alternative;".$line_break." boundary=\"$boundary\"".$line_break;


		switch ($for) {
			case 'demote':

				$subject = "[IT Solutions] DEMOTATION NOTIFICATION";

				$txt = 
"Hello !

System has a message for you :

Bad news ! You've been demoted. You're now a normal member.

If you think this decision isn't normal or legit, please contact IT Solutions's administrator.

This email is automatically generated by System, do not reply.";

				$html =
"<html><head></head><body><b>Hello !</b><br/><br/>
System has a message for you :<br/><br/>
<b color='red'>Bad news !</b> You've been demoted. You're now a normal member.<br/>
If you think this decision isn't normal or legit, please contact IT Solutions's administrator.<br/>
<i>This email is automatically generated by System, do not reply</i>.

</body></html>";


				break;
		}

		$message = $line_break."--".$boundary.$line_break;

		//=====Ajout du message au format texte.

		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$line_break;

		$message.= "Content-Transfer-Encoding: 8bit".$line_break;

		$message.= $line_break.$txt.$line_break;

		//==========

		$message.= $line_break."--".$boundary.$line_break;

		//=====Ajout du message au format HTML

		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$line_break;

		$message.= "Content-Transfer-Encoding: 8bit".$line_break;

		$message.= $line_break.$html.$line_break;

		//==========

		$message.= $line_break."--".$boundary."--".$line_break;

		$message.= $line_break."--".$boundary."--".$line_break;

		mail($email_adress,$subject,$message,$header);

/*			
			case 'promote':
"Hello !

System has a message for you :

Good news ! You've been promoted. You're now a moderator.

You're mission, if you accept it, is to keep the forum safe, attractive and respectful.

This email is automatically generated by System, do not reply.";
				break;
*/
		
	}
?>