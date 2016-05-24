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
    <style>
    	#log{
    		border: 1px dashed red;
    	}
    </style>
</head>
<body>
    <header>
        <h1>國立臺灣師範大學資訊研究社--投票系統--投票結果</h1>
    </header>
    <main>
        <section id="topics">
            <h2>議題列表：</h2>
            <ul>
                <?php foreach($data as $datarow) {?>
                    <li class="<?=$datarow['TopicEnable']?'enable':'disable'?>">
                        <a href="topic_result.php?id=<?=$datarow['TopicId']?>">
                            <?=$datarow['TopicName']?>
                            <?=$datarow['TopicEnable']?'':'(關閉)'?>
                        </a>
                    </li>
                <?php }?>
            </ul>
        </section>	
    </main>
    <footer>
        <p>Copyright &copy; NTNUCIC 2015</p>
    </footer>
</body>
</html>