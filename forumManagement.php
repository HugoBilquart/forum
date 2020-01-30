<div class="col-md-12">
<?php
	if($_SESSION) {
		if ($_SESSION['role'] > 1) {
			echo '<h1 class="page_name">Forum management page</h1>';
		}
		else {
			echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
		}
	}
	else {
		echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
	}
?>
</div>
<div class="col-md-12 text-center">
<?php
	if($_POST) {
		if($_POST['action'] == '🗑') {
			$request_delete_messages = $connex_PDO->exec('UPDATE messages SET visible = 0 WHERE id_topic = '.$_POST['topicToDelete']);
			if($request_delete_messages) {
				echo '<p class="loginDone">Messages of topic n°'.$_POST['topicToDelete'].' are no longer visible on the forum</p>';
				$request_delete_topic = $connex_PDO->exec('UPDATE topics SET visible = 0 WHERE id = '.$_POST['topicToDelete']);
				if($request_delete_topic) {
					echo '<p class="loginDone">Topic n°'.$_POST['topicToDelete'].' is no longer visible on the forum</p>';
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
			$request_complete_topic = $connex_PDO->exec('UPDATE topics SET complete = 1 WHERE id = '.$_POST['topicToComplete']);
			if($request_complete_topic) {
				echo '<p class="loginDone">Topic n°'.$_POST['topicToComplete'].' is complete</p>';
			}
			else {
				echo '<p class="loginFailed">Failed to change topic n°'.$_POST['topicToComplete'].'\'s state</p>';
			}
		}
		else if($_POST['action'] == '✘') {
			$results = $connex_PDO->exec('UPDATE messages SET visible = 0 WHERE id = '.$_POST['messageToDelete']);
			if($results) {
				echo '<p class="success">Message n°'.$_POST['messageToDelete'].' is no longer visible in selected topic</p>';
			}
			else {
				echo '<p class="failed">Failed to delete message</p>';
			}
		}
	}
	$request_topic_removable = $connBDD->query('SELECT topics.id,topic_name,topic_owner,creation_date,role FROM topics INNER JOIN users ON topics.id = users.id WHERE users.role < 3');
	$tab_removable = $request_topic_removable->fetchAll(PDO::FETCH_ASSOC);

	$request_topic_not_complete = $connBDD->query('SELECT topics.id,topic_name,topic_owner,creation_date,role FROM topics INNER JOIN users ON topics.id = users.id WHERE users.role < 3 AND complete = 0');
	$tab_not_complete = $request_topic_not_complete->fetchAll(PDO::FETCH_ASSOC);
?>
</div>

<div class="col-md-12">
	<form method="POST">
		<div class="row">
			<div class="col-md-5 form-part text-center">
				<p><label for="topicToDelete">Delete topic</label></p>
				<select id="topicToDelete" name="topicToDelete" class="form-control">
					<option value="">Select a topic</option>
					<?php
						foreach ($tab_removable as $key => $values) {
							echo '<option value="'.$tab_removable[$key]['id'].'">#'.$tab_removable[$key]['id'].' - '.$tab_removable[$key]['topic_name'].' | By '.$tab_removable[$key]['topic_owner'].' | '.date('d/m/Y',strtotime($tab_removable[$key]['creation_date'])).'</option>';
						}
					?>
				</select>
				<br/>
				<input type="submit" class="action_button" name="action" value="🗑" title="Delete selected topic">
			</div>

			<div class="col-md-2"></div>

			<div class="col-md-5 form-part text-center">
				<p><label for="topicToDelete">Turn a topic on <b>completed</b> state</label></p>
				<select name="topicToComplete" class="form-control">
					<option value="">Select a topic</option>
					<?php
						foreach ($tab_not_complete as $key => $values) {
							echo '<option value="'.$tab_not_complete[$key]['id'].'">N°'.$tab_not_complete[$key]['id'].' - '.$tab_not_complete[$key]['topic_name'].' | By '.$tab_not_complete[$key]['topic_owner'].' | '.date('d/m/Y',strtotime($tab_removable[$key]['creation_date'])).'</option>';
						}
					?>
				</select>
				<br/>
				<input type="submit" class="action_button" name="action" value="✔" title="Turn selected topic on 'completed' state">
			</div>

			<div class="col-md-12"><br/></div>

			<div class="col-md-12 form-part text-center">
				<p><label>Delete a message from a topic</label></p>
				<select id="topicToEdit" name="topicToEdit" class="form-control">
					<option value="">Select a topic</option>
					<?php
						foreach ($tab_removable as $key => $values) {
							echo '<option value="'.$tab_removable[$key]['id'].'|'.$tab_removable[$key]['topic_name'].'">#'.$tab_removable[$key]['id'].' - '.$tab_removable[$key]['topic_name'].' | By '.$tab_removable[$key]['topic_owner'].' | '.date('d/m/Y',strtotime($tab_removable[$key]['creation_date'])).'</option>';
						}
					?>
				</select>
				<br/>
				<input type="button" id="searchMessages" class="action_button" name="action" value="🔍" title="Get messages of selected topic">
				<hr>
				<div class="row" id="selectedTopic">
					<p id="selectedTopicName"></p>
					<div class="col-sm-12" id="selectedTopicMessages">
						<p><label>Select message to delete</label></p>
						<select id="selectedTopicSelect" name="messageToDelete"></select>
						<input type="submit" class="action_button" name="action" value="✘" title="Delete message of selected topic">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script>

	

	$('#searchMessages').click(function(){
		$topicToEdit = $('#topicToEdit').val().split('|');
		$('#selectedTopicSelect').html('');
		
		$.ajax({
			url: 'script/getTopicMessages.php',
			method: "GET",
			data: {
				topic : $topicToEdit[0]
			},
			success:function(data){
				$msg = data;
				console.log($msg);
				$('#selectedTopic').css('display','block');
				$('#selectedTopicName').html($topicToEdit[1]);

				for(var i = 0; i < $msg.length; i++) {
					var content = $msg[i]['content'].substring(0,20);
					document.getElementById('selectedTopicSelect').innerHTML += '<option value="'+$msg[i]['message']+'">' + $msg[i]['name'] + ' : ' + content + '</option>';
				}
				$('#selectedTopic').css('display','block');
			},
			dataType:"json"
		});
	});

</script>

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

#selectedTopic {
	display: none;
}

.action_button {
	font-weight: bold;
	font-size: 20px;
}


</style>