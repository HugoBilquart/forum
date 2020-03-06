<?php session_start();
	include('functions.php');
	$connBDD = DBConnection();
	$connex_PDO = DBConnection();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>IT Solutions</title>
		<meta charset="utf-8">
		<link rel="icon" href="images/website_icon.png" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="stylesheet" type="text/css" href="style/style_menu.css">
		<link rel="stylesheet" type="text/css" href="style/style_forum.css">
		<link rel="stylesheet" type="text/css" href="style/style_yourPage.css">
		<link rel="stylesheet" type="text/css" href="style/bootstrap.min.css">
		<script src="script/script.js"></script>
		<script src="script/jquery.js"></script>
		<script src="script/jquery.color.js"></script>
		<script src="script/jquery.color.svg-names.js"></script>
	</head>
	<body>	
		<div class="container-fluid" id="container">
			<!-- TOP OF THE PAGE : LOGO + SESSION DATA -->
			<div class="row" id="top">
				<div class="col-sm-5 logoArea">
					<img src="images/website_logo_edit_bis.png" alt="website-logo">
				</div>
				<div class="col-sm-7 top">
					<?php
						if($_SESSION AND $_GET['page'] == "logout") {
							include 'logout.php';
						} 	
						else if ($_SESSION) {
							?>
							<table id="session_data">
								<tr>
									<td rowspan="3">
										<a href="index.php?page=profile&user=<?php echo $_SESSION['userName'];?>" title="Access to your profile page">
											<img src="<?php echo $_SESSION["pp"]; ?>" alt="<?php echo $_SESSION['userName'];?>'s profile picture" class="user_pic">
										</a>
									</td>
									<td>
										<p><?php echo $_SESSION["userName"]; ?></p>
									</td>
								</tr>
								<tr>
									<td>
										<p><?php echo roleStr($_SESSION['role']); ?></p>
									</td>
								</tr>
								<tr>
									<td>
										<p>
											<a href="index.php?page=logout" id="logout_link">Logout</a>
										</p>
									</td>
								</tr>
							</table>
							<?php
						}
						else {
							include 'login.php';
						} 
					?>
				</div>
			</div>
			<div class="row">
				<?php include 'menu.php'; ?>
			</div>

			<div class="row" id="content">
				<?php
					if (isset($_GET['page'])) {
						switch ($_GET['page']) {
							case 'register':
								include("register.php");
								break;
							case 'yourPage':
								include("yourPage.php");
								break;

							case 'forum':
								include("forum.php");
								break;

							case 'topic':
								include("topic.php");
								break;

							case 'newTopic':
								include("newTopic.php");
								break;

							case 'memberList':
								include("memberlist.php");
								break;

							case 'editProfile':
								include("editProfile.php");
								break;

							case 'profile':
								include("profile.php");
								break;

							case 'forumManagement':
								include("forumManagement.php");
								break;

							case 'userManagement':
								include("userManagement.php");
								break;

							case 'staffManagement':
								include("staffManagement.php");
								break;
							default:
								include('homepage.php');
								break;
						}
					}
					else {
						echo '<script type="text/javascript"> window.location = "index.php?page=home"; </script>';
					}
				?>
			</div>

			<div class="row">
				<div class="col-sm-12" id="footer">
					<span class="footer_title">IT Solutions - Created and Designed by Hugo BILQUART</span>
					<br/>
					<span class="footer_title">© - IT Solutions™ - v4</span>
				</div>
			</div>
			
		</div>
		<script src="script/menu.js"></script>
	</body>
</html>