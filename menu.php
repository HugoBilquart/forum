<td colspan="5">
    <div id="bandeauMenu">
        <dl class="indexTabMenu">
        	<dt>
                <a href="index.php?page=home" class="liensTabMenu"> 
                    Homepage
                </a>
        	</dt>
        </dl>

        <dl class="indexTabMenu">
        	<dt>
    		    <a href="index.php?page=yourPage" class="liensTabMenu"> 
                    Your page 
                </a>
        	</dt>
        </dl>

        <dl class="indexTabMenu">
        	<dt>
        		<a href="index.php?page=forum&category=home" class="liensTabMenu">
            		Forum
          		</a>
          	</dt>
            <?php
                if($_SESSION) {
                    if($_SESSION['role'] == 'admin' OR $_SESSION['role'] == 'moderator') {
                        echo '<dd><a href="index.php?page=forumManagement" class="liensTabMenu">Forum management</a></dd>';
                    }
                }
            ?>
            <dd>
                <a href="index.php?page=forum&category=announce" class="liensTabMenu">
                    Announces
                </a>
            </dd>
        	<dd>
        		<a href="index.php?page=forum&category=rules" class="liensTabMenu">
            		Rules
          		</a>
          	</dd>
        	<dd>
        		<a href="index.php?page=forum&category=network" class="liensTabMenu">
            		Network
          		</a>
          	</dd>
        	<dd>
                <a href="index.php?page=forum&category=web" class="liensTabMenu">
                    Web
                </a>
            </dd>
        	<dd>
                <a href="index.php?page=forum&category=software" class="liensTabMenu">
                    Software
                </a>   
            </dd>
        </dl>

        <dl class="indexTabMenu">
        	<dt>
        		<a href="index.php?page=memberList" class="liensTabMenu">
            		Members
          		</a>
        	</dt>
            <dd></dd>
            <?php 
                if($_SESSION) {
                    if($_SESSION['role'] == 'moderator' || $_SESSION['role'] == 'admin') {
                        echo '<dd><a href="index.php?page=userManagement" class="liensTabMenu">User Management</a></dd>';
                        if($_SESSION['role'] == 'admin') {
                            echo '<dd><a href="index.php?page=staffManagement" class="liensTabMenu">Staff Management</a></dd>';
                        }
                    }
                }
            ?>
        </dl>
    </div>
</td>

<script>
    
    $('.indexTabMenu').hover(function(){
        $(this).css('background','linear-gradient(to right top, #212121, red)');
    });

    $('.indexTabMenu').mouseleave(function(){
        $(this).css('background','linear-gradient(to right top, black, red)');
    });

    $('.indexTabMenu.dd').hover(function(){
        $(this).css('background','linear-gradient(to right top, #212121, red)');
    });


</script>