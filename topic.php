<?php
	if($_POST) {
		?>
		<div class="col-md-11 topic-message text-center">
		<?php
		$req_check = 'SELECT * FROM messages WHERE id_user = '.$_SESSION['userID'].' AND id_topic = '.$_GET['value'].' AND content = "'.$_POST['newMessageArea'].'"';
		$check = $connBDD->query($req_check)->fetch();

		if(!empty($check)) {
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
		?>
		</div>
		<?php
	}

	$req = 'SELECT id,theme,topic_name,topic_owner,nb_message,options,complete FROM topics WHERE id="'.$_GET['value'].'"';
	$results = $connBDD->query($req);
	$line = $results->fetch();
	$topic_name = $line["topic_name"];

	echo '<p><a href="index.php?page=home"><img src="images/icons/home.png" alt="home_button" id="home_button"></a>
		 > <a href="index.php?page=forum" class="userLink">Forum</a> 
		 > <a href="index.php?page=forum&category='.$line["theme"].'" class="userLink">'.$line["theme"].'</a> 
		 > '.$topic_name.' [ #'.$line["id"].' ]</p>';

	$req_msg = 'SELECT users.id,name,role,profile_pic,signature,publish_date,content,visible FROM users INNER JOIN messages ON users.id = messages.id_user WHERE messages.id_topic ='.$_GET["value"];
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
		$topic_visible = $msg_data[$i]['visible'];
		$regExpHref = '#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i';
		if(preg_match($regExpHref,$topic_signature)) {
			$topic_signature = '<a href="'.$topic_signature.'">'.$topic_signature.'</a>';
		}

		if($topic_visible == 1) {
		?>
			<div class="col-md-11 topic-message">
				<div class="row border">
					<div class="col-sm-2 name-area">
						<span><b><?php echo $topic_user; ?></b> - <span class="<?php echo strtolower($topic_role); ?>_role"><?php echo $topic_role; ?></span></span>
					</div>
					<div class="col-sm-10">
						<p><?php echo $topic_publishDate; ?></p>
					</div>
					<div class="col-sm-2 topic-pp-area">
						<a class="userLink" href="index.php?page=profile&user=<?php echo $topic_user; ?>" title="Profile of <?php echo $topic_user; ?>">
							<img src="<?php echo $topic_pp; ?>" alt="<?php echo $topic_pp; ?>" class="topic_pp">
						</a>
					</div>
					<div class="col-sm-10 topic-msg-content">
						<p><?php echo $topic_content; ?></p>
					</div>
					<div class="col-sm-2 empty"></div>
					<div class="col-sm-10 text-center align-middle signature-area">
						<hr>
						<span><?php echo $topic_signature; ?></span>
					</div>
				</div>
			</div>
			<?php
		}
		else {
		?>
			<div class="col-md-11 deleted-message border text-center">
				<br/>
				<p>Message deleted</p>
				<br/>
			</div>
		<?php
		}
	}
?>
	

			<div class="col-md-11 topic-message">
				<div class="row border">
				<?php
					if($_SESSION) {
						if($line['complete'] == 1 && $_SESSION['role'] != 3) {
							?>
							<div class="col-md-12 topic-message" id="topic_msg_denied">
								<p>You can\'t post on a completed topic</p>
							</div>
							<?php
						}
						else if($_SESSION['isMuted'] == 1) {
							?>
							<div class="col-md-12 topic-message" id="topic_msg_denied">
								<p>Your are not allowed to publish on the forum</p>
							</div>
							<?php
						}
						else if($line['options'] == 'readOnly' && $_SESSION['userName'] != $line['topic_owner']) {
							?>
							<div class="col-md-12 topic-message" id="topic_msg_denied">
								<p>This topic is editable only by his owner</p>
							</div>
							<?php
						}
						else if($line['options'] == 'staffOnly' && $_SESSION['role'] == 1) {
							?>
							<div class="col-md-12 topic-message" id="topic_msg_denied">
								<p>Only staff is allowed to publish on this topic</p>
							</div>
							<?php
						}
						else {
							?>
							<div class="form-group col-md-12 form-part text-center topic-message">
								<form method="POST" id="newMsg">
									<p class="form-part-title">RE : <?php echo $topic_name; ?></p>
									<hr>
									<p>
										<label for="newMessageArea">Type your message here</label>
										<textarea class="form-control" id="newMessageArea" name="newMessageArea" rows="10" onchange=""></textarea>
									</p>

									<input type="submit" class="btn btn-primary form-control" name="submit" value="Publish message">
								</form>
							</div>
							<?php
						}
					}
					else {
						?>
						<div class="col-md-12 topic-message" id="topic_msg_denied">
							<p>You must be authenticated to publish in topics</p>
						</div>
						<?php
					}
				?>
				</div>
			</div>