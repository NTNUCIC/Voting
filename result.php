<?php
    require_once("autoload.php");
    SessionManager::start();

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
        #log{
            border: 1px dashed red;
        }
    </style>
    <link href="./css/general.css" rel="stylesheet">
</head>
<body>
    <?php require_once("navbar.php");?>
    <main>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading ">
                        <h3>議題列表</h3>
                    </div>
                    <table class="table table-hover panel-body">
                        <div class="list-group">
                        <?php foreach($data as $datarow) {?>
                            <a href="topic_result.php?id=<?=$datarow['TopicId']?>" class="list-group-item
                                <?=$datarow['TopicEnable']?'list-group-item-info':'list-group-item-danger'?>">
                                <?=$datarow['TopicName']?>
                                <?=$datarow['TopicEnable']?'':'(關閉)'?>
                            </a>
                        <?php }?>
                        </div>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php require_once("footer.php");?>
</body>
</html>
