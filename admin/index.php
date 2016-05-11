<?php
require_once("../autoload.php");
SessionManager::start();
if(BLL\AdminBLL::isLogIn()) {
    header("HTTP/1.1 302 Redirect");
    header("Location: main.php");
    exit;
}

$form=new FormVerification();
$results=$form->getResult();
if($_POST["action"]=="log-in") {
    $form->setRequired(array(
        "admin"=>"管理者帳號",
        "password"=>"密碼",
        "iv"=>"圖形驗證碼",
    ));
    $form->toUpper("iv");
    $form->setEqual("iv",SessionManager::get("verification"),"圖形驗證碼");
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $bll=new BLL\AdminBLL();
        if($bll->logIn($results["admin"],$results["password"])) {
            header("HTTP/1.1 302 Redirect");
            header("Location: main.php");
            exit;
        } else {
            $log->add("帳號或密碼不正確!");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NTNUCIC Voting</title>
    <?require_once("head.php");?>
</head>
<body>
    <header>
        <h1>國立臺灣師範大學資訊研究社--投票系統--後台管理系統</h1>
    </header>
    <main>
        <?=!empty($log)&&$log->logsCount()>0?$log->toString("log"):""?>
        <form action="" method="post">
            <label for="admin">*管理者帳號：</label>
            <input type="text" id="admin" name="admin" value="<?=$results['admin']?>" required>
            <br>
            <label for="password">*密碼：</label>
            <input type="password" id="password" name="password" required>
            <br>
            <img src="../verification.php" id="vImage">
            <br>
            <label for="iv">*圖形驗證碼：</label>
            <input type="text" id="iv" name="iv" required>
            <button id="refresh" type="button">刷新</button>
            <br>
            <input type="hidden" name="action" value="log-in">
            <button type="submit">登入</button>
        </form>
    </main>
    <footer>
        <p>Copyright &copy; NTNUCIC 2015</p>
    </footer>
    <script>
        document.getElementById("refresh").addEventListener("click",function(){
            document.getElementById("vImage").src="../verification.php?t="+new Date().getTime();
            document.getElementById("iv").value="";
        });
    </script>
</body>
</html>