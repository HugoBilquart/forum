<div class="col-md-12">
	<?php
		if($_SESSION) {
			if ($_SESSION['role'] > 1) {
				echo '<h1 class="page_name">User management page</h1>';
			}
			else {
				echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
			}
		}
		else {
			echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
		}

		if($_POST) {
			if(isset($_POST['userAction'])) {
				switch ($_POST['userAction']) {
					case '🔓':
						$req = 'UPDATE users SET role = 1 WHERE id="'.$_POST['memberToForgive'].'"';
						$results = $connBDD->exec($req);
						break;
					
					case '🔨':
						$req = 'UPDATE users SET `role` = "0" , `isMuted` = "1" WHERE id="'.$_POST['memberToBan'].'"';
						$results = $connBDD->exec($req);
						break;
	
					case '🔇':
						$req = 'UPDATE users SET isMuted = 1 WHERE id="'.$_POST['memberToMute'].'"';
						$results = $connBDD->exec($req);
						break;
	
					case '🔊':
						$req = 'UPDATE users SET isMuted = 0 WHERE id="'.$_POST['memberToUnmute'].'"';
						$results = $connBDD->exec($req);
						break;
				}
			}
		}

		$request_members = $connex_PDO->query("SELECT id,name,role,profile_pic,isMuted FROM users WHERE role=1 AND isMuted = 0");
		$members = $request_members->fetchAll();

		$request_muted = $connex_PDO->query("SELECT id,name,role,profile_pic,isMuted FROM users WHERE role=1 AND isMuted = 1");
		$muted = $request_muted->fetchAll();

		$request_banned = $connex_PDO->query("SELECT id,name,role,profile_pic,isMuted FROM users WHERE role=0");
		$banned = $request_banned->fetchAll();

	
		$nbMember = count($members);
		$nbMuted = count($muted);
		$nbBanned = count($banned);
	?>
</div>

<div class="col-md-12 user-action-area">
	<form method="POST">
		<div class="row">
			<div class="col-sm-4 userAction">
				<p><label for="muteMember">Mute a member</label></p>
				<select name="memberToMute" id="muteMember" class="form-control">
					<?php displaySelect(0,1); ?>
					<input type="submit" name="userAction" value="🔇" title="Mute selected member">
				</select>
			</div>
			<div class="col-sm-4 userAction">
				<p><label for="unmuteMember">Unmute a member</label></p>
				<select name="memberToUnmute" id="unmuteMember" class="form-control">
					<?php displaySelect(1,1); ?>
				</select>
				<input type="submit" name="userAction" value="🔊" >
			</div>
			<div class="col-sm-4 userAction">
				<p><label for="banMember">Ban a member</label></p>
				<select name="memberToBan" id="banMember" class="form-control">
					<?php displaySelectRole(1); ?>
				</select>
				<input type="submit" name="userAction" value="🔨">
			</div>
		</div>	
	</form>
</div>

<br/>

<!-- MEMBERS (NOT MUTED) -->

<div class="col-md-12 text-center" id="memberPart">
	<p>Members [ not muted ] - <?php echo $nbMember; ?></p>
</div>

<div class="col-md-12">
	<div class="row">
		<?php
			if(!empty($members)) {
				foreach ($members as $key => $value) {
					?>
						<div class="col-sm-3 userManagement_user" data-id="<?php echo $members[$key]['id'];  ?>">
							<div class="row">
								<div class="col-4">
									<img class="userManagement_pp" src="<?php echo $members[$key]['profile_pic']; ?>" alt="<?php echo $members[$key]['profile_pic']; ?>">
								</div>
								<div class="col-4">
									<a href="index.php?page=profile&user=<?php echo $members[$key]['name']; ?>" title="Profile page of <?php echo $members[$key]['name']; ?>">
										<span><?php echo $members[$key]['name']; ?> <br/>[ #<?php echo $members[$key]['id']; ?> ]</span>
									</a>
									<li><span><?php echo roleINT_CHAR($members[$key]['role']); ?></span></li>
								</div>
								<div class="col-4 button-area">
									<?php
										if($members[$key]['isMuted'] == 0) {
											?>
											<input type="text" name="'.$id.'" value="🔊" title="User '.$id.' isn\'t muted" class="changeRoleButtonUser loginDone" size="1">
											<?php
										}
										else {
											?>
											<input type="text" name="'.$id.'" value="🔇" title="User '.$id.' is muted" class="changeRoleButtonUser loginFailed" size="1">
											<?php
										}
										?>
										<br/>
										<?php
										if($members[$key]['role'] == 0) {
											?>
											<input type="text" name="'.$id.'" value="🔒" title="User is banned" class="changeRoleButtonUser loginFailed" size="1">
											<?php
										}
										else {
											?>
											<input type="text" name="'.$id.'" value="✅" title="User isn\'t banned" class="changeRoleButtonUser loginDone" size="1">
											<?php
										}
									?>
								</div>
							</div>
						</div>
					<?php
				}
			}
		?>
	</div>
</div>

<!-- END MEMBERS (NOT MUTED) -->

<!-- MUTED MEMBERS -->

<?php
if($nbMuted > 0) {
?>

<div class="col-md-12 text-center" id="mutedPart">
	<p>Members [ muted ] - <?php echo $nbMuted; ?></p>
</div>

<div class="col-md-12">
	<div class="row">
		<?php
			foreach ($muted as $key => $value) {
				?>
					<div class="col-sm-3 userManagement_user muted" data-id="<?php echo $muted[$key]['id'];  ?>">
						<div class="row">
							<div class="col-4">
								<img class="userManagement_pp" src="<?php echo $muted[$key]['profile_pic']; ?>" alt="<?php echo $muted[$key]['profile_pic']; ?>">
							</div>
							<div class="col-4">
								<a href="index.php?page=profile&user=<?php echo $muted[$key]['name']; ?>" title="Profile page of <?php echo $muted[$key]['name']; ?>">
									<span><?php echo $muted[$key]['name']; ?> <br/>[ #<?php echo $muted[$key]['id']; ?> ]</span>
								</a>
								<li><span>Muted</span></li>
							</div>
							<div class="col-4 button-area">
								<?php
									if($muted[$key]['isMuted'] == 0) {
										?>
										<input type="text" name="'.$id.'" value="🔊" title="User '.$id.' isn\'t muted" class="changeRoleButtonUser loginDone" size="1">
										<?php
									}
									else {
										?>
										<input type="text" name="'.$id.'" value="🔇" title="User '.$id.' is muted" class="changeRoleButtonUser loginFailed" size="1">
										<?php
									}
									?>
									<br/>
									<?php
									if($muted[$key]['role'] == 0) {
										?>
										<input type="text" name="'.$id.'" value="🔒" title="User is banned" class="changeRoleButtonUser loginFailed" size="1">
										<?php
									}
									else {
										?>
										<input type="text" name="'.$id.'" value="✅" title="User isn\'t banned" class="changeRoleButtonUser loginDone" size="1">
										<?php
									}
								?>
							</div>
						</div>
					</div>
				<?php
			}
		?>
	</div>
</div>

<?php
}
?>

<!-- END MUTED MEMBERS -->

<!-- BANNED MEMBERS -->

<?php
if($nbBanned > 0) {
?>
<div class="col-md-12 text-center" id="bannedPart">
	<p>Banned members - <?php echo $nbBanned; ?></p>
	<form method="POST">
		<p><label for="forgiveBanned">Forgive a banned member</label></p>
		<select name="memberToForgive" id="forgiveBanned" class="form-control userAction">
		<?php
			displaySelectRole(0);
		?>
		</select>
		<input type="submit" name="userAction" value="🔓">
	</form>
</div>
<div class="col-md-12">
	<div class="row">
		<?php
			foreach ($banned as $key => $value) {
				?>
					<div class="col-sm-3 userManagement_user banned" data-id="<?php echo $banned[$key]['id'];  ?>">
						<div class="row">
							<div class="col-4">
								<img class="userManagement_pp" src="<?php echo $banned[$key]['profile_pic']; ?>" alt="<?php echo $banned[$key]['profile_pic']; ?>">
							</div>
							<div class="col-4">
								<span><?php echo $banned[$key]['name']; ?> <br/>[ #<?php echo $banned[$key]['id']; ?> ]</span>
								<li><span>Banned</span></li>
							</div>
							<div class="col-4 button-area">
								<?php
									if($banned[$key]['isMuted'] == 0) {
										?>
										<input type="text" name="'.$id.'" value="🔊" title="User '.$id.' isn\'t muted" class="changeRoleButtonUser loginDone" size="1">
										<?php
									}
									else {
										?>
										<input type="text" name="'.$id.'" value="🔇" title="User '.$id.' is muted" class="changeRoleButtonUser loginFailed" size="1">
										<?php
									}
									?>
									<br/>
									<?php
									if($banned[$key]['role'] == 0) {
										?>
										<input type="text" name="'.$id.'" value="🔒" title="User is banned" class="changeRoleButtonUser loginFailed" size="1">
										<?php
									}
									else {
										?>
										<input type="text" name="'.$id.'" value="✅" title="User isn\'t banned" class="changeRoleButtonUser loginDone" size="1">
										<?php
									}
								?>
							</div>
						</div>
					</div>
				<?php
			}
		?>
	</div>
</div>
<?php
}
?>
<!-- END BANNED MEMBERS -->

<!-- Script to highlight selected div -->
<script>
	$('select').change(function(){
		$('.userManagement_user').css('box-shadow','2px -5px 2px rgba(255,255,255,0.8)');
		$('.userManagement_user').css('border','solid white 2px');
		if($(this).val() > 0) {
			$userID = $(this).val();
			console.log($userID);

			$userDiv = $("div[data-id='" + $userID +"']");
			console.log($userDiv);


			$userDiv.css('box-shadow','2px -5px 2px rgba(255,0,0,0.8)');
			$userDiv.css('border','solid red 2px');
		}	
	});
</script>

