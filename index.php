<?php session_start();
	include('functions.php');
	$connBDD = DBConnection();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>IT Solutions</title>
		<meta charset="utf-8">
		<link rel="icon" href="images/icons/website_icon.png" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="stylesheet" type="text/css" href="style/style_menu.css">
		<link rel="stylesheet" type="text/css" href="style/style_yourPage.css">
		<script src="script/script.js"></script>
		<script src="script/jquery.js"></script>
		<script src="script/jquery.color.js"></script>
		<script src="script/jquery.color.svg-names.js"></script>
	</head>
	<body>
		<table id="table">
			<tr id="top">
				<th colspan="1" class="logoArea"></th>
				<th colspan="5" class="top">
					<?php 
						echo '<p class="top">'.date('d F Y').'</p>';
						if ($_SESSION) {
							echo '<p class="top">Welcome '.$_SESSION['userName'].'</p>';	
							echo '<p class="top">Connected since '.$_SESSION['start'].'</p>';
						}
					?>
				</th>	
			</tr>

			<tr>
			    <td colspan="1">
			    	<?php
			    		if($_SESSION AND $_GET['page'] == "logout") {
			    			include 'logout.php';
			    		} 	
			    		else if ($_SESSION) {
			    			showRole($_SESSION['role']);
			    			echo '<table id="session_data"><tr><td rowspan="3"><a href="index.php?page=yourPage" title="Personal page of '.$_SESSION['userName'].'"><img src="'.$_SESSION["pp"].'" alt="'.$_SESSION['userName'].'\'s profile picture" class="user_pic"></a></td>';
			    			echo '<td><p>'.$_SESSION["userName"].'</p></td></tr>';
			    			echo '<tr><td><p>Role : '.$_SESSION['role'].'</p></td></tr>';
			    			echo '<tr><td><p><a href="index.php?page=logout" id="logout_link">Logout</a></p></td></tr></table>';
			    		}
			    		else {
			    			include 'login.php';
			    		} 
			    	?>
			    </td>
			    <?php include 'menu.php'; ?>
			</tr>
			<tr>    
			    <td colspan="6" id="content">
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
			    </td>
			</tr>
			<tr>
			    <td colspan="6">
			    	<footer id="footer">
						<span class="footer_title">IT Solutions - Created and Designed by Hugo BILQUART</span>
						<br/>
						<span class="footer_title">© - IT Solutions™ - v3</span>
					</footer>
			    </td>
			</tr>
		</table>
	</body>
</html>