<p class="page_name">List of Members</p>
<?php
	$connBDD = DBConnection();
	if(isset($_GET['orderby']) || isset($_GET['desc'])) {
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
	}
	
	$results = $connBDD->query($req);
	if($results) {
		$tab = $results->fetchAll(PDO::FETCH_ASSOC);
		echo '<table class="table_memberlist"><tr><th class="top_table_memberlist"><a href="'.redirectURL('id').'" title="Order by ID" class="filterLink">N°</a></th><th class="top_table_memberlist"><a href="'.redirectURL('name').'" title="Order by name" class="filterLink">Name</a></th><th class="top_table_memberlist"><a href="'.redirectURL('role').'" title="Order by role" class="filterLink">Role</a></th><th class="top_table_memberlist">Profile Picture</th><th class="top_table_memberlist">Registration Date</th></tr>';
			foreach ($tab as $key => $value) {
				echo "<tr>";
				foreach ($tab[$key] as $keys => $values) {
					if($keys == "profile_pic") {
						echo '<td class="index_table_memberlist"><a href="index.php?page=profile&user='.$tab[$key]['name'].'" title="Profile of '.$tab[$key]['name'].'"><img src="'.$tab[$key][$keys].'" alt="'.$tab[$key][$keys].'" class="memberlist_picture"></a></td>';
					}
					else if($keys == "name") {
						echo '<td class="index_table_memberlist name"><a href="index.php?page=profile&user='.$tab[$key][$keys].'" class="memberlist_namelink" title="Profile of '.$tab[$key][$keys].'">'.$tab[$key][$keys].'</a></td>';
					}
					else if($keys == "role") {
						switch ($values) {
							case '1':
								echo '<td class="index_table_memberlist role"><span class="member_role">Member</span></td>';
								break;
							
							case '2':
								echo '<td class="index_table_memberlist role"><span class="moderator_role">Moderator</span></td>';
								break;

							case '3':
								echo '<td class="index_table_memberlist role"><span class="admin_role">Admin</span></td>';
								break;

							default:
								echo '<td class="index_table_memberlist role">'.$tab[$key][$keys].'</td>';
								break;
						}
					}
					else {
						echo '<td class="index_table_memberlist '.$tab[$key][$keys].'">'.$tab[$key][$keys].'</td>';
					}
				}
				echo "</tr>";
			}
		echo "</table>";
	}

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

?>