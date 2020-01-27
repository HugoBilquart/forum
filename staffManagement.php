<div class="col-md-12">
<?php
	if($_SESSION) {
		if ($_SESSION['role'] == 3) {
			?>
				<h1 class="page_name">Staff management page</h1>
				<p class="page_name">Resultat du traitement</p>
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
			if($_POST["modoAction"] == '➕' AND !empty($_POST['userToPromote'])) {
				?>
				<p class="page_name">User n° <?php echo $_POST['userToPromote']; ?>
				<?php
				$req = 'UPDATE users SET role = 2 WHERE id="'.$_POST['userToPromote'].'"';
				$results = $connBDD->exec($req);
				if($results) {
					?>
						promoted as moderator</p>
					<?php
				}
			} 
			else if($_POST["modoAction"] == '➖' AND !empty($_POST['moderatorsToDemote'])) {
				?>
				<p class="page_name">User n° <?php echo $_POST['moderatorsToDemote']; ?>	
				<?php
				$req = 'UPDATE users SET role = 1 WHERE id="'.$_POST['moderatorsToDemote'].'"';
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
			<p>Current moderators - <?php echo $nbModo; ?></p>
			<p>	<select name="moderatorsToDemote">
					<option value="">---Select a moderator---</option>
					<?php
						foreach ($tab_moderators as $key => $value) {
							echo '<option value="'.$tab_moderators[$key]['id'].'">'.$tab_moderators[$key]['id'].' | '.$tab_moderators[$key]['name'].'</option>';
						}
					?>
				</select>
				<input type="submit" name="modoAction" value="➖" title="Demote selected moderator">
			</p>
		</div>
		<?php
			fetchMember(2,"staffManagement",'display');
		?>

		<div class="col-md-12" id="memberPart">
			<p>Current members - <?php echo $nbMember; ?></p>
			<p>	<select name="userToPromote">
					<option value="">---Select a user---</option>
					<?php
						foreach ($tab_users as $key => $value) {
							echo '<option value="'.$tab_users[$key]['id'].'">'.$tab_users[$key]['id'].' | '.$tab_users[$key]['name'].'</option>';
						}
					?>
				</select>
				<input type="submit" name="modoAction" value="➕" title="Promote selected user">
			</p>
		</div>
		<?php
			fetchMember(1,"staffManagement",'display');
		?>

	</div>
</form>
</div>