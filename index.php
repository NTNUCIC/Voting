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

    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico">

    <meta name="og:title" content="師大資研社--投票系統">
    <meta name="og:url" content="http://web.ntnucic.club/vote">
    <meta name="og:description" content="國立臺灣師範大學資訊研究社專屬，線上不記名投票系統">
    <meta name="og:image" content="assets/cic.jpg">
    <meta name="og:site_name" content="NTNUCIC">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
</head>
<body>
    <header>
        <h1>國立臺灣師範大學資訊研究社--投票系統</h1>
    </header>
    <main>
        <?=!empty($log)&&$log->logsCount()>0?$log->toString("log"):""?>
        <h2><?=$data['TopicName']?></h2>
        <p><?=$data['TopicDesc']?></p>
        <form action="" method="post">
            <ul>
                <?php foreach($data["Option"] as $option) {?>
                    <li>
                        <input type="radio" id="o<?=$option['OptionId']?>" name="option" value="<?=$option['OptionId']?>"<?=$results['option']==$option['OptionId']?" checked":""?>>
                        <label for="o<?=$option['OptionId']?>"><?=$option['OptionName']?></label>
                    </li>
                <?php }?>
            </ul>
            <label for="uiid">*識別碼：</label>
            <input type="text" id="uiid" name="uiid" required value="<?=$results['uiid']?>">
            <br>
            <img id="vImage" src="verification.php">
            <br>
            <label for="iv">*圖形驗證碼：</label>
            <input type="text" id="iv" name="iv" required>
            <button type="button" id="refresh">刷新</button>
            <br>
            <input type="hidden" name="action" value="vote">
            <input type="hidden" name="TopicId" value="<?=$data['TopicId']?>">
            <button type="submit">送出</button>
        </form>
    </main>
    <footer>
        <p>Copyright &copy; NTNUCIC 2015</p>
    </footer>
    <script>
        document.getElementById("refresh").addEventListener("click",function(){
            document.getElementById("vImage").src="verification.php?t="+new Date().getTime();
            document.getElementById("iv").value="";
        });
    </script>
</body>
</html>