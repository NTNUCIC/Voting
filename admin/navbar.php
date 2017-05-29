   <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.php">
					NTNUCIC - 投票系統後台
                </a>
            </div>

<?php
require_once("../autoload.php");
if(BLL\AdminBLL::isLogIn()) {
echo '
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav">
    				<li><a href="main.php">首頁</a></li>
                </ul>
                <ul class="nav navbar-nav">
    				<li><a href="logout.php">登出</a></li>
                </ul>
            </div>';
    }
?>
        </div>
    </nav>