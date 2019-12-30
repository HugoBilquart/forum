<?php
	if(isset($_GET['user'])) {
		$req_user_info = 'SELECT id,name,role,profile_pic,country,birth_date,biography,signature,registration_date,video_link FROM users WHERE name ="'.$_GET['user'].'"';
		$results_user_info = $connBDD->query($req_user_info);
		$info = $results_user_info->fetch();
		if(empty($info)) {
			echo '<p class="page_name" style="color: red;">No user named <u>'.$_GET['user'].'</u> found</p>';
		}
		else {
			echo '<p class="page_name">Profile of '.$_GET['user'].'</p>';
			if($_SESSION) {
				if($info['id'] == $_SESSION['userID']) {
					echo '<script type="text/javascript"> window.location = "index.php?page=yourPage"; </script>';
				}
			}
		}
	}
	else {
		echo '<script type="text/javascript"> window.location = "index.php?page=home"; </script>';
	}
?>

<table>
  	<tr>
	    <td colspan="2" style="border: solid white;">
	    	<?php
	    		if(isset($_GET['user']) AND !empty($info)) {
		    		$id = $info['id'];
					$role = roleINT_CHAR($info['role']);
					$profile_pic = $info['profile_pic'];
					if(empty($info['registration_date'])) {	
						$info['registration_date'] = "the beginning";
					}

					echo '<table class="personal_page"><tr><th colspan="2" class="personal_page">Global statistics</th></tr>';

		    		if($_POST) {
						if($_POST['playVDO'] == 'Play') {
							if($info['video_link'] == '') {
								$info['video_link'] = 'https://www.youtube.com/embed/iGpuQ0ioPrM';
							}
							echo '<tr><td rowspan="6" id="personal_page_pp"><iframe height="315" width="560" src="'.$info['video_link'].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></td></tr>';
						}
						else {
							echo '<tr><td rowspan="6" id="personal_page_pp"><img src="'.$profile_pic.'" class="profile_user_pic" title="Click on profile picture to display video player" id="profile_picture"></td></tr>';
						}
					}
					else {
						echo '<td rowspan="6" id="personal_page_pp"><img src="'.$profile_pic.'" class="profile_user_pic" title="Click on profile picture to display video player" id="profile_picture"></td>';
					}
					if(empty($info['country']))
						$info['country'] = "<span class='emptyDetail'>Undefined</span>";

					if(empty($info['birth_date']))
						$info['birth_date'] = "<span class='emptyDetail'>Undefined</span>";
					
					echo '<td class="personal_page"><a id="username" title="Click on username to hide video player"><span class="name personal_page">'.$info['name'].'</span></a></td></tr>';
					echo '<tr><td class="personal_page"><span class="role personal_page">'.$role.'</span></td></tr>';
					echo '<tr><td class="personal_page"><span class="personal_page">Member since '.$info['registration_date'].'</span></td></tr>';
					echo '<tr><td class="personal_page"><span class="personal_page">From '.$info['country'].'</span></td></tr>';
					echo '<tr><td class="personal_page"><span class="personal_page">Birthday '.$info['birth_date'].'</span></td></tr></table>';
			?>
		</td>
		<td>
			<?php
		    		if(empty($info['biography']))
						$info['biography'] = "<span class='emptyDetail'>Introduce yourself in some words...</span>";
					
		    		echo '<td><table class="personal_page"><tr><th class="personal_page">Biography</th></tr>';
					echo '<tr><td class="personal_page" rowspan="6">'.$info['biography'].'</td></tr></table></td>';
				}
	    	?>
	    </td>
	</tr>
  	<tr>
	    <td colspan="2">
	    	<?php
	    		if(isset($_GET['user']) AND !empty($info)) {
		    		if(empty($info['signature']))
						$info['signature'] = "<span class='emptyDetail'>Write a message that will appear at the end of your messages</span>";

					echo '<td colspan="5"><table class="personal_page"><tr><th class="personal_page" colspan="5">Signature</th></tr>';
					echo '<tr><td colspan="3" class="personal_page">'.$info['signature'].'</td></tr></table></td>';
		    	
			 		echo '<td><table class="personal_page" id="video_commands"><tr><th class="personal_page" colspan="2">Video commands</th></tr>
					<tr><form action="index.php?page=profile&user='.$_GET['user'].'" method="POST"><td class="personal_page">
						<input type="submit" name="playVDO" value="Play" id="play_button">
						<input type="submit" name="playVDO" value="Stop" id="stop_button">
					</td></form></tr></table>';
				}
			?>
	    </td>
  	</tr>
</table>

<script type="text/javascript">
	
	$('#profile_picture').click(function(){
		$("#play_button").trigger("click");
	});

	$('#username').click(function(){
		$('#stop_button').trigger('click');
	});

</script>