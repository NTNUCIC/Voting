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
    <?php require_once("head.php");?>
    <link href="../css/general.css" rel="stylesheet">
</head>
<body>
    <?php require_once("navbar.php");?>
    <main>
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3> 登入系統 </h3>
                    </div>

                    <div class="panel-body">
                        <?=!empty($log)&&$log->logsCount()>0?$log->toString("log"):""?>
                        <form action="" method="post">
                            <div class="row">
                                <div class="form-group form-inline col-lg-8">
                                    <label for="admin">*帳號：</label>
                                    <input class="form-control" type="text" id="admin" name="admin" value="<?=$results['admin']?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-inline col-lg-8">
                                    <label for="password">*密碼：</label>
                                    <input class="form-control" type="password" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-inline col-lg-8">
                                    <img src="../verification.php" id="vImage">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-inline col-lg-8">
                                    <label for="iv">*圖形驗證碼：</label>
                                    <input class="form-control" type="text" id="iv" name="iv" required>
                                    <button class="btn btn-primary" id="refresh" type="button">
                                        <span class="glyphicon glyphicon-refresh"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-inline col-lg-1">
                                    <input type="hidden" name="action" value="log-in">
                                    <button class="btn btn-primary" type="submit">登入</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php require_once("../footer.php");?>
    </footer>
    <script>
        document.getElementById("refresh").addEventListener("click",function(){
            document.getElementById("vImage").src="../verification.php?t="+new Date().getTime();
            document.getElementById("iv").value="";
        });
    </script>
</body>
</html>
