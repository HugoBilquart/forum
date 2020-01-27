<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="index.php?page=home">
        Home
    </a>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <?php
                if($_SESSION) {
            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=profile&user=<?php echo $_SESSION['userName']; ?>">Your page</a>
                    </li>
            <?php
                }
            ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="index.php?page=forum&category=home" id="navbarDropdown1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Forum
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                    <a class="dropdown-item" href="index.php?page=forum&category=rules">Rules</a>
                    <a class="dropdown-item" href="index.php?page=forum&category=announce">Announce</a>
                    <?php
                        if($_SESSION) {
                            if($_SESSION['role'] == 3 OR $_SESSION['role'] == 2) {
                                ?>
                                    <a class="dropdown-item" href="index.php?page=forumManagement">Forum management</a>
                                <?php
                            }
                        }
                    ?>
                    <div class="dropdown-divider">Some categories</div>
                    <a class="dropdown-item" href="index.php?page=forum&category=network">Network</a>
                    <a class="dropdown-item" href="index.php?page=forum&category=web">Web</a>
                    <a class="dropdown-item" href="index.php?page=forum&category=software">Software</a>
                </div>
            </li>
                    
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="index.php?page=memberList" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Members
                </a>
                
                    <?php
                        if($_SESSION) {
                            if($_SESSION['role'] == 3 OR $_SESSION['role'] == 2) {
                                ?>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                                        <a class="dropdown-item" href="index.php?page=forumManagement">Forum management</a>
                                <?php

                                if($_SESSION['role'] == 3) {
                                ?>
                                    <a class="dropdown-item" href="index.php?page=staffManagement">Staff management</a>
                                <?php
                                }
                                ?>
                                    </div>
                                <?php
                            }
                        }
                    ?>
                
            </li>
        </ul>
    </div>
</nav>





<!--dl class="indexTabMenu">
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
</dl-->

<!--dl class="indexTabMenu">
    <dt>
        <a href="index.php?page=forum&category=home" class="liensTabMenu">
            Forum
        </a>
    </dt>
    <?php
        /*if($_SESSION) {
            if($_SESSION['role'] == 'admin' OR $_SESSION['role'] == 'moderator') {
                echo '<dd><a href="index.php?page=forumManagement" class="liensTabMenu">Forum management</a></dd>';
            }
        }*/
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
</dl-->

<!--dl class="indexTabMenu">
    <dt>
        <a href="index.php?page=memberList" class="liensTabMenu">
            Members
        </a>
    </dt>
    <dd></dd>
    <?php 
        /*if($_SESSION) {
            if($_SESSION['role'] == 2 || $_SESSION['role'] == 3) {
                echo '<dd><a href="index.php?page=userManagement" class="liensTabMenu">User Management</a></dd>';
                if($_SESSION['role'] == 3) {
                    echo '<dd><a href="index.php?page=staffManagement" class="liensTabMenu">Staff Management</a></dd>';
                }
            }
        }*/
    ?>
</dl>

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


</script-->