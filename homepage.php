<h1 class="page_name">Welcome on IT Solutions</h1>
<hr>

<h2>Here're some informations about the forum</h2>

<div class="col-sm-12">
	<?php $info = lastMessage(); ?>
	<table class="table table-dark table-bordered homepage-table">
		<thead>
			<tr>
				<th scope="col">
					<span>Last post ✉ :</span>
					<span>
						<?php echo $info['publish_date']; ?>
					</span>
				</th>
			</tr>
			<tr>
				<th scope="row">
					
					<a class="homepage-link" href="index.php?page=forum&category=<?php echo $info['theme']; ?>">
						<?php echo $info["theme"]; ?>
					</a> 
					<b> > </b> 
					<a class="homepage-link" href="index.php?page=topic&value=<?php echo $info['id']; ?>">
						<?php echo $info["topic_name"]; ?>
					</a>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<img src="<?php echo $info['profile_pic']; ?>" class="lastRegistered_pp" alt="<?php echo $info['profile_pic']; ?>">
					<span><?php echo $info['name']; ?></span>
					<p><?php echo $info["content"]; ?></p>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="col-sm-6">
	<table class="table table-dark table-bordered homepage-table">
		<thead>
			<tr>
				<th scope="row">
					Birthday 🎂
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<p>Today is birthday of <?php birthday(); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="col-sm-6">
	<?php $info = lastRegistered(); ?>
	<table class="table table-dark table-bordered homepage-table">
		<thead>
			<tr>
				<th scope="row">
					Last registered member : <?php echo $info["registration_date"]; ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<img src="<?php echo $info['profile_pic']; ?>" class="lastRegistered_pp" alt="<?php echo $info['profile_pic']; ?>">
					<span>
						<a class="homepage-link" href="index.php?page=profile&user=<?php echo $info['name']; ?>">	
							<?php echo $info['name']; ?>
						</a>
					</span>
				</td>
			</tr>
		</tbody>
	</table>
</div>
