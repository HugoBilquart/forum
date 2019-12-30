<?php
	if($_SESSION) {
		if ($_SESSION['role'] == 'admin') {
			echo '<p class="page_name">Staff management page</p>';
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
				echo '<p style="text-align: center;">User n° '.$_POST['userToPromote'];
				$req = 'UPDATE users SET role = 2 WHERE id="'.$_POST['userToPromote'].'"';
				$results = $connBDD->exec($req);
				if($results) {
					echo " promoted as moderator</p></center>";
				}
			} 
			else if($_POST["modoAction"] == '➖' AND !empty($_POST['moderatorsToDemote'])) {
				echo '<p style="text-align: center;">User n° '.$_POST['moderatorsToDemote'];	
				$req = 'UPDATE users SET role = 1 WHERE id="'.$_POST['moderatorsToDemote'].'"';
				$results = $connBDD->exec($req);
				if($results) {
					echo " demoted</p>";
					email('demote');
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
	<table class="staffManagement_table">
		<tr id="modoPart">
			<th>
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
			</th>
		</tr>
	</table>
	<?php
		fetchMember(2,"staffManagement",'display');
	?>
	<table class="staffManagement_table">
		<tr id="memberPart">
			<th>
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
			</th>
		</tr>
	</table>
	<?php
		fetchMember(1,"staffManagement",'display');
	?>
</form>