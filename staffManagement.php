<div class="col-md-12" id="staffManagement">
<?php
	if($_SESSION) {
		if ($_SESSION['role'] == 3) {
			?>
				<h1 class="page_name">Staff management page</h1>
			<?php
		}
		else {
			echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
		}
	}
	else {
		echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
	} 	

		if($_POST) {
			?>
				<p class="page_name">Processing result - 
			<?php
			if($_POST["modoAction"] == '➕' AND !empty($_POST['userToPromote'])) {
				
				$userToPromoteParts = explode('|',$_POST['userToPromote']);
				?>
				<?php echo $userToPromoteParts[1]; ?>
				<?php
				$req = 'UPDATE users SET role = 2 WHERE id="'.$userToPromoteParts[0].'"';
				$results = $connBDD->exec($req);
				if($results) {
					?>
						promoted as <span class="moderator_role">moderator</span></p>
					<?php
				}
			} 
			else if($_POST["modoAction"] == '➖' AND !empty($_POST['moderatorsToDemote'])) {
				$moderatorsToDemoteParts = explode('|',$_POST['moderatorsToDemote']);
				?>
				<?php echo $moderatorsToDemoteParts[1]; ?>	
				<?php
				$req = 'UPDATE users SET role = 1 WHERE id="'.$moderatorsToDemoteParts[0].'"';
				$results = $connBDD->exec($req);
				if($results) {
					?> 
						demoted</p>
					<?php
				}
			}
				
		}
		$req_moderators = 'SELECT id,name FROM users WHERE role = 2';
		$req_users = 'SELECT id,name FROM users WHERE role = 1';
		$results_moderators = $connBDD->query($req_moderators);
		$results_users = $connBDD->query($req_users);

		$tab_moderators = $results_moderators->fetchAll(PDO::FETCH_ASSOC);
		$tab_users = $results_users->fetchAll(PDO::FETCH_ASSOC);

		$nbMember = countRole(1);
		$nbModo = countRole(2);
?>

<form method="POST" class="staffManagement_form">
	<div class="row">
		<div class="col-md-12" id="modoPart">
			<p><label for="moderatorToDemote">Current moderators - <?php echo $nbModo; ?></label></p>
			<p>	<select name="moderatorsToDemote" id="moderatorToDemote">
					<option value="">---Select a moderator---</option>
					<?php
						foreach ($tab_moderators as $key => $value) {
							echo '<option value="'.$tab_moderators[$key]['id'].'|'.$tab_moderators[$key]['name'].'">'.$tab_moderators[$key]['id'].' | '.$tab_moderators[$key]['name'].'</option>';
						}
					?>
				</select>
				<input type="submit" name="modoAction" value="➖" title="Demote selected moderator">
			</p>
		</div>
		<div class="col-md-12" id="moderators">
			<div class="row">
				<?php
					$results = $connBDD->query("SELECT id,name,role,profile_pic FROM users WHERE role=2 ORDER BY name COLLATE NOCASE");
					$moderators = $results->fetchAll(PDO::FETCH_ASSOC);
					foreach ($moderators as $key => $value) {
						?>
							<div class="col-sm-3 moderator-user">
								<div class="row border">
									<div class="col-sm-4 border staff-management-pp-area">
										<a href="index.php?page=profile&user=<?php echo $moderators[$key]['name']; ?>" title="Profile page of <?php echo $moderators[$key]['name']; ?>">
											<img class="staffManagement_pp" src="<?php echo $moderators[$key]['profile_pic']; ?>">
										</a>
									</div>
									<div class="col-sm-8">
										<a href="index.php?page=profile&user=<?php echo $moderators[$key]['name']; ?>" title="Profile page of <?php echo $moderators[$key]['name']; ?>">
											<span><?php echo $moderators[$key]['name']; ?> [ #<?php echo $moderators[$key]['id']; ?> ]</span>
										</a>
										<li><span><?php echo roleINT_CHAR($moderators[$key]['role']); ?></span></li>
									</div>
								</div>
							</div>
						<?php
					}
				?>
			</div>
		</div>

		<div class="col-md-12" id="memberPart">
			<p>Current members - <?php echo $nbMember; ?></p>
			<p>	<select name="userToPromote">
					<option value="">---Select a user---</option>
					<?php
						foreach ($tab_users as $key => $value) {
							echo '<option value="'.$tab_users[$key]['id'].'|'.$tab_users[$key]['name'].'">'.$tab_users[$key]['id'].' | '.$tab_users[$key]['name'].'</option>';
						}
					?>
				</select>
				<input type="submit" name="modoAction" value="➕" title="Promote selected user">
			</p>
		</div>

		<div class="col-md-12" id="members">
			<div class="row">
				<?php
					$results = $connBDD->query("SELECT id,name,role,profile_pic FROM users WHERE role=1 ORDER BY name COLLATE NOCASE");
					$members = $results->fetchAll(PDO::FETCH_ASSOC);
					foreach ($members as $key => $value) {
						?>
							<div class="col-sm-3 member-user">
								<div class="row border">
									<div class="col-sm-4 border staff-management-pp-area">
										<a href="index.php?page=profile&user=<?php echo $members[$key]['name']; ?>" title="Profile page of <?php echo $members[$key]['name']; ?>">
											<img class="staffManagement_pp" src="<?php echo $members[$key]['profile_pic']; ?>">
										</a>
									</div>
									<div class="col-sm-8">
										<a href="index.php?page=profile&user=<?php echo $members[$key]['name']; ?>" title="Profile page of <?php echo $members[$key]['name']; ?>">
											<span><?php echo $members[$key]['name']; ?> [ #<?php echo $members[$key]['id']; ?> ]</span>
										</a>
										<li><span><?php echo roleINT_CHAR($members[$key]['role']); ?></span></li>
									</div>
								</div>
							</div>
						<?php
					}
				?>
			</div>
		</div>
	</div>
</form>
</div>