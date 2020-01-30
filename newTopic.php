<div class="col-md-12">
	<?php
		if($_SESSION AND $_SESSION['isMuted'] == 0) {
			?>
			<h1 class="page_name">Creation of a new topic</h1>
			<?php
		}
		else {
			echo 'echo "<script type="text/javascript"> window.location = "index.php?page=forum"; </script>";';
		}

		if($_POST) {
			if($_POST['submit'] == 'Publish topic') {
				$creation_date = date('Y-m-d');
				if(empty($_POST['newTopic_options'])) {
					$_POST['newTopic_options'] == '';
				}
				$req_topic = 'INSERT INTO topics(topic_name,topic_owner,options,theme,creation_date,complete) VALUES ("'.$_POST['newTopic_name'].'","'.$_SESSION['userName'].'","'.$_POST['newTopic_options'].'","'.$_POST['newTopic_theme'].'","'.$creation_date.'","0")';
				$check = $connex_PDO->exec($req_topic);
				if($check) {
					echo "<p class='success'>Topic successfully published !</p>";
					$req_id_topic = 'SELECT id FROM topics WHERE topic_name = "'.$_POST['newTopic_name'].'" AND creation_date = "'.$creation_date.'"';
					$query = $connBDD->query($req_id_topic);
					$line = $query->fetch();
					$req_new_message = 'INSERT INTO messages(id_topic,id_user,publish_date,content) VALUES ("'.$line['id'].'","'.$_SESSION['userID'].'","'.date('Y-m-d H:i:s').'","'.$_POST['newMessageArea'].'")';
					$publish = $connBDD->exec($req_new_message);
					if($publish) {
						echo "<p class='success'>Message add to the topic !</p>";
					}
					else {
						echo "<p class='failed'>Message hasn't been add</p>";
					}			
				}
				else {
					echo "<p class='failed'>Creation of topic FAILED</p>";
				}
			}
		}
	?>
</div>
<div class="col-md-12" id="newTopic">
	<form method="POST">
		<div class="row">
			<div class="form-group col-md-10 form-part" id="newTopicInfo">
				<p class="form-part-title">New topic informations</p>
				<hr>

				<p>
					<label for="newTopicName">Topic name</label>
					<input type="text" class="form-control" name="newTopic_name" id="newTopicName" required>
				</p>
				<p>
					<label for="newTopicTheme">Topic theme</label>
					<select class="form-control" name="newTopic_theme" id="newTopicTheme" required>
						<option value="" selected>-- Select --</option>
						<?php 
							if($_SESSION['role'] == 3) {
								?>
									<option value="rules">Rules</option>
									<option value="changelogs">Changelogs</option>
								<?php
							}
							if($_SESSION['role'] > 1) {
								?>
								<option value="announce">Announces</option>
								<?php
							}
						?>
						<option value="network">Network</option>
						<option value="web">Web</option>
						<option value="software">Software</option>
					</select>
				</p>
				<?php
				if($_SESSION['role'] > 1) {
					?>
					<p>
						<label for="newTopicOption">Topic option</label>
						<select class="form-control" name="newTopic_options" id="newTopicOption">
							<option value="" selected>---Undefined---</option>
							<?php
								if($_SESSION['role'] == 3) {
									?>
									<option value="readOnly">Read Only</option>
									<?php
								}
								?>
								<option value="staffOnly">Editable only by the staff</option>
						</select>
					</p>
				<?php
				}
				?>
			</div>

			<div class="form-group col-md-10 form-part text-center" id="newTopicFirstMsg">
				<p class="form-part-title">First message</p>
				<hr>
				<p>
					<label for="newMessageArea">Type the first message here</label>
					<textarea class="form-control" id="newMessageArea" name="newMessageArea" rows="10" onchange=""></textarea>
				</p>
			</div>

			<div class="form-group col-md-10 text-center" id="newTopicSubmit">
				<input type="submit" class="btn btn-primary form-control" name="submit" value="Publish topic">
			</div>
		</div>
	</form>
</div>

<?php
	
?>

<style type="text/css">
	
	

</style>