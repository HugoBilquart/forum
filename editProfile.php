<form method='POST' enctype="multipart/form-data" name="editProfile">
		<?php
			if($_POST) {
				switch ($_POST['submit']) {
					case 'Change avatar':
						?>
						<h1 class="page_name text-center">Change avatar page</h1>
						<div class="col-md-12 m-auto">
							<?php
								include('forms/changeAvatarForm.html');
							?>
						</div>
						<?php
					break;
		?>
		<table class="editProfileForm">
		<?php
					case 'Edit profile details':
						$connBDD = DBConnection();
						$req_profile_info = 'SELECT country,birth_date,biography,signature,video_link FROM users WHERE id='.$_SESSION['userID'].'';
						$results = $connBDD->query($req_profile_info);
						$line = $results->fetch();
						echo   '<input type="hidden" name="currentCountry" value="'.$line['country'].'">
								<input type="hidden" name="currentBirth_date" value="'.$line['birth_date'].'">
								<input type="hidden" name="currentBiography" value="'.$line['biography'].'">
								<input type="hidden" name="currentSignature" value="'.$line['signature'].'">';

						include('forms/editProfileDetailsForm.html');

						echo '<tr><td rowspan="2"> - Biography</td><td><textarea rows="5" cols="25" maxlenght="200" name="editBiography" class="editTextarea" onchange="remainsChar(1)">'.$line['biography'].'</textarea></td><td rowspan="2"> - Signature</td><td><textarea rows="5" cols="25" maxlenght="100" name="editSignature" class="editTextarea" onchange="remainsChar(2)">'.$line['signature'].'</textarea></td></tr>';

						echo '<tr><td colspan="4"><p>Video link (from Youtube) <input type="text" name="editVideoLink" value="'.$line['video_link'].'" size="50"></td></tr>';

						echo '<tr><td colspan="4"><center><p><input type="submit" name="submit" id="submit" value="Confirm profil details"></p></center></td></tr>';
					break;
					
					case 'Edit account informations':
						$connBDD = DBConnection();
						$req_login_info = 'SELECT name,password,email FROM users WHERE id='.$_SESSION['userID'].'';
						$results = $connBDD->query($req_login_info);
						$line = $results->fetch();
						echo '<input type="hidden" name="currentUsername" value="'.$line['name'].'">';
						echo '<input type="hidden" name="currentEmail" value="'.$line['email'].'">';
						include('forms/editAccInfoForm.html');
					break;

					case 'Change password':
					echo '<input type="hidden" name="user" id="user" value="'.$_SESSION['userName'].'">';
						include('forms/changePasswordForm.html');
					break;
					

					case 'Confirm profil details':
						if($_POST) {
							if(empty($_POST['editCountry']))
								$editCountry = $_POST['currentCountry'];
							else if($_POST['editCountry'] == "delete") {
								$editCountry = '';
							}
							else
								$editCountry = $_POST['editCountry'];

							$editBirth_date = $_POST['birth_day'].'/'.$_POST['birth_month'].'/'.$_POST['birth_year'];
							if($editBirth_date == '//') {
								$editBirth_date = $_POST['currentBirth_date'];
							}
							else if($editBirth_date == 'del/del/del') {
								$editBirth_date = '';
							}
							$connBDD = DBConnection();
							$req_profile_edit = 'UPDATE users SET `country` = "'.$editCountry.'",`birth_date` = "'.$editBirth_date.'",`biography` = "'.$_POST['editBiography'].'",`signature` = "'.$_POST['editSignature'].'",`video_link` = "'.$_POST['editVideoLink'].'" WHERE `id` ='.$_SESSION['userID'];
							$results = $connBDD->exec($req_profile_edit);
							if($results)
								echo '<p class="loginDone"><center>Profile details successfully edited !</center></p>';
							
							else 
								echo '<p class="loginFailed"><center>Profile details edition failed</center></p>';
							echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
						}
						break;

					case 'Confirm new avatar':
						$connBDD = DBConnection();
						$results = $connBDD->query('SELECT profile_pic FROM users WHERE name="'.$_SESSION['userName'].'"');
						$line = $results->fetch();
						$tmp_name = $_FILES['newAvatar']['tmp_name'];
						$filename = $_SESSION['userName'].'.png';
						$upload = move_uploaded_file($tmp_name, "./images/users_avatar/$filename");
						if(!$upload) {
							echo '<p class="loginFailed">Upload failed</p>';
							echo $_FILES['newAvatar']['error'];
						}
						else {
							if(empty($line['profile_pic'])) {
								$connBDD = DBConnection();
								$req_update_pp = 'UPDATE users SET `profile_pic` = "images/users_avatar/'.$_SESSION['userName'].'.png" WHERE `id`='.$_SESSION['userID'];
								$results = $connBDD->exec($req_update_pp);
								if($results) {
									echo '<p class="loginDone"><center>Profile picture updated !</center></p>';
								}
							}
							echo '<p class="loginDone"><center>Upload done !</center></p>';
						}
						echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
					break;

					case 'Confirm new password':
						if(!empty($_POST['currentPass']) && !empty($_POST['editPass']) && !empty($_POST['confEditPass'])) {
							if($_POST['user'] != $_SESSION['userName']) {
								echo "<p class='loginFailed'>You're not allowed to edit someone else's password</p>";
								echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
							}
							else {
								$connBDD = DBConnection();
								$req_update_pass = 'UPDATE users SET `password` = "'.$_POST['editPass'].'" WHERE `name`="'.$_SESSION['userName'].'"';
								$results = $connBDD->exec($req_update_pass);
								if($results) {
									echo '<p class="loginDone"><center>Password updated !</center></p>';
									echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
								}
								else {
									echo '<p class="loginFailed">Failed to update password</p>';
								}
							}
						}
						else {
							echo '<p class="loginFailed">Form is not filled properly</p>';
							echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
						}
					break;

					case 'Confirm new account details':
						if($_POST) {
							if($_POST['editUsername'] == 'NOCHANGE' && $_POST['editEmail'] == 'NOCHANGE') {
								echo '<p>Nothing to change</p>';
								echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
							}
							else {
								if($_POST['editUsername'] == 'NOCHANGE' || empty($_POST['editUsername'])) {
									$editUsername = '';
								}
								else {
									$editUsername = $_POST['editUsername'];
								}

								if($_POST['editEmail'] == 'NOCHANGE' || empty($_POST['editEmail'])) {
									$editEmail = '';
								}
								else {
									$editEmail = $_POST['editEmail'];
								}

								$req_available = 'SELECT id FROM users WHERE name="'.$editUsername.'" OR email = "'.$editEmail.'"';
								$results_available = $connBDD->query($req_available);
								$line = $results_available->fetch();
								if(!empty($line)) {
									echo '<p class="loginFailed">Account details modification failed</p>';
									echo '<p class="loginFailed">Username or email is taken</p>';
									echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
								}
								else {
									if($editUsername == '') {
										$editUsername = $_POST['currentUsername'];
										echo "Replace by current username";
									} 
									else {
										$renamepp = rename('images/users_avatar/'.$_POST['currentUsername'].'.png','images/users_avatar/'.$editUsername.'.png');
										if($renamepp) 
											echo '<p class="loginDone">Profile picture renamed</p>';
										
										else 
											echo '<p class="loginFailed">Profile picture rename failed</p>';
									}
									if($editEmail == '') {
										$editEmail = $_POST['currentEmail'];
									}
									$req_edit_logins = 'UPDATE users SET `name`="'.$editUsername.'",`email`="'.$editEmail.'",`profile_pic` = "images/users_avatar/'.$editUsername.'.png" WHERE `id` ="'.$_SESSION['userID'].'"';
									$return = $connBDD->exec($req_edit_logins);
									if($return) {
										echo '<p class="loginDone">Account details successfully modified</p>';
										echo '<p>You should disconnect to apply modifications';
										echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
									}
									else {
										echo '<p class="loginFailed">Account details modification failed</p>';
										echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
									}

								}
							}
						}
						else {
							echo '<p class="loginFailed"><center>No change found</center></p>';
							echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
						}
						break;

						default:
							echo "Invalid submit";
							echo '<a href="index.php?page=yourPage"><center>Return to your page</center</a>';
						break;
				}
			}
			else {
				echo '<tr><th><p class="loginFailed">Access denied</p></th></tr>';
				echo '<tr><td><p><a href="index.php?page=yourPage">Return to home page</a></p></td></tr>';
			}
		?>
	</table>
</form>

<style type="text/css">
	table.editProfileForm {
		margin: auto;
		text-align: left;
	}

	th.editProfileForm {
		text-align: center;
	}

	li.editForm {
		list-style-type: none;
	}

	textarea.editTextarea {
		resize: none;
	}

	tr#submit_buttons {
		margin: 10px;
		text-align: center;
	}

</style>