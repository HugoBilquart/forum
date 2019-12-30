<?php
	if($_SESSION AND $_SESSION['isMuted'] == 0) {
		echo '<p><center>Creation of a new topic</center></p>';
	}
	else {
		echo 'echo "<script type="text/javascript"> window.location = "index.php?page=forum"; </script>";';
	}
?>
<form method="POST" id="newTopicArea">
	<fieldset>
		<legend>New Topic</legend>
		<table id="newTopic_table">
			<tr>
				<th>
					<p>Topic name : </p> 
				</th>
				<td>
					<input type="text" name="newTopic_name" size="50" required></input>
				</td>
			</tr>

			<tr>
				<th>
					<p>Topic theme : </p> 
				</th>
				<td>
					<select name="newTopic_theme" required>
						<option value="" selected>---Undefined---</option>
						<?php 
							if($_SESSION['role'] == 'admin') {
								echo '<option value="rules">Rules</option>';
								echo '<option value="changelogs">Changelogs</option>';
							}
							if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator') {
								echo '<option value="announce">Announces</option>';
							}
						?>
						<option value="network">Network</option>
						<option value="web">Web</option>
						<option value="software">Software</option>
					</select>
				</td>
			</tr>
			<?php 
				if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'moderator') {
					echo '<tr><th><p>Topic option : </p></th><td><select name="newTopic_options">';
					if($_SESSION['role'] == 'admin') {
						echo '<option value="" selected>---Undefined---</option>';
						echo '<option value="readOnly">Read Only</option>';
						echo '<option value="staffOnly">Editable only by the staff</option>';
					}
					else {
						echo '<option value="" selected>---Undefined---</option>';
 						echo '<option value="staffOnly">Editable only by the staff</option>';
					}
					echo '</select></td></tr>';
				}
			?>
			<tr>
				<td colspan="2">
					<?php
						$topicName = '';
						formNewMessage($topicName);
					?>
				</td>
			</tr>
		</table>
	</fieldset>
</form>

<?php
	if($_POST) {
		if($_POST['submit'] == 'Publish topic') {
			$connBDD = DBConnection();
			$creation_date = date('d/m/Y');
			$req_topic = 'INSERT INTO topics(topic_name,topic_owner,options,theme,nb_message,creation_date,complete) VALUES ("'.$_POST['newTopic_name'].'","'.$_SESSION['userName'].'","'.$_POST['newTopic_options'].'","'.$_POST['newTopic_theme'].'","1","'.$creation_date.'","0")';
			$check = $connBDD->exec($req_topic);
			if($check) {
				echo "<p class='loginDone'>Topic successfully published !</p>";
				$req_id_topic = 'SELECT id FROM topics WHERE topic_name = "'.$_POST['newTopic_name'].'" AND creation_date = "'.$creation_date.'"';
				$query = $connBDD->query($req_id_topic);
				$line = $query->fetch();
				$req_new_message = 'INSERT INTO messages(id_topic,id_user,publish_date,content) VALUES ("'.$line['id'].'","'.$_SESSION['userID'].'","'.date('d/m/Y H:i:s').'","'.$_POST['newMessageArea'].'")';
				$publish = $connBDD->exec($req_new_message);
				if($publish) {
					echo "<p class='loginDone'>Message add to the topic !</p>";
				}
				else {
					echo "<p class='loginFailed'>Message hasn't been add</p>";
				}			
			}
			else {
				echo "<p class='loginFailed'>Creation of topic FAILED</p>";
			}
		}
	}
?>

<style type="text/css">
	
	form#newTopicArea {
		margin: 	auto;
		width: 		90%;
	}

	table.newTopic_table, tr,th,td {
		margin: 	auto;
	}

	textarea#newMessageArea {
		resize: none;
		text-indent: 0px;
		width: 90%; 
	}

</style>