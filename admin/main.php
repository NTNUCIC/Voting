<?php
require_once("../autoload.php");
SessionManager::start();
require_once("CheckAdmin.php");

$bll=new BLL\VotingBLL();

$data=$bll->getAllTopic();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NTNUCIC Voting</title>
    <?php require_once("head.php");?>
    <style>
        li.disable{
            list-style-type: circle;
        }
    </style>
</head>
<body>
    <header>
        <?php require_once("header.php");?>
    </header>
    <nav>
        <?php require_once("nav.php");?>
    </nav>
    <main>
        <?=!empty($log)&&$log->logsCount()>0?$log->toString("log"):""?>
        <section id="topics">
            <h2>議題列表：</h2>
            <ul>
                <?php foreach($data as $datarow) {?>
                    <li class="<?=$datarow['TopicEnable']?'enable':'disable'?>">
                        <a href="topic.php?id=<?=$datarow['TopicId']?>">
                            <?=$datarow['TopicName']?>
                            <?=$datarow['TopicEnable']?'':'(關閉)'?>
                        </a>
                    </li>
                <?php }?>
            </ul>
            <a href="topicAdd.php">新議題</a>
        </section>
    </main>
    <footer>
        <?php require_once("footer.php");?>
    </footer>
</body>
</html>