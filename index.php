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
    <style>
    	#log{
    		border: 1px dashed red;
    	}
    </style>

    <script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- Custom styles for this template -->
    <link href="./css/signin.css" rel="stylesheet">
  </head>

  <body>
    <header class="container">
        <h1 class="form-signin-heading">國立臺灣師範大學資訊研究社--投票系統</h1>
    </header>
    <div class="container">
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

    </div> 
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