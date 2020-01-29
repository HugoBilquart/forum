<div class="col-sm-12">
	<?php
		if(!isset($_GET['user'])) {
			echo '<script type="text/javascript"> window.location = "index.php?page=home"; </script>';
		}
		else {
			$info = getUserInfoProfile($_GET['user']);
			if($info == 404) {
				echo '<h1 class="page_name text-center text-danger">No user named <u>'.$_GET['user'].'</u> found</h1>';
				
			}
			else {
				if($_SESSION && $_SESSION['userName'] == $_GET['user']) {
					echo '<h1 class="page_name">Your profile</h1>';
					
				}
				else {
					echo '<h1 class="page_name">Profile of '.$_GET['user'].'</h1>';
				}
			}
		}
	?>
</div>
<?php
if($info != 404) {
?>
	<div class="col-sm-6 border" id="global-info">
		<div class="row bg-dark dark info-title">
			<span>Global statistics</span>
		</div>
		<div class="row">
			<div class="col-sm-6 border">
				<img src="<?php echo $info['profile_pic']; ?>" class="profile_user_pic" alt="<?php echo $info['profile_pic']; ?>" id="profile_picture">
			</div>
			<div class="col-sm-6 profile_page border">
				<ul class="list-group">
					<li class="list-group-item">
						<span class="name"><?php echo $info['name']; ?></span>
					</li>
					<li class="list-group-item">
						<span class="role <?php echo strtolower($info['role']).'_role';?>">
							<?php echo $info['role']; ?>
						</span>
					</li>
					<li class="list-group-item detail">
						<span class="personal_page">
							Member since <?php echo $info['registration_date']; ?>
						</span>
					</li>
					<li class="list-group-item detail">
						<span class="personal_page">
							From <?php echo $info['country']; ?>
						</span>
					</li>
					<li class="list-group-item detail">
						<span class="personal_page">
							Birth date : <?php echo $info['birth_date']; ?>
						</span>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="col-sm-1"></div>

	<div class="col-sm-5">
		<div class="row bg-dark info-title border">
			<span>Biography</span>		
		</div>
		<div class="row biography border">
			<div class="col-sm-12">
				<p><?php echo $info['biography']; ?></p>
			</div>		
		</div>
		<br/>

		<div class="row bg-dark info-title border">
			<span>Signature</span>		
		</div>
		<div class="row signature border">
			<div class="col-sm-12">
				<span><?php echo $info['signature']; ?></span>
			</div>	
		</div>		
	</div>

	<?php
		if($_SESSION && $_SESSION['userName'] == $_GET['user']) {
	?>	
			<div class="col-sm-12" id="editProfile">
				<form action="index.php?page=editProfile" method="POST">
					<div class="row">
						<div class="col-sm-12 text-center">
							<p>Edit profile</p>
						</div>
						<div class="col-sm-3">
							<input type="submit" name="submit" class="btn btn-primary" value="Edit profile details">
						</div>
						<div class="col-sm-3">
							<input type="submit" name="submit" class="btn btn-primary" value="Change avatar">
						</div>
						<div class="col-sm-3">
							<input type="submit" name="submit" class="btn btn-danger" value="Edit account informations">
						</div>
						<div class="col-sm-3">
							<input type="submit" name="submit" class="btn btn-danger" value="Change password">
						</div>		
					</div>
				</form>
			</div>
	<?php
		}
}
?>