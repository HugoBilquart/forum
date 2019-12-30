<?php
	if($_POST) {
		$req_check = 'SELECT * FROM messages WHERE id_user = '.$_SESSION['userID'].' AND id_topic = '.$_GET['value'].' AND content = "'.$_POST['newMessageArea'].'"';
		$check = $connBDD->query($req_check);
		if($check) {
			echo "<p class='loginFailed'>This message is already post in the topic (don't flood topics)</p>";
		}
		else {
			$req_new_message = 'INSERT INTO messages(id_topic,id_user,publish_date,content) VALUES ('.$_GET['value'].','.$_SESSION['userID'].',"'.date('d/m/Y H:i:s').'","'.$_POST['newMessageArea'].'")';
			$publish = $connBDD->exec($req_new_message);
			
			if($publish) {
				echo "<p class='loginDone'>Message successfully published !</p>";
				$connBDD->exec('UPDATE messages SET nb_message = nb_message + 1 WHERE id = '.$_GET['value']);
			}
			else {
				echo "\nPDO::errorInfo():\n";
				print_r($connBDD->errorInfo());
				echo "<p class='loginFailed'>Message wasn't add</p>";
			}
		}


	}

	$req = 'SELECT id,theme,topic_name,topic_owner,nb_message,options,complete FROM topics WHERE id="'.$_GET['value'].'"';
	$results = $connBDD->query($req);
	$line = $results->fetch();
	$topic_name = $line["topic_name"];

	echo '<p><a href="index.php?page=home"><img src="images/icons/home.png" alt="home_button" id="home_button"></a>
		 > <a href="index.php?page=forum" class="userLink">Forum</a> 
		 > <a href="index.php?page=forum&category='.$line["theme"].'" class="userLink">'.$line["theme"].'</a> 
		 > '.$topic_name.' [ n° '.$line["id"].' ]</p>';

	$req_msg = 'SELECT users.id,name,role,profile_pic,signature,publish_date,content FROM users INNER JOIN messages ON users.id = messages.id_user WHERE messages.id_topic ='.$_GET["value"];
	$msg = $connBDD->query($req_msg);
	$msg_data = $msg->fetchAll(PDO::FETCH_ASSOC);


	$req_count = 'SELECT count(id) AS count FROM messages WHERE id_topic = '.$_GET["value"];
	$result = $connBDD->query($req_count);
	$count = $result->fetch();
	$count = $count['count'];

	for($i=0;$i<$count;$i++) {
		$topic_user = $msg_data[$i]['name'];
		$topic_role = roleINT_CHAR($msg_data[$i]['role']);
		$topic_pp = $msg_data[$i]['profile_pic'];
		$topic_signature = $msg_data[$i]['signature'];
		$topic_publishDate = $msg_data[$i]['publish_date'];
		$topic_content = $msg_data[$i]['content'];

		echo '	<table class="topic_msg">
					<tr class="topic_userData">
				    	<td class="topic_userData"><a class="userLink" href="index.php?page=profile&user='.$topic_user.'" title="Profile of '.$topic_user.'"><img src="'.$topic_pp.'" alt="'.$topic_pp.'" class="topic_pp"></a></td>
				    	<td rowspan="2" colspan="2" class="topic_content">'.$topic_content.'</td>
				  	</tr>
				  	<tr>
				    	<td rowspan="2" class="topic_userData"><a class="userLink" href="index.php?page=profile&user='.$topic_user.'" title="Profile of '.$topic_user.'"><p>'.$topic_user.'</p></a><p>'.$topic_role.'</p><p>'.$topic_publishDate.'</p></td>
				  	</tr>
				  	<tr>
				    	<td colspan="2" class="topic_signature">'.$topic_signature.'</td>
				  	</tr>
				</table>';
	}

		if($_SESSION) {
			if($line['complete'] == 1) {
				echo '<table id="topic_msg_denied"><tr><th>You can\'t post on a completed topic</th></tr></table>';
			}
			else if($_SESSION['isMuted'] == 1) {
				echo '<table id="topic_msg_denied"><tr><th>Your are not allowed to publish on the forum</th></tr></table>';
			}
			else if($line['options'] == 'readOnly') {
				if($_SESSION['userName'] == $line['topic_owner']) {
					formNewMessage($topic_name);
				}
				else {
					echo '<table id="topic_msg_denied"><tr><th>This topic is editable only by his owner</th></tr></table>';
				}
			}
			else if($line['options'] == 'staffOnly') {
				if($_SESSION['role'] == 'admin' OR $_SESSION['role'] == 'moderator') {
					formNewMessage($topic_name);
				}
				else {
					echo '<table id="topic_msg_denied"><tr><th>Only staff is allowed to publish on this topic</th></tr></table>';
				}	
			}
			else {
				formNewMessage($topic_name);
			}
		}
		else {
			echo '<table id="topic_msg_denied"><tr><th>You must be authenticated to publish in topics</th></tr></table>';
		}
?>