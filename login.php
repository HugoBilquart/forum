<form method="POST" id="loginForm">
	<fieldset>
		<legend>Login</legend>
		<table>
			<tr>
				<td><label>Username</label></td>
				<td><input type="text" name="userLogin" class="form-control"></td>
			</tr>
			<tr>
				<td><label>Password</label></td> 
				<td><input type="password" name="passLogin" class="form-control"></td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="submit" value="Login" class="btn btn-primary">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<a href="index.php?page=register" class="linkLogin">No account ? Join us</a>
				</td>
			</tr>
		</table>
		<?php
			if($_POST) {
				if(isset($_POST['submit'])) {
					if($_POST['submit'] == 'Login') {
						$connBDD = DBConnection();
						$pass = $_POST['passLogin'];
						include("hash.php");
						$hashed_password = crypt("$pass", "$hash");

						$req = 'SELECT id,name,role,profile_pic,isMuted FROM users WHERE name="'.$_POST['userLogin'].'" AND password="'.$hashed_password.'"';
						$results = $connBDD->query($req);
						$line = $results->fetch();
						if(empty($line)) {
							echo "<p class='loginFailed'>Incorrect identifiers</p>";
						}
						else if($line['role'] == '0') {
							echo '<li class="results"><p class="loginFailed">This account is excluded or banned from the forum</p></li>';
						}
						else {
							$_SESSION['userID'] = $line['id'];
							$_SESSION['start'] = date('l d F Y G:i');
							$_SESSION['userName'] = $line['name'];
							$_SESSION['role'] = $line['role'];
							$_SESSION['isMuted'] = $line['isMuted'];
							$_SESSION['pp'] = $line['profile_pic'];
							echo '<script type="text/javascript"> window.location = "'.$_SERVER['REQUEST_URI'].'"; </script>';
						}
					}
				}
			}
		?>
	</fieldset>
</form>