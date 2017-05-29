<!-- TODO: Use bootstrap alert to show logs-->
<?php
require_once("../autoload.php");
SessionManager::start();
require_once("CheckAdmin.php");

$form=new FormVerification();
$results=$form->getResult();
if($_POST["action"]=="add") {
    $form->setRequired(array(
        "name"=>"議題名稱",
    ));
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $bll=new BLL\VotingBLL();
        $options=[];
        $optionCount=intval($results["option-number"]);
        for($i=1;$i<=$optionCount;$i++) {
            $options[]=$results["option".$i];
        }
        $id=$bll->addTopic($results["name"],$results["desc"],$results["enable"],$options);
        header("HTTP/1.1 302 Redirect");
        header("Location: topic.php?id=".$id);
        exit;
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
                        <h4> 新增議題 </h4>
                    </div>
                    <div class="panel-body">
                        <!-- TODO: Use bootstrap alert to show logs-->
                        <?=!empty($log)&&$log->logsCount()>0?$log->toString("log"):""?>
                        <form action="" method="post">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="name">*議題名稱：</label>
                                    <input class="form-control" type="text" id="name" name="name" required>
                
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="desc">議題描述：</label>
                                    <textarea class="form-control" name="desc" id="desc" cols="50" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group form-inline col-lg-8">
                                    <label for="enable">是否啟用：</label>
                                    <input class="form-control" type="checkbox" id="enable" name="enable" value="t" checked>
                                </div>
                            </div>
                            <input type="hidden" name="action" value="add">
                            <h4>選項：</h4>
                            <section id="options"></section>
                            <div class="form-group form-inline">
                                <label for="add-option-number">新增選項：</label>
                                <input class="form-control" type="number" id="add-option-number" min="0" value="1">
                                <button class="btn btn-primary" id="add-option" type="button">新增</button>
                            </div>
                            <input type="hidden" id="option-number" name="option-number" value="0">
                            <button class="btn btn-primary" type="submit">送出</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require_once("../footer.php");?>
    <script>
        function addOption(id){
            var row=document.createElement("div");
            row.setAttribute("class", row)
            var container=document.createElement("div");
            container.setAttribute("class", "form-group form-inline col=lg-6");
            var label=document.createElement("label");
            label.innerHTML="選項"+id+"：";
            label.setAttribute("for","option"+id);
            var option=document.createElement("input");
            option.setAttribute("id","option"+id);
            option.setAttribute("name","option"+id);
            option.setAttribute("type","text");
            option.setAttribute("class","form-control");

            container.appendChild(label);
            container.appendChild(option);
            row.appendChild(container);
            document.getElementById("options").appendChild(container);
        }

        document.getElementById("add-option").addEventListener("click",function(){
            var num=Number(document.getElementById("add-option-number").value);
            var id=Number(document.getElementById("option-number").value)+1;
            for(var i=0;i<num;i++){
                addOption(id+i);
            }
            document.getElementById("option-number").value=id+num-1;
        });
    </script>
</body>
</html>