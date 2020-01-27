<?php
	if($_SESSION) {
		if ($_SESSION['role'] == 2 || $_SESSION['role'] == 3) {
			echo '<h1 class="page_name">User management page</h1>';
			$req_muted = 'SELECT id,name FROM users WHERE role = 1 AND isMuted = 1';

			$req_banned = 'SELECT id,name FROM users WHERE role = 0';

			$req_members = 'SELECT id,name FROM users WHERE role = 1 AND isMuted = 0';
		}
		else {
			echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
		}
	}
	else {
		echo "<script type='text/javascript'> window.location = 'index.php?page=home'; </script>";
	}
?>

<form method="POST">

	<table style="margin: auto;">
		<tr>
			<th class="userAction">
				<select name="memberToMute" class="userAction">
					<option value="">-Select a member-</option>
					<?php displaySelect(0,1); ?>
				</select>
			</th>
			<th class="userAction">
				<select name="memberToUnmute" class="userAction">
					<option value="">-Select a member-</option>
					<?php displaySelect(1,1); ?>
				</select>
			</th>
			<th class="userAction">
				<select name="memberToBan" class="userAction">
					<option value="">-Select a member-</option>
					<?php displaySelectRole(1); ?>
				</select>
			</th>
		</tr>
		<tr>
			<td class="userAction">
				<input type="submit" name="userAction" value="🔇" title="Mute selected member">
			</td>
			<td class="userAction">
				<input type="submit" name="userAction" value="🔊" >
			</td>
			<td class="userAction">
				<input type="submit" name="userAction" value="🔨">
			</td>
		</tr>
	</table>	
</form>

<?php
	if($_POST) {
		if(isset($_POST['userAction'])) {
			switch ($_POST['userAction']) {
				case '🔓':
					$req = 'UPDATE users SET role = 1 WHERE id="'.$_POST['memberToUnban'].'"';
					$results = $connBDD->exec($req);
					break;
				
				case '🔨':
					$req = 'UPDATE users SET role = 0 WHERE id="'.$_POST['memberToBan'].'"';
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
	$nbBanned = countRole(0);
	$nbMember = countRole(1);

	echo '<form method="POST"><table class="userManagement_table"><tr id="memberPart"><th><p>Current members - '.$nbMember.'</p></th></tr></table>';
	fetchMember(1,'userManagement','display');
	if($nbBanned > 0) {
		echo '<table class="userManagement_table"><tr id="bannedPart"><th><p>Banned members - '.$nbBanned.'</p><p><select name="memberToUnban" class="userAction"><option value="">-Select a member-</option>';
		displaySelectRole(0);
		echo '</select><input type="submit" name="userAction" value="🔓"></p></th></tr></table>';				
		fetchMember(0,'userManagement','display');
	}
	
	echo '</form>';
?>
<script type="text/javascript">
	$('select').change(function(){
		$('.userManagement_user').css('box-shadow','2px -5px 2px rgba(255,255,255,0.8)');
		$('.userManagement_user').css('border','solid white 2px');
		if($(this).val() > 0) {
			$userID = $(this).val();

			$('#'+$userID).css('box-shadow','2px -5px 2px rgba(255,0,0,0.8)');
			$('#'+$userID).css('border','solid red 2px');
			console.log('Un select à changé');
			console.log($(this).val());
		}	
	});


</script>