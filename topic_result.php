<?php
require_once("autoload.php");
SessionManager::start();

$bll=new BLL\VotingBLL();
$id=$_GET["id"];

if(empty($id)) {
    noTopic();
}

$data=$bll->getTopic($id);
if(is_null($data)) {
    noTopic();
}
$uiids=$bll->getUiid($id);

function noTopic()
{?>
    <script>
        alert("議題不存在!");
        window.location="result.php";
    </script>
    <?php exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NTNUCIC Voting</title>
    <?php require_once("head.php");?>
    <link href="./css/general.css" rel="stylesheet">
</head>
<body>
    <?php require_once("navbar.php");?>
    <main>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2><?=$data['TopicName']?></h2>
                    </div>
                    <div class="panel-body">
                        <p><?=$data['TopicDesc']?></p>
                        <h5>應投票數：<?=count($uiids)?></h5>
                        <h4>投票結果：</h4>
                        <table class="table table-hover table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <td>選項內容</td>
                                    <td>票數</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data["Option"] as $datarow) {?>
                                    <tr>
                                        <td id="option-name<?=$datarow['OptionId']?>"><?=$datarow['OptionName']?></td>
                                        <td><?=$datarow['OptionCount']?></td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require_once("footer.php");?>
</body>
</html>
