<?php
require_once("autoload.php");
SessionManager::start();

$bll=new BLL\VotingBLL();

$form=new FormVerification();
$results=$form->getResult();
if($_POST["action"]=="vote") {
    $form->setRequired(array(
        "option"=>"選項",
        "uiid"=>"識別碼",
        "iv"=>"圖形驗證碼",
        "TopicId",
    ));
    $form->toUpper("iv");
    $form->setEqual("iv",SessionManager::get("verification"),"圖形驗證碼");
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $log->addLogs($bll->vote(
            $results["TopicId"],
            $results["option"],
            $results["uiid"]
        ));
    }
}

$data=$bll->getLastTopic();
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
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3> 最新議題</h3>
                </div>
                <div class="panel-body">
                    <?php if(is_null($data)) {?>
                        <h2>現在沒有議題!</h2>
                    <?php } else {?>
                        <h2><?=$data['TopicName']?></h2>
                        <p><?=$data['TopicDesc']?></p>
                        <form class="form-signin">
                            <ul>
                            <?php foreach($data["Option"] as $option) {?>
                                <li>
                                <input type="radio" id="o<?=$option['OptionId']?>" name="option" value="<?=$option['OptionId']?>"<?=$results['option']==$option['OptionId']?" checked":""?>>
                                <label for="o<?=$option['OptionId']?>"><?=$option['OptionName']?></label>
                                </li>
                            <?php }?>
                            </ul>
                            <label for="uiid" class="form-label">識別碼：</label>
                            <input type="text" id="uiid" class="form-control" required value="<?=$results['uiid']?>" placeholder="請輸入識別碼">
                            <label for="iv" class="form-label">圖形驗證：</label>
                            <img id="vImage" src="verification.php">
                            <button type="button" id="refresh" class="btn-sm btn-link refresh">刷新</button>
                            <input type="text" id="iv" class="form-control" required placeholder="請輸入圖形驗證碼">
                            <?=!empty($log)&&$log->logsCount()>0?$log->toString("log"):""?>
                            <input type="hidden" name="action" value="vote">
                            <input type="hidden" name="TopicId" value="<?=$data['TopicId']?>">
                            <button class="btn btn-lg btn-primary btn-block form-submit" type="submit">送出</button>
                        </form>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("footer.php");?>
    <script>
        document.getElementById("refresh").addEventListener("click",function(){
            document.getElementById("vImage").src="verification.php?t="+new Date().getTime();
            document.getElementById("iv").value="";
        });
    </script>
  </body>
</html>
