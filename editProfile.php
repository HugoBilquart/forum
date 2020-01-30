<div class="col-md-12">
	<h1 class="page_name">Edit profile page</h1>
</div>
<div class="col-md-12 m-auto text-center">
	<form method='POST' enctype="multipart/form-data" name="editProfile">
			<?php
				if($_POST) {
					switch ($_POST['submit']) {
						case 'Change avatar':
							?>
							<h1 class="page_name text-center">Change your avatar</h1>
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
							$results = $connBDD->query('SELECT country,birth_date,biography,signature FROM users WHERE id='.$_SESSION['userID'].'');
							$data = $results->fetch();
							echo   '<input type="hidden" name="currentCountry" value="'.$data['country'].'">
									<input type="hidden" name="currentBirth_date" value="'.$data['birth_date'].'">
									<input type="hidden" name="currentBiography" value="'.$data['biography'].'">
									<input type="hidden" name="currentSignature" value="'.$data['signature'].'">';

							?>
								<h1 class="page_name text-center">Edit profile details</h1>
								<form method="POST" name="form" id="register-form">
									<div class="row">
										<?php include('forms/editProfileDetailsForm.html'); ?>
										<div class="form-group col-sm-12 form-part">
											<p><label>Biography</label></p>
											<textarea rows="9" cols="45" maxlenght="200" name="editBiography" class="editTextarea"><?php
												echo $data['biography']; 
											?></textarea>
										</div>
										<div class="form-group col-sm-12 form-part">
											<p><label>Signature</label></p>
											<textarea rows="2" cols="45" maxlenght="200" name="editSignature" class="editTextarea"><?php
												 echo $data['signature']; 
											?></textarea>
										</div>
										<div class="form-group col-sm-12">
											<input type="submit" class="btn btn-primary" name="submit" id="submit" value="Confirm profil details">
										</div>
									</div>
								</form>
							<?php
						break;
						
						case 'Edit account informations':
							$connBDD = DBConnection();
							$req_login_info = 'SELECT name,password,email FROM users WHERE id='.$_SESSION['userID'].'';
							$results = $connBDD->query($req_login_info);
							$line = $results->fetch();
							?>
							<div class="col-md-12 m-auto text-center">
								<h1 class="page_name text-center">Edit login informations</h1>
								<form method="POST">
									<div class="row">
										<div class="col-sm-5 form-part">
											<p><label for="editUsername">Username</label></p>
											<input type="text" class="form-control" name="editUsername" id="editUsername" value="<?php echo $line['name']; ?>" required>
										</div>
										<div class="col-sm-2"></div>
										<div class="col-sm-5 form-part">
											<p><label for="editEmail">E-Mail address</label></p>
											<input type="text" class="form-control" name="editEmail" id="editEmail" value="<?php echo $line['email']; ?>" required>
										</div>
										<div class="col-sm-12"><br/></div>
										<div class="col-sm-12 form-part">
											<p><label for="editConfirmation">Password confirmation</label></p>
											<input type="password" class="form-control" name="editConfirmation" id="editConfirmation" required>
										</div>
									</div>
									<br/>
									<input type="submit" name="submit" class="btn btn-primary" id="submit" value="Confirm new account details">
								</form>
							</div>
							<?php
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
								$req_profile_edit = 'UPDATE users SET `country` = "'.$editCountry.'",`birth_date` = "'.$editBirth_date.'",`biography` = "'.$_POST['editBiography'].'",`signature` = "'.$_POST['editSignature'].'" WHERE `id` ='.$_SESSION['userID'];
								$results = $connBDD->exec($req_profile_edit);
								if($results)
									echo '<p class="success">Profile details successfully edited !</p>';
								
								else 
									echo '<p class="failed">Profile details edition failed</p>';
								echo '<a href="index.php?page=profile&user='.$_SESSION['userName'].'">Return to your page</a>';
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
								echo '<p class="failed">Upload failed</p>';
								echo $_FILES['newAvatar']['error'];
							}
							else {
								?>
								<p class="success">Profile picture updated !</p>
								<p>Refresh your profile page to see changes</p>
								<?php
							}
							?>
							<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to your page</a></p>
							<?php
						break;

						case 'Confirm new password':
							if(!empty($_POST['currentPass']) && !empty($_POST['editPass']) && !empty($_POST['confEditPass'])) {
								include("hash.php");
								$hashed_password = crypt(''.$_POST['currentPass'].'', "$hash");
								$requestCheckUser = $connex_PDO->query('SELECT id FROM users WHERE name = "'.$_SESSION['userName'].'" AND password = "'.$hashed_password.'"');
								if(empty($requestCheckUser->fetch())) {
									?>
									<p class="failed">Wrong password !</p>
									<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to your page</a></p>
									<?php
								}
								else if($_POST['editPass'] != $_POST['confEditPass']) {
									?>
									<p class="failed">New password and confirmation don't match !</p>
									<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to your page</a></p>
									<?php
								}
								else {
									$hashed_password = crypt(''.$_POST['editPass'].'', "$hash");
									$results = $connex_PDO->exec('UPDATE users SET `password` = "'.$hashed_password.'" WHERE `id`="'.$_SESSION['userID'].'"');
									if($results) {
										?>
										<p class="success">Password updated !</p>
										<p>You'll have to use your new password at your next connection.</p>
										<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to your page</a></p>
										<?php
									}
									else {
										?>
										<p class="failed">Failed to update your password</p>
										<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to your page</a></p>
										<?php
									}
								}
							}
							else {
								?>
								<p class="failed">Please fill every field of the form.</p>
								<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to your page</a></p>
								<?php
							}
						break;

						case 'Confirm new account details':
							if($_POST) {
								?>
								<div class="col-md-12">
								<?php
								if(empty($_POST['editUsername']) || empty($_POST['editEmail']) || empty($_POST['editConfirmation'])) {
									?>
									<p class="failed">Please fill every field of the form</p>
									<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to home page</a></p>
									<?php
								}
								else {
									include("hash.php");
									$hashed_password = crypt(''.$_POST['editConfirmation'].'', "$hash");
									$requestCheckUser = $connex_PDO->query('SELECT id FROM users WHERE name = "'.$_SESSION['userName'].'" AND password = "'.$hashed_password.'"');
									if(empty($requestCheckUser->fetch())) {
										?>
										<p class="failed">Wrong password !</p>
										<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to home page</a></p>
										<?php
									}
									else if (!preg_match( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $_POST['editEmail'] ) ) {
										?>
										<p class="failed">Your email address is improper</p>
										<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to home page</a></p>
										<?php
									}
									else if(strlen($_POST['editUsername']) < 4) {
										?>
										<p class="failed">Your username is not long enough (at least 4 characters)</p>
										<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to home page</a></p>
										<?php
									}
									else {
										$requestUpdate = $connex_PDO->exec('UPDATE users SET name = "'.$_POST['editUsername'].'", email = "'.$_POST['editEmail'].'" WHERE id='.$_SESSION['userID']);
										if($requestUpdate) {
											?>
											<p class="success">Your login informations are updated, you have to log you out to apply changes.</p>
											<a href="index.php?page=logout" id="logout_link">Logout</a>
											<?php
										}
										else {
											?>
											<p class="failed">Failed to update your login informations.</p>
											<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to home page</a></p>
											<?php
										}
									}
								}
								?>
								</div>
								<?php
							}
							break;

							default:
								echo "Invalid submit";
								echo '<a href="index.php?page=yourPage">Return to your page</a>';
							break;
					}
				}
				else {
					?>
					<p class="failed">Access denied</p>
					<p><a href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Return to home page</a></p>
					<?php
				}
			?>
		</table>
	</form>
</div>

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
		width: 90%; 

		margin: 	auto;
		text-align: left;
	}

	tr#submit_buttons {
		margin: 10px;
		text-align: center;
	}

</style>