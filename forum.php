<!-- TITLE -->
<div class="col-md-12">
	<h1 class="page_name">Welcome on the forum</h1>
</div>

<!-- TITLE SECONDARY -->
<div class="col_md-12" id="forum-home-top">
	<?php
		if($_POST) {
			if(isset($_POST['search'])) {
				echo '<h2><a class="forum_welcome"><u>SEARCH</u> -> '.$_POST["search"].'</a></h2>';
			}
		}
		else if(isset($_GET['category'])) {
			echo '<h2><a class="forum_welcome">'.$_GET["category"].'</a></h2>';
		}
		else {
			echo '<h2><a class="forum_welcome">Topic list</a></h2>';
		}
	?>
	
	<hr>

	<?php 
		if($_SESSION AND $_SESSION['isMuted'] == 0) { 
			?>
				<p>
					<a href="index.php?page=newTopic" class="topic_link">
						<img src="images/folders/add.png" class="topic_folder">
						<span>Create a topic</span>
					</a>
				</p>
			<?php
		}
	?>
	<form method="POST" action="index.php?page=forum">
		<p class="forum_search">Specific research (keywords, topic) --> 
			<input type="text" name="search">
			<input type="submit" name="" value="🔍">
		</p>
	</form>
</div>
<!-- END TITLE SECONDARY -->

<!-- CONTENT -->
<div class="col-md-12">
<?php
	$request = 'SELECT * FROM topics ';
	$title = "Every topics";
	if($_POST) {
		if(isset($_POST['search'])) {
			$request = $request. 'WHERE theme LIKE "%'.$_POST['search'].'%" OR topic_name LIKE "%'.$_POST['search'].'%" ORDER BY creation_date DESC';
			$title = $_POST['search'];
		}
	}
	else if(isset($_GET['category'])) {
		if($_GET['category'] == 'home') {
			$request = $request. 'WHERE theme = "rules" OR theme = "announce" OR theme = "changelogs" ORDER BY theme DESC';
			$title = "Home";
		}
		else {
			$request = $request. 'WHERE theme LIKE "%'.$_GET['category'].'%" ORDER BY creation_date DESC';
			$title = $_GET['category'];
		}
	}
	else {
		$request = $request.'ORDER BY creation_date DESC';
	}

	$response = $connex_PDO->query($request);
	$articles = $response->fetchAll();
	if(empty($articles)) {
		?>
			<p class="failed text-center">No topic found</p>
		<?php
	}
	else {
		?>
			<!-- INFO TITLE SECONDARY -->
			<div class="row" id="category-info">
				<div class="col-sm-4"><p><?php echo ucfirst($title); ?></p></div>
				<div class="col-sm-4"><p>Number of articles : <?php echo count($articles); ?></p></div>
				<div class="col-sm-4"><p>Last article : <?php echo date('d/m/Y',strtotime($articles[count($articles) - 1]['creation_date'])); ?></p></div>
			</div>
			<!-- END INFO TITLE SECONDARY -->

			<br/>

			<!-- TOPICS -->
			<div class="row">
				<div class="col-sm-11" id="topics">
					<table>
						<thead>
							<tr>
								<th scope="col"><p></p></th>
								<th scope="col"><p>Topic name</p></th>
								<th scope="col"><p>Number of message</p></th>
								<th scope="col"><p>Last message</p></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($articles as $key => $value) {
									$countDay = date_diff(date_create(date('Y-m-d')),date_create($articles[$key]['creation_date']))->format('%d');
									if($countDay > 7) {
										if($articles[$key]['complete'] == 0) {
											$src_folder = "default";
										}
										else {
											$src_folder = "complete";
										}
									}
									else {
										$src_folder = "new";
									}
									?>
									<!-- ARTICLE -->
									<tr data-id="<?php echo $articles[$key]['id']; ?>">
										<th scope="row" class="folder-area"><img class="topic_folder" src="images/folders/<?php echo $src_folder; ?>.png" alt="icon-folder"></th>
										<td class="topic-name">
											<?php
												if(isset($_GET['category']) OR isset($_POST['search'])) {
													?>
													<span><?php echo $articles[$key]['topic_name']; ?></span>
													<?php
												}
												else {
													?>
													<span><?php echo $articles[$key]['topic_name'].' <i>['.$articles[$key]['theme'].']</i>'; ?></span>
													<?php
												}
											?>
										</td>
										<td><span><?php echo $articles[$key]['nb_message']; ?></span></td>
										<td>
											<?php
												$req_last_msg = $connex_PDO->query('SELECT publish_date FROM messages WHERE id_topic ='.$articles[$key]['id'].' ORDER BY publish_date DESC LIMIT 1');
												$last_msg = $req_last_msg->fetch();
											?>
											<span><?php echo date('d/m/Y H:i',strtotime($last_msg[0])); ?></span>
										</td>
									</tr>
									<!-- END ARTICLE -->
									<?php
								}
							?>
						</tbody>
					</table>
				
		
				</div>
			</div>
			<!-- END TOPICS -->
		<?php
	}
?>
</div>
<!-- END CONTENT -->

<script>
	//Trigger tr as a href
	$('#topics > table > tbody > tr').click(function() {
		console.log('POP');
		window.location.href = 'index.php?page=topic&value=' + $(this).data("id");
	});
</script>
