<?php
require_once("autoload.php");
SessionManager::start();

$bll=new BLL\VotingBLL();

if($_POST["action"]=="vote") {
    $form=new FormVerification();
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
    $results=$form->getResult();
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

    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

    <meta name="og:title" content="國立臺灣師範大學資訊研究社">
    <meta name="og:url" content="http://ntnucic.github.io/104/">
    <meta name="og:description" content="國立臺灣師範大學資訊研究社">
    <meta name="og:image" content="images/cic.jpg">
    <meta name="og:site_name" content="NTNUCIC">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
</head>
<body>
    <header></header>
    <main>
        <?=!empty($log)&&$log->logsCount()>0?$log->toString("log"):""?>
        <h2><?=$data['TopicName']?></h2>
        <p><?=$data['TopicDesc']?></p>
        <form action="" method="post">
            <ul>
                <?php foreach($data["Option"] as $option) {?>
                    <li>
                        <input type="radio" id="o<?=$option['OptionId']?>" name="option" value="<?=$option['OptionId']?>">
                        <label for="o<?=$option['OptionId']?>"><?=$option['OptionName']?></label>
                    </li>
                <?php }?>
            </ul>
            <label for="uiid">*識別碼：</label>
            <input type="text" id="uiid" name="uiid" required>
            <br>
            <img src="" alt="">
            <label for="iv">*圖形驗證碼：</label>
            <input type="text" id="iv" name="iv" required>
            <br>
            <input type="hidden" name="action" value="vote">
            <input type="hidden" name="tid" value="<?=$data['TopicId']?>">
            <button type="submit">送出</button>
        </form>
    </main>
    <footer>
        <p>Copyright &copy; NTNUCIC 2015</p>
    </footer>
</body>
</html>