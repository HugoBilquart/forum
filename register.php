<?php
	if($_SESSION) {
		echo '<script type="text/javascript"> window.location = "index.php?page=home"; </script>';
	}
?>

			<?php include('forms/registerForm.html'); ?>
			<?php
				if($_POST) {
					if($_POST['submit'] == 'Register') {
						$checkFormAgain = checkFormAgain(); 
						echo "<p>Request of registration : ";
						if(empty($_POST['newNickname']) OR empty($_POST['newEmail']) OR empty($_POST['newPass']) OR empty($_POST['newPassConfirm'])) {
							echo "<span class='loginFailed'>FAILED</span></p>";
							echo "<p class='loginFailed'>You didn't filled every required field</p>";
						}
						else {


							$newUser_nickname = $_POST['newNickname'];
							$newUser_email = $_POST['newEmail'];

							
							echo "<span class='loginDone'>SUCCESS</span></p>";
							$newUser_country = $_POST['newCountry'];
							$newUser_PP = createNewAvatar($newUser_nickname);
							$newUser_birth_date = $_POST['birth_day'].'/'.$_POST['birth_month'].'/'.$_POST['birth_year'];
							if($newUser_birth_date == '//') {
								$newUser_birth_date = '';
							}
							$registration_date = date('d/m/Y');

							$newUser_pass = $_POST['newPass'];
							
							$connBDD = DBConnection();
							$reqNum = 'SELECT MAX(id)+1 FROM users';
							$callBack = $connBDD->query($reqNum);
							if($callBack) {
								$nbUser = $callBack->fetchColumn();
							}
							$req = 'INSERT INTO users (id,name,password,email,role,profile_pic,country,birth_date,registration_date,isMuted) VALUES ("'.$nbUser.'","'.$newUser_nickname.'","'.$newUser_pass.'","'.$newUser_email.'","1","'.$newUser_PP.'","'.$newUser_country.'","'.$newUser_birth_date.'","'.$registration_date.'","0")';
							$results = $connBDD->exec($req);
							if($results) {
								echo "<p class='loginDone'>Your registration is complete</p>";
							}
							else {
								echo "<p class='loginFailed'>Your registration failed</p>";
							}
						}
					}
				}
			?>
		</div>
	</fieldset>
</form>
