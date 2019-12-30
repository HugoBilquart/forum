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

	function fetchTopicMsg() {
		$topic_user = $msg_data['name'];
		$topic_role = roleINT_CHAR($msg_data['role']);
		$topic_pp = $msg_data['profile_pic'];
		$topic_signature = $msg_data['signature'];
		$topic_publishDate = $msg_data['publish_date'];
		$topic_available = $msg_data['available'];
		$topic_content = $msg_data['content'];

		echo '	<table id="topic_msg">
					<tr id="topic_userData">
				    	<td id="topic_userData"><a class="userLink" href="index.php?page=profile&user='.$topic_user.'" title="Profile of '.$topic_user.'"><img src="'.$topic_pp.'" class="topic_pp"></a></td>
				    	<td rowspan="2" colspan="2" id="topic_content">'.$topic_content.'</td>
				  	</tr>
				  	<tr>
				    	<td rowspan="2" id="topic_userData"><a class="userLink" href="index.php?page=profile&user='.$topic_user.'" title="Profile of '.$topic_user.'"><p>'.$topic_user.'</p></a><p>'.$topic_role.'</p><p>'.$topic_publishDate.'</p></td>
				  	</tr>
				  	<tr>
				    	<td colspan="2" id="topic_signature">'.$topic_signature.'</td>
				  	</tr>
				</table>';
	}

	function createNewAvatar($user) {
		$file = 'images/users_avatar/default.jpg';
		$newfile = 'images/users_avatar/'.$user.'.png';
		if (!copy($file, $newfile)) {
    		echo "Failed to create new user avatar\n";
		}
		else {
			return $newfile;
		}
	}

	function formNewMessage($topic_name) {
		echo '	<form method="POST" id="newMessage_form">
					<fieldset>';
					if(!empty($topic_name)) {
						echo '<legend id="newMessage_title">RE:'.$topic_name.'</legend>';
					}
					else {
						echo '<legend id="newMessage_title">First message</legend>';
					}
		echo	'<table><tr><td><textarea id="newMessageArea" name="newMessageArea" cols="70" rows="20" onchange="remainingCharacters()"></textarea></td></tr></table></fieldset>';
		if($_GET['page'] == 'newTopic') {
			echo '<input type="submit"  name="submit" value="Publish topic">';
		}
		else {
			echo '<input type="submit"  name="submit" value="Post message">';
		}
		echo '</form>';
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
				echo '<table style="margin:auto;">';
				$x = 0;	
				for($i=0;$i<$nbRole;$i++) {
					$id = $tab[$i]['id'];
					$name = $tab[$i]['name'];
					$role = roleINT_CHAR($tab[$i]['role']);
					$profile_pic = $tab[$i]['profile_pic'];
					if($i%3 == 0) {
						echo '<tr>';
					}
						echo "<td>";
						echo '<table class="staffManagement_user"><tr><td rowspan="2" class="staffManagement_pp_area"><a href="index.php?page=profile&user='.$name.'" title="Profile of '.$name.'"><img class="staffManagement_pp" src="'.$profile_pic.'" alt="'.$profile_pic.'"></a></td><td>'.$name.' [ N° '.$id.' ]</td>';
						

						echo'</tr><tr><td>'.$role.'</td></tr></table>';
						echo "</td>";
						$x++;
					if($i > 3 && $x == 3) {
						$x = 0;
						echo '</tr>';
					}
				}
				echo '</table>';
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
		$req = 'SELECT name,role,birth_date,profile_pic FROM users WHERE role > 0 AND birth_date LIKE "'.date('d/n').'%"';
		$req_count = 'SELECT count(id) AS count FROM users WHERE role > 0 AND birth_date LIKE "'.date('d/n').'%"';
		
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
				echo '<p><img class="birthday_pp" src="'.$tab[$key]['profile_pic'].'"> '.$tab[$key]['name'].' is now ' .age($tab[$key]['birth_date']);
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

		$tab = $results->fetch();

		echo '	<table class="lastRegistered_table">
					<tr>
						<td rowspan="2" class="homepage_pp_area">
							<a href="index.php?page=profile&user='.$tab['name'].'" title="Profile of '.$tab['name'].'">
								<img src="'.$tab['profile_pic'].'" class="lastRegistered_pp" alt="'.$info['profile_pic'].'">
							</a>
						</td>
						<td>
							<a href="index.php?page=profile&user='.$tab['name'].'" class="memberlist_namelink" title="Profile of '.$tab['name'].'">
								'.$tab['name'].'
							</a>
						</td>
					</tr>
					<tr>
						<td>
							Registered since '.$tab['registration_date'].'
						</td>
					</tr>
				</table>';
	}

	function lastMessage() {
		$connBDD = DBConnection();
		$req = 'SELECT users.name,users.profile_pic,topics.id,topics.theme,topics.topic_name,messages.publish_date,messages.content FROM messages JOIN users ON messages.id_user = users.id JOIN topics ON messages.id_topic = topics.id ORDER BY messages.id DESC LIMIT 1';
		$results = $connBDD->query($req);

		return $results->fetch();
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