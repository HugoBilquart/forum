<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="index.php?page=home" id="home-icon">
        <img src="images/website_icon.png" alt="IT Solutions Logo">
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
                                        <a class="nav-link dropdown-item" href="index.php?page=userManagement">User management</a>
                                <?php

                                if($_SESSION['role'] == 3) {
                                ?>
                                    <a class="nav-link dropdown-item" href="index.php?page=staffManagement">Staff management</a>
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