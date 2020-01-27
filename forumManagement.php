<?php
	if($_SESSION) {
		if ($_SESSION['role'] > 1) {
			echo '<p class="page_name">Forum management page</p>';
			$req_topic_removable = 'SELECT topics.id,topic_name,topic_owner,role,nb_message FROM topics INNER JOIN users ON topics.id = users.id WHERE users.role < 3';
			$results_topic_removable = $connBDD->query($req_topic_removable);
			$tab_removable = $results_topic_removable->fetchAll(PDO::FETCH_ASSOC);

			$req_topic_not_complete = 'SELECT topics.id,topic_name,topic_owner,role,nb_message FROM topics INNER JOIN users ON topics.id = users.id WHERE users.role < 3 AND complete = 0';
			$results_topic_not_complete = $connBDD->query($req_topic_not_complete);
			$tab_not_complete = $results_topic_not_complete->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
		}
	}
	else {
		echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
	}

	if($_POST) {
		if($_POST['action'] == '🗑') {
			echo 'Topic n° '.$_POST['topicToDelete'];
			$req_delete_messages = 'DELETE FROM messages WHERE id_topic = '.$_POST['topicToDelete'];
			$result_delete_messages = $connBDD->exec($req_delete_messages);
			if($result_delete_messages) {
				echo '<p class="loginDone">Messages of topic n°'.$_POST['topicToDelete'].' deleted</p>';
				$req_delete_topic = 'DELETE FROM topics WHERE id = '.$_POST['topicToDelete'];
				$result_delete_topic = $connBDD->exec($req_delete_topic);
				if($result_delete_topic) {
					echo '<p class="loginDone">Topic n°'.$_POST['topicToDelete'].' deleted</p>';
				}
				else {
					echo '<p class="loginFailed">Failed to delete topic</p>';
				}
			}
			else {
				echo '<p class="loginFailed">Failed to delete messages</p>';
			}
		}
		else if($_POST['action'] == '✔') {
			echo 'Topic n° '.$_POST['topicToComplete'];
			$req_complete_topic = 'UPDATE topics SET complete = 1 WHERE id = '.$_POST['topicToComplete'];
			$result_complete_topic = $connBDD->exec($req_complete_topic);
			if($result_complete_topic) {
				echo '<p class="loginDone">Topic n°'.$_POST['topicToComplete'].' is complete</p>';
			}
			else {
				echo '<p class="loginFailed">Failed to change topic n°'.$_POST['topicToComplete'].'\'s state</p>';
			}
		}
		else if($_POST['action'] == '❌') {
			echo $_POST['messageToDelete'];
			$req = 'DELETE FROM messages WHERE id ='.$_POST['messageToDelete'];
			$results = $connBDD->exec($req);
			if($results) {
				echo 'Message n°'.$_POST['messageToDelete'].' deleted';
			}
			else {
				echo 'Failed to delete message';
			}
		}
	}
?>

<form method="POST">
	<table id="forumManagement_table">
		<tr class="forumManagement_td">
			<td class="forumManagement_td">
				<table class="forumManagement_underTable">
					<tr>
						<th colspan="2"><p>Delete topic</p></th>
					</tr>
					<tr>
						<td>
							<select name="topicToDelete">
								<option value="">Select a topic</option>
								<?php
									foreach ($tab_removable as $key => $values) {
										echo '<option value="'.$tab_removable[$key]['id'].'">N°'.$tab_removable[$key]['id'].' - '.$tab_removable[$key]['topic_name'].' | By '.$tab_removable[$key]['topic_owner'].' | '.$tab_removable[$key]['nb_message'].' message(s)</option>';
									}
								?>
							</select>
						</td>
						<td>
							<input type="submit" class="action_button" name="action" value="🗑" title="Delete selected topic">
						</td>
					</tr>
				</table>
			</td>
			<td class="forumManagement_td">
				<table class="forumManagement_underTable">
					<tr>
						<th colspan="2"><p>Turn a topic on 'completed' state</p></th>
					</tr>
					<tr>
						<td>
							<select name="topicToComplete">
								<option value="">Select a topic</option>
								<?php
									foreach ($tab_not_complete as $key => $values) {
										echo '<option value="'.$tab_not_complete[$key]['id'].'">N°'.$tab_not_complete[$key]['id'].' - '.$tab_not_complete[$key]['topic_name'].' | By '.$tab_not_complete[$key]['topic_owner'].' | '.$tab_not_complete[$key]['nb_message'].' message(s)</option>';
									}
								?>
							</select>
						</td>
						<td>
							<input type="submit" class="action_button" name="action" value="✔" title="Turn selected topic on 'completed' state">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="forumManagement_td" colspan="2">
				<table class="forumManagement_underTable">
					<tr>
						<th colspan="2"><p>Delete a message from a topic</p></th>
					</tr>
					<tr>
						<td>
							<select name="topicToEdit">
								<option value="">Select a topic</option>
								<?php
									foreach ($tab_removable as $key => $values) {
										echo '<option value="'.$tab_removable[$key]['id'].'">N°'.$tab_removable[$key]['id'].' - '.$tab_removable[$key]['topic_name'].' | By '.$tab_removable[$key]['topic_owner'].' | '.$tab_removable[$key]['nb_message'].' message(s)</option>';
									}
								?>
							</select>
						</td>
						<td>
							<input type="submit" class="action_button" name="action" value="🔍" title="Get messages of selected topic">
						</td>
					</tr>
				</table>
				<table class="forumManagement_underTable">
					<tr>
						<th colspan="2"><p>Select message to delete</p></th>
					</tr>
					<tr>
						<td>
							<select name="messageToDelete">
								<option value="">Select a message</option>
								<?php
									if($_POST) {
										if($_POST['action'] == '🔍') {
											echo $_POST['topicToEdit'];
											$req_messages = 'SELECT id,id_user,publish_date FROM messages WHERE id_topic = '.$_POST['topicToEdit'];
											$results_messages = $connBDD->query($req_messages);
											$tab_messages = $results_messages->fetchAll(PDO::FETCH_ASSOC);
											foreach ($tab_messages as $key => $value) {
												echo '<option value="'.$tab_messages[$key]['id'].'">N°'.$tab_messages[$key]['id'].' | By '.$tab_messages[$key]['id_user'].' | '.$tab_messages[$key]['publish_date'].'</option>';
											}
										}
									}
								?>
							</select>
						</td>
						<td>
							<input type="submit" class="action_button" name="action" value="❌" title="Delete message of selected topic">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<style type="text/css">
	
table#forumManagement_table {
	margin: auto;
	text-align: center;
	width: 80%;

}

table.forumManagement_underTable {
	margin: auto;
	text-align: center;
	width: 40%;
}

td.forumManagement_td, tr.forumManagement_td{
	margin: 10%;
	border: solid white;
	padding: 5px;
	text-align: center;
} 

.action_button {
	font-weight: bold;
	font-size: 20px;
}


</style>