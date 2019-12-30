<?php 
	if($_SESSION) {
		echo '<p class="page_name">Personal page of '.$_SESSION['userName'].'</p>';
	}
	else {
		echo '<center><p class="loginFailed">You must be authenticated to access this page</p></center>';
	}
?>
<table style="margin: auto;">
	<tr>
		<td rowspan="2">
			<?php
				if($_SESSION) {
					$connBDD = DBConnection();
					$req = 'SELECT id,name,email,role,profile_pic,country,birth_date,biography,signature,registration_date,video_link FROM users WHERE name ="'.$_SESSION['userName'].'"';

					$req_nb_message = 'SELECT count(id) AS nb FROM messages WHERE `id_user` ="'.$_SESSION['userID'].'"';
					$results_nb_message = $connBDD->query($req_nb_message);
					if($results_nb_message) {
						$line_nb_message = $results_nb_message->fetch();
						$nbMessage = $line_nb_message['nb'];
					}
					else {
						$nbMessage = 0;
					}

					$req_nb_topic = 'SELECT count(id) AS nb FROM topics WHERE `topic_owner` ="'.$_SESSION['userName'].'"';
					$results_nb_topic = $connBDD->query($req_nb_topic);
					if($results_nb_topic) {
						$line_nb_topic = $results_nb_topic->fetch();
						$nbTopic = $line_nb_topic['nb'];
					}
					else {
						$nbTopic = 0;
					}

					

					$results = $connBDD->query($req);
					$ligne = $results->fetch();
					$id = $ligne['id'];
					$name = $ligne['name'];
					$email = $ligne['email'];
					$role = roleINT_CHAR($ligne['role']);
					if($ligne['profile_pic'] == '') {
						$profile_pic = "/images/users_avatar/default.jpg";
					} 
					else {
						$profile_pic = $ligne['profile_pic'];
					}
					if($ligne['country'] == '') {
						$country = "<span class='emptyDetail'>Undefined</span>";
					}
					else {
						$country = '<span>'.$ligne['country'].'</span>';
					}
					if($ligne['birth_date'] == '' || $ligne['birth_date'] == '//') {
						$birth_date = "<span class='emptyDetail'>Undefined</span>";
					}
					else {
						$birth_date = '<span>'.$ligne['birth_date'].'</span>';
					}
					if($ligne['biography'] == '') {
						$biography = "<span class='emptyDetail'>Introduce yourself in some words...</span>";
					}
					else {
						$biography = $ligne['biography'];
					}
					if($ligne['signature'] == '') {
						$signature = "<span class='emptyDetail'>Write a message that will appear at the end of your messages</span>";
					}
					else {
						$signature = $ligne['signature'];
					}
					if($ligne['video_link'] == '') {
						$video_link = 'https://www.youtube.com/embed/iGpuQ0ioPrM';
					}
					else {
						$video_link = $ligne['video_link'];
					}

					$registration_date = $ligne['registration_date'];

					echo '<table class="personal_page"><tr><th colspan="2" class="personal_page">Global statistics</th></tr>';
					echo '<tr>';
					if($_POST) {
						if($_POST['playVDO'] == 'Play') {
							echo '<tr><td rowspan="6" id="personal_page_pp"><iframe height="315" width="560" src="'.$video_link.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></td></tr>';
						}
						else {
							echo '<tr><td rowspan="6" id="personal_page_pp"><img src="'.$profile_pic.'" class="profile_user_pic" title="Click on profile picture to display video player" id="profile_picture"></td></tr>';
						}
					}
					else {
						echo '<td rowspan="6" id="personal_page_pp"><img src="'.$profile_pic.'" class="profile_user_pic" title="Click on profile picture to display video player" id="profile_picture"></td>';
					}

					
					echo '<td class="personal_page"><a id="username" title="Click on username to hide video player"><span class="name personal_page">'.$name.'</span></a></td></tr>';
					echo '<tr><td class="personal_page"><span class="role personal_page">'.$role.'</span></td></tr>';
					echo '<tr><td class="personal_page"><span class="personal_page">Member since '.$registration_date.'</span></td></tr>';
					echo '<tr><td class="personal_page"><span class="personal_page">From '.$country.'</span></td></tr>';
					echo '<tr><td class="personal_page"><span class="personal_page">Birthday '.$birth_date.'</span></td></tr></table></td>';

					echo '<td><table class="personal_page"><tr><th class="personal_page">Forum statistics</th></tr>';
					echo '<tr><td class="personal_page">You posted '.$nbMessage.' messages</td></tr>';
					echo '<tr><td class="personal_page">You created '.$nbTopic.' topics</td></tr></table></td></tr>';

					echo '<tr><td><table class="personal_page"><tr><th class="personal_page">Biography</th></tr>';
					echo '<tr><td class="personal_page">'.$biography.'</td></tr></table>';

					echo '<tr><td><table class="personal_page" id="video_commands"><tr><th class="personal_page">Video commands</th></tr>
						<tr><form action="index.php?page=yourPage" method="POST">
						<td class="personal_page"><input type="submit" name="playVDO" value="Play" id="play_button"><input type="submit" name="playVDO" value="Stop" id="stop_button"></td>
						</form></tr></table></td></tr>';

					echo '<tr><td colspan="5"><table class="personal_page"><tr><th class="personal_page" colspan="5">Signature</th></tr>';
					echo '<tr><td colspan="3" class="personal_page">'.$signature.'</td></tr></table></td></tr>';

					echo '<tr><td colspan="2" id="editProfileButtons"><form action="index.php?page=editProfile" method="POST">';
				 	echo '<input type="submit" name="submit" value="Change avatar">';
					echo '<input type="submit" name="submit" value="Edit profile details">';
					echo '<input type="submit" name="submit" value="Edit account informations">';
					echo '<input type="submit" name="submit" value="Change password">';		
					echo '</form>';
				}
			?>	
		</td>
	</tr>
</table>

<script type="text/javascript">
	
	$('#personal_page_pp').click(function(){
		$("#play_button").trigger("click");
	});

	$('#username').click(function(){
		$('#stop_button').trigger('click');
	});

</script>