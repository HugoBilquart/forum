<p class="forum_welcome">Welcome on IT Solutions</p>
<hr>

<table style="margin: auto; width: 90%;">
	<tr>
		<td style="padding-top: 2%; padding-bottom: 2%;" colspan="2">
			<table class="homepage_table">
				<tr>
					<th>
						Last post ✉
					</th>
				</tr>
				<tr>
					<td>
						<?php $info = lastMessage(); ?>
						<table width="100%">
							<tr>
								<?php echo '<td colspan="3"><a href="index.php?page=forum&category='.$info['theme'].'">'.$info["theme"].'</a> > <a href="index.php?page=topic&value='.$info['id'].'">'.$info["topic_name"].'</a></td>'; ?>	
							</tr>
							<tr>
								<?php
								echo '	<td class="homepage_pp_area" colspan="1">
											<a href="index.php?page=profile&user='.$info["name"].'" title="View profile of '.$info["name"].'"><img src="'.$info['profile_pic'].'" class="lastRegistered_pp" alt="'.$info['profile_pic'].'"></a>
											<span>'.$info['name'].'</span>
										</td>';
								if(strlen($info["content"]) > 50) {
									$info["content"] = substr($info["content"],0,50);
									echo '<td colspan="2"><p>'.$info["content"].'...<a href="index.php?page=topic&value='.$info['id'].'" title="View full message in topic">[Read more]</a></p></td>';
								}
								else {
									echo '<td colspan="2"><p>'.$info["content"].'</p></td>';
								}
								?>
							</tr>
							<tr>
								<?php echo '<th colspan="2">'.$info['publish_date'].'</th>'; ?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>
		<td style="padding-top: 2%; padding-bottom: 2%;">
			<table class="homepage_table">
				<tr>
					<th>
						Birthday 🎂
					</th>
				</tr>
				<tr>
					<td>
						<p>Today is birthday of <?php birthday(); ?>
					</td>
				</tr>
			</table>
		</td>

		<td style="padding-top: 2%; padding-bottom: 2%;">
			<table class="homepage_table">
				<tr>
					<th>
						Last registered member
					</th>
				</tr>
				<tr>
					<td>
						<?php lastRegistered(); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
