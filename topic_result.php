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

    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico">

    <meta name="og:title" content="師大資研社--投票系統-投票結果">
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
        <h1>國立臺灣師範大學資訊研究社--投票系統--投票結果</h1>
    </header>
    <main>
            <section>
                <h2><?=$data['TopicName']?></h2>
        		<p><?=$data['TopicDesc']?></p>
        		<h5>應投票數：<?=count($uiids)?></h5>
            </section>
            <section>
                <h4>投票結果：</h4>
                <table border="1">
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
            </section>
    </main>
    <footer>
        <p>Copyright &copy; NTNUCIC 2015</p>
    </footer>
</body>
</html>