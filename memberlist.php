<h1 class="page_name">List of Members</h1>
<?php
	$connBDD = DBConnection();

	$req = 'SELECT id,name,role,profile_pic,registration_date FROM users WHERE role != 0';
	if(isset($_GET['orderby']) || isset($_GET['desc'])) {
		if(isset($_GET['orderby']) && isset($_GET['desc'])) {
			if($_GET['orderby'] == "id" || $_GET['orderby'] == "name" || $_GET['orderby'] == "role") {
				$req = $req.' ORDER BY '.$_GET['orderby'].' COLLATE NOCASE DESC';
			}
		}
		else if(isset($_GET['orderby'])) {
			if($_GET['orderby'] == "id" || $_GET['orderby'] == "name" || $_GET['orderby'] == "role") {
				$req = $req.' ORDER BY '.$_GET['orderby'].' COLLATE NOCASE';
			}
		}
	}


	/*if(isset($_GET['orderby']) || isset($_GET['desc'])) {
		if(isset($_GET['orderby']) && isset($_GET['desc'])) {
			if($_GET['orderby'] != "id" && $_GET['orderby'] != "name" && $_GET['orderby'] != "role") {
				$req = 'SELECT id,name,role,profile_pic,registration_date FROM users WHERE role != 0';
			}
			else {
				$req = 'SELECT id,name,role,profile_pic,registration_date FROM users WHERE role != 0 ORDER BY '.$_GET['orderby'].' DESC';
			}
		}
		else if(isset($_GET['orderby'])) {
			if($_GET['orderby'] != "id" && $_GET['orderby'] != "name" && $_GET['orderby'] != "role") {
				$req = 'SELECT id,name,role,profile_pic,registration_date FROM users WHERE role != 0';		
			}
			else {	
				$req = 'SELECT id,name,role,profile_pic,registration_date FROM users WHERE role != 0 ORDER BY '.$_GET['orderby'];
			}
		}
		else {
			$req = 'SELECT id,name,role,profile_pic,registration_date FROM users WHERE role != 0';
		}
	}
	else {
		$req = 'SELECT id,name,role,profile_pic,registration_date FROM users WHERE role != 0';
	}*/
?>

<table class="table table-dark table-striped table_memberlist">
	<thead>
		<tr>
			<th scope="col"><a href="<?php echo redirectURL('id')?>" title="Order by ID" class="filterLink"><?php echo memberlistIndex('id'); ?></a></th>
			<th scope="col"><a href="<?php echo redirectURL('name')?>" title="Order by name" class="filterLink"><?php echo ucfirst(memberlistIndex('name')); ?></a></th>
			<th scope="col"><a href="<?php echo redirectURL('role')?>" title="Order by role" class="filterLink"><?php echo ucfirst(memberlistIndex('role')); ?></a></th>
			<th scope="col">Profile Picture</th>
			<th scope="col">Registration Date</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$results = $connBDD->query($req);
			if($results) {
				$members = $results->fetchAll(PDO::FETCH_ASSOC);
				foreach ($members as $key => $value) {	
					echo "<tr>";
					foreach ($members[$key] as $keys => $values) {
						switch ($keys) {
							case "id" :
								?>
									<th scope="row">
										<?php echo $members[$key][$keys]; ?>
									</th>
								<?php
								break;

							case "name" :
								?>
									<td class="index_table_memberlist name">
										<a href="index.php?page=profile&user=<?php echo $members[$key][$keys]; ?>" class="memberlist_namelink" title="Profile of <?php echo $members[$key][$keys]; ?>">
											<?php echo $members[$key][$keys]; ?>
										</a>
									</td>
								<?php
								break;

							case "profile_pic" :
								?>
									<td class="index_table_memberlist">
										<a href="index.php?page=profile&user=<?php echo $members[$key]['name']; ?>" title="Profile of <?php echo $members[$key]['name']; ?>">
											<img src="<?php echo $members[$key][$keys]; ?>" alt="<?php echo $members[$key][$keys]; ?>" class="memberlist_picture">
										</a>
									</td>
								<?php
								break;

							case "role" :
								switch ($values) {
									case '1':
										?>
											<td class="index_table_memberlist role"><span class="member_role">Member</span></td>
										<?php
										break;
									
									case '2':
										?>
											<td class="index_table_memberlist role"><span class="moderator_role">Moderator</span></td>
										<?php
										break;
		
									case '3':
										?>
											<td class="index_table_memberlist role"><span class="admin_role">Admin</span></td>
										<?php
										break;
								}
								break;

							default:
								?>
									<td class="index_table_memberlist <?php echo $members[$key][$keys]; ?>"><?php echo $members[$key][$keys]; ?></td>
								<?php
								break;
						}
					}
				}
			}
		?>
	</tbody>
</table>


<?php
	function redirectURL($filter) {
		if(isset($_GET['orderby'])) {
            if(isset($_GET['desc'])) {
                if($_GET['orderby'] == $filter) {
                    return 'index.php?page=memberList&orderby='.$filter;
                }
                else {
                    return 'index.php?page=memberList&orderby='.$filter;
                }
            }
            else if ($_GET['orderby'] == $filter) {
                return 'index.php?page=memberList&orderby='.$filter.'&desc';
            }
            else {
                return 'index.php?page=memberList&orderby='.$filter;
            }
        }
        else {
            return 'index.php?page=memberList&orderby='.$filter;
        } 
	}

	function memberlistIndex($indexName) {
		if(isset($_GET['orderby'])) {
			$arrow = '▼';
			if(isset($_GET['desc'])) {
				$arrow = '▲';
			}
		
			if($indexName == $_GET['orderby']) {
				if($indexName == 'id') {
					return "# ".$arrow;
				}
				else {
					return $indexName.' '.$arrow;
				}
			}
			else if($indexName == 'id') {
				return '#';
			}
			else {
				return $indexName;
			}
		}
		else {
			if($indexName == 'id') {
				return "# ▼";
			}
			else {
				return $indexName;
			}
		}
	}
?>