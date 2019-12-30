
<p>IT Solutions's goals</p>
<ul>
	<li>Share knowledges</li>
	<li>Help users whatever their technical level</li>
</ul>

<table>
	<tr>
		<td>
			<?php
				echo '<table class="lastThing_table"><tr><th colspan="2" class="lastThing_thing">Last user registered</th></tr><tr><td class="lastThing_top">';
				echo '<table class="lastThing_table"><tr><td class="lastThing_top">N°</td><td class="lastThing_top">Nickname</td><td class="lastThing_top">On</td></tr>
				<tr><td class="lastThing_info">'.$line['id'].'</td><td class="lastThing_info"><a href="index.php?page=profile&user='.$line['name'].'" class="lastUserLink" title="Profile of '.$line['name'].'">'.$line['name'].'</a></td><td class="lastThing_info">'.$line['registration_date'].'</td></tr></table></td></tr></table></td>';
			?>
		</td>

		<td>
			<?php
				echo '<table class="lastThing_table"><tr><th colspan="2" class="lastThing_thing">Last topic</th></tr><tr><td class="lastThing_top">';
				echo '<table class="lastThing_table"><tr><td class="lastThing_top">N°</td><td class="lastThing_top">Title</td><td class="lastThing_top">Theme</td><td class="lastThing_top">By</td><td class="lastThing_top">On</td></tr>
				<tr><td class="lastThing_info">'.$line_last_topic['id'].'</td><td class="lastThing_info"><a href="index.php?page=topic&value='.$line_last_topic['id'].'" class="lastUserLink">'.$line_last_topic['topic_name'].'</a></td><td class="lastThing_info"><a href="index.php?page=forum&category='.$line_last_topic['theme'].'" class="lastUserLink">'.$line_last_topic['theme'].'</td><td class="lastThing_info"><a href="index.php?page=profile&user='.$line_last_topic['topic_owner'].'" class="lastUserLink" title="Profile of '.$line_last_topic['topic_owner'].'">'.$line_last_topic['topic_owner'].'</a></td><td class="lastThing_info">'.$line['registration_date'].'</td></tr></table></td></tr></table>';


			?>
		</td>
	</tr>
</table>

<style type="text/css">
	table.lastThing_table {
		border:	solid white 2px;
	}

	th.lastThing_thing {
		border:	solid white 1px;
	}

	td.lastThing_top {
		margin: auto;
		text-align: center;
		background-color: #009996;
	}
	td.lastThing_info {
		background-color: cyan;
		padding: 5px;
	}

	a.lastUserLink {
		color:						white;
		text-decoration:			none;
		transition-duration:		0.7s;
	}

	a.lastUserLink:hover {
		text-shadow: 				1px 3px 3px white;  
	}

</style>