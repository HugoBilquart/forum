$req_title_last = 'SELECT count(id) AS nb,creation_date FROM topics ORDER BY id DESC LIMIT 5';
$results_title_last = $connBDD->query($req_title_last);
$line_title_last = $results_title_last->fetch();
$req_last_topics = 'SELECT id,topic_name,nb_message,topic_owner FROM topics ORDER BY id DESC LIMIT 5';
$results_last_topics = $connBDD->query($req_last_topics);
$tab_last_topics = $results_last_topics->fetchAll(PDO::FETCH_ASSOC);


echo '</table>';

			echo '<table class="topic_category"><tr style="background-color: #009996"><th colspan="6">5 last topics</th></tr></table>';

			topicTableTop();
			foreach ($tab_last_topics as $key => $value) {
				echo "<tr>";
				$id = $values;
				$req_last_msg = 'SELECT publish_date FROM messages WHERE id_topic ='.$tab_last_topics[$key]['id'].' ORDER BY publish_date DESC LIMIT 1';
				$results_last_msg = $connBDD->query($req_last_msg);
				$line = $results_last_msg->fetch();
				$topic_pic = topic_folder($name);
				echo '	<td class="topic"><img src="'.$topic_pic.'" class="topic_folder"></td>
						<td class="topic"><a href="index.php?page=topic&value='.$tab_last_topics[$key]['id'].'" class="topic_link">'.$tab_last_topics[$key]['topic_name'].'</a></td>';
				echo '<td class="topic"><p class="topic_info">'.$tab_last_topics[$key]['nb_message'].'</p></td>';
				echo '<td class="topic"><p class="topic_info">'.$line['publish_date'].'</p></td>';
			}
			echo "</tr>";