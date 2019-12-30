<table>
	<?php
		if($_POST) {
			if(isset($_POST['category'])) {
				echo '<a class="forum_welcome"><u>SEARCH</u> -> '.$_POST["category"].'</a>';
			}
		}
		else if(!isset($_GET['category']) OR $_GET['category'] == 'home') {
			echo '<a class="forum_welcome">Welcome on the forum</a>';
		}
		else if($_GET['category']) {
			echo '<a class="forum_welcome">'.$_GET["category"].'</a>';
		}
		
	?>
	
	<hr>
	<?php 
		if($_SESSION AND $_SESSION['isMuted'] == 0) { 
		echo '<p><a href="index.php?page=newTopic" class="topic_link"><img src="images/folders/add.png" class="topic_folder"> Create a topic</a></p>'; 
		}
	?>
	<form method="POST" action="index.php?page=forum"><p class="forum_search">Specific research (keywords, topic) --> <input type="text" name="category"><input type="submit" name="" value="🔍"></p></form>
</table>

<?php
	if($_POST) {
		if(isset($_POST['category'])) {
			$req_count = 'SELECT count(id) AS nb FROM topics WHERE theme LIKE "%'.$_POST['category'].'%" OR topic_name LIKE "%'.$_POST['category'].'%"';
			$result_count = $connBDD->query($req_count);
			if($result_count) {
				$line_count = $result_count->fetch();
				if($line_count['nb'] == 0) {
					echo '<table class="topic_category"><tr><th><p class="loginFailed">No topic found</p></th></tr></table>';
				}
				else {
					$req_creation_date = 'SELECT creation_date FROM topics WHERE theme LIKE "%'.$_POST['category'].'%" OR topic_name LIKE "%'.$_POST['category'].'%" ORDER BY creation_date DESC LIMIT 1';
					$result_creation_date = $connBDD->query($req_creation_date);

					
					$line = $result_creation_date->fetch();
					echo '	<table class="topic_category">
								<tr style="background-color: #009996">
									<th colspan="4"> Research -> '
										.$_POST['category'].	
									'</th>
									<th colspan="2">
										Number of articles : '.$line_count['nb'].'
									</th>
									<th colspan="2">
										Last article : '.$line['creation_date'].'
									</th>
								</tr>
							</table>';

					topicTableTop();

					$req = 'SELECT id,topic_name,nb_message,topic_owner FROM topics WHERE theme LIKE "%'.$_POST['category'].'%" OR topic_name LIKE "%'.$_POST['category'].'%" ORDER BY creation_date';
					echo $req;
					$results = $connBDD->query($req);
					$tab = $results->fetchAll(PDO::FETCH_ASSOC);
					foreach ($tab as $key => $value) {
						echo "<tr>";
						foreach ($tab[$key] as $keys => $values) {
							if($keys == "id") {
								$id = $values;
								$req_last_msg = 'SELECT publish_date FROM messages WHERE id_topic ='.$id.' ORDER BY publish_date DESC LIMIT 1';
								$results_last_msg = $connBDD->query($req_last_msg);
								$line_last_msg = $results_last_msg->fetch();
								$last_msg_date = $line_last_msg['publish_date']; 
							}
							else if($keys == "topic_name") {
								$name = $values;
								$topic_pic = topic_folder($name);
								echo '	<td class="topic"><img src="'.$topic_pic.'" class="topic_folder"></td>
										<td class="topic"><a href="index.php?page=topic&value='.$id.'" class="topic_link">'.$name.'</a></td>';
								echo '<td class="topic"><p class="topic_info">'.$tab[$key]['nb_message'].'</p></td>';
								echo '<td class="topic"><p class="topic_info">'.$line_last_msg['publish_date'].'</p></td>';
							}
						}
						echo "</tr>";
					}
					echo '</table>';
				}
			}
		}
	}
	else {	
		if(!isset($_GET['category']) OR $_GET['category'] == 'home') {
			$req_title = 'SELECT count(id) AS nb,creation_date FROM topics WHERE theme = "rules" OR theme = "changelogs"';
			$results = $connBDD->query($req_title);
			$line = $results->fetch();

			echo '	<table class="topic_category"><tr style="background-color: #009996">
						<th colspan="4">Rules & Changelogs</th>
						<th colspan="2">Number of articles : '.$line['nb'].'</th>
						<th colspan="2">Last article : '.$line['creation_date'].'</th>
					</tr></table>';

			topicTableTop();
			$req = 'SELECT id,topic_name,nb_message,topic_owner FROM topics WHERE theme = "rules" OR theme = "changelogs"';

			$results = $connBDD->query($req);
			

			$tab = $results->fetchAll(PDO::FETCH_ASSOC);
			

			foreach ($tab as $key => $value) {
				echo "<tr>";
				foreach ($tab[$key] as $keys => $values) {
					if($keys == "id") {
						$id = $values;
						$req_last_msg = 'SELECT publish_date FROM messages WHERE id_topic ='.$id.' ORDER BY publish_date DESC LIMIT 1';
						$results_last_msg = $connBDD->query($req_last_msg);
						$line = $results_last_msg->fetch();
					}
					else if($keys == "topic_name") {
						$name = $values;
						$topic_pic = topic_folder($name);
						echo '	<td class="topic"><img src="'.$topic_pic.'" class="topic_folder"></td>
								<td class="topic"><a href="index.php?page=topic&value='.$id.'" class="topic_link">'.$values.'</a></td>';
						echo '<td class="topic"><p class="topic_info">'.$tab[$key]['nb_message'].'</p></td>';
						echo '<td class="topic"><p class="topic_info">'.$line['publish_date'].'</p></td>';
					}
				}
				echo "</tr>";
			}
			echo '</table>';
		}
		
		else {
			$req_count = 'SELECT count(id) AS nb FROM topics WHERE theme ="'.$_GET["category"].'"';
			$req_creation_date = 'SELECT creation_date FROM topics WHERE theme ="'.$_GET["category"].'" ORDER BY creation_date DESC LIMIT 1';
			$result_count = $connBDD->query($req_count);
			$result_creation_date = $connBDD->query($req_creation_date);

			$count = $result_count->fetch();
			$line = $result_creation_date->fetch();
			if($count['nb'] == '') {
				echo '<p class="login_failed">No topic has been found</p>';
			}
			else {
				echo '	<table class="topic_category">
							<tr style="background-color: #009996">
								<th colspan="4">'
									.$_GET['category'].	
								'</th>
								<th colspan="2">
									Number of articles : '.$count['nb'].'
								</th>
								<th colspan="2">
									Last article : '.$line['creation_date'].'
								</th>
							</tr>
						</table>';

				topicTableTop();
				$req = 'SELECT id,topic_name,nb_message,topic_owner FROM topics WHERE theme ="'.$_GET["category"].'" ORDER BY creation_date';
				$results = $connBDD->query($req);
				

				$tab = $results->fetchAll(PDO::FETCH_ASSOC);
				foreach ($tab as $key => $value) {
					echo "<tr>";
					foreach ($tab[$key] as $keys => $values) {
						if($keys == "id") {
							$id = $values;
							$req_last_msg = 'SELECT publish_date FROM messages WHERE id_topic ='.$id.' ORDER BY publish_date DESC LIMIT 1';
							$results_last_msg = $connBDD->query($req_last_msg);
							$line = $results_last_msg->fetch();
						}
						else if($keys == "topic_name") {
							$topic_pic = topic_folder($values);
							echo '	<td class="topic"><img src="'.$topic_pic.'" class="topic_folder"></td>
									<td class="topic"><a href="index.php?page=topic&value='.$id.'" class="topic_link">'.$values.'</a></td>';

							echo '<td class="topic"><p class="topic_info">'.$tab[$key]['nb_message'].'</p></td>';
							echo '<td class="topic"><p class="topic_info">'.$line['publish_date'].'</p></td>';
						}
						
					}
					echo "</tr>";
				}
				echo '</table>';
			}		
		}
	}
?>

<style type="text/css">
	table.topic_category {
		width:	80%;
		margin:	10;
	}

	table.topics {
		width: 	90%;
		margin: 20;
		padding-left: 10px;
	}

	td#corner {
		background:			linear-gradient(to top right,rgba(109, 225, 255, 0.5),rgba(0, 153, 150, 0.5));
	}

	td.topic_folder {
		padding-left: 		10px;
		align-content: 		center;
	}

	a.topic_link {
		color: 				white;
		text-decoration: 	none;
	}

	td.topic {
		background-color: 	#6de1ff;
		text-align: 		center;
	}

	p.topic_info {
		color: white;
		text-align: center;
	}

	img.topic_folder {
		margin: auto;
		width: 	20px;
		height: 20px;
	}

	.topic_action {
		font-weight: bold;
	}

	hr {
		margin-left: 5px;
		width: 80%;
		height: 2px;
		border-radius: 30px;
		background-color: white;
	}

</style>
