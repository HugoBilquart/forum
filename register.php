<?php
	if($_SESSION) {
		echo '<script type="text/javascript"> window.location = "index.php?page=home"; </script>';
	}
	else {
		?>
		<div class="col-md-12">
		<?php
		if($_POST) {
			if($_POST['submit'] == 'Register') {
				echo "<p>Request of registration : ";
				if(empty($_POST['newNickname']) OR empty($_POST['newEmail']) OR empty($_POST['newPass']) OR empty($_POST['newPassConfirm']) OR empty($_POST['rulesConfirm'])) {
					?>
					<span class='failed'>FAILED</span></p>
					<p class='failed'>You didn't fill every required field</p>
					<?php
				}
				else {
					if(strlen($_POST['newNickname']) < 4) {
						?>
						<span class='failed'>FAILED</span></p>
						<p class='failed'>Your username must be at least 4 characters long.</p>
						<?php
					}
					else if(strlen($_POST['newPass']) < 6) {
						?>
						<span class='failed'>FAILED</span></p>
						<p class='failed'>Your password must be at least 6 characters long.</p>
						<?php
					}
					else if($_POST['newPass'] != $_POST['newPassConfirm']) {
						?>
						<span class='failed'>FAILED</span></p>
						<p class='failed'>Your password and the confirmation don't match.</p>
						<?php
					}
					else {
						//Check if username or email address is already used
						$requestDouble = $connex_PDO->query('SELECT * FROM users WHERE `name` = "'.$_POST['newNickname'].'" OR `email` = "'.$_POST['newEmail'].'"');
						$doubleAccount = $requestDouble->fetchAll();
						if(count($doubleAccount) > 0) {
							?>
							<span class='failed'>FAILED</span></p>
							<p class='failed'>Your username or email address is already used.</p>
							<?php
						}
						else {
							//Create new user
							$newUser_PP = createNewAvatar($_POST['newNickname']);
							$newUser_birth_date = $_POST['birth_day'].'/'.$_POST['birth_month'].'/'.$_POST['birth_year'];
							if($newUser_birth_date == '//') {
								$newUser_birth_date = '';
							}
							if(empty($_POST['newCountry'])) {
								$country = '';
							}
							else {
								$country = $_POST['newCountry'];
							}
							$registration_date = date('d/m/Y');

							include("hash.php");
							$hashed_password = crypt(''.$_POST['newPass'].'', "$hash");
				
							$requestNewUser = $connex_PDO->query('INSERT INTO users (name,password,email,role,profile_pic,country,birth_date,registration_date,isMuted) VALUES ("'.$_POST['newNickname'].'","'.$hashed_password.'","'.$_POST['newEmail'].'","1","'.$newUser_PP.'","'.$country.'","'.$newUser_birth_date.'","'.$registration_date.'","0") ');
							if($requestNewUser) {
								?>
								<span class='loginDone'>SUCCESS</span></p>
								<p class='success'>Your registration is complete</p>
								<?php
							}
							else {
								?>
								<span class='failed'>FAILED</span></p>
								<p class='failed'>Your registration failed</p>
								<?php
							}
						}
					}
				}
			}
		}
		?>
		</div>
		<?php
		include('forms/registerForm.html');	
	}
?>
