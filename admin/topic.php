<?php
require_once("../autoload.php");
SessionManager::start();
require_once("CheckAdmin.php");

$id=$_GET["id"];

if(empty($id)) {
    noTopic();
}

$bll=new BLL\VotingBLL();

$form=new FormVerification();
$results=$form->getResult();
if($_POST["action"]=="edit") {
    $form->setRequired(array(
        "id",
        "TopicName"=>"議題名稱",
    ));
    $form->setEqual("id",$id,"id");
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $log->addLogs($bll->editTopic(
            $id,
            $results["TopicName"],
            $results["TopicDesc"],
            $results["TopicEnable"]
        ));
    }
} elseif($_POST["action"]=="delete") {
    $form->setRequired(array(
        "id",
    ));
    $form->setEqual("id",$id,"id");
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $bll->deleteTopic($id);
        header("HTTP/1.1 302 Redirect");
        header("Location: main.php");
        exit;
    }
} elseif($_POST["action"]=="new-uiid") {
    $form->setRequired(array(
        "id",
        "new-uiid-number"=>"識別碼數量",
    ));
    $form->setEqual("id",$id,"id");
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $log->addLogs($bll->addUiid(
            $id,
            $results["new-uiid-number"]
        ));
    }
} elseif($_POST["action"]=="memo-uiid") {
    $form->setRequired(array(
        "action-id",
        "action-value",
    ));
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $log->addLogs($bll->memoUiid(
            $results["action-id"],
            $results["action-value"]
        ));
    }
} elseif($_POST["action"]=="delete-uiid") {
    $form->setRequired(array(
        "action-id",
    ));
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $log->addLogs($bll->deleteUiid(
            $results["action-id"]
        ));
    }
} elseif($_POST["action"]=="rename-option") {
    $form->setRequired(array(
        "action-id",
        "action-value",
    ));
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $log->addLogs($bll->renameOption(
            $results["action-id"],
            $results["action-value"]
        ));
    }
} elseif($_POST["action"]=="delete-option") {
    $form->setRequired(array(
        "action-id",
    ));
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $log->addLogs($bll->deleteOption(
            $results["action-id"]
        ));
    }
} elseif($_POST["action"]=="add-option") {
    $form->setRequired(array(
        "id",
        "option-number",
    ));
    $form->setEqual("id",$id,"id");
    $form->verify();
    $log=$form->getErrorLog();
    if($form->noError()) {
        $options=[];
        $optionCount=intval($results["option-number"]);
        for($i=1;$i<=$optionCount;$i++) {
            $options[]=$results["new-option".$i];
        }
        $log->addLogs($bll->addOption(
            $id,
            $options
        ));
    }
}

$data=$bll->getTopic($id);
if(is_null($data)) {
    noTopic();
}
$data["TopicDesc"]=StringFilter::textareaOutput($data["TopicDesc"]);
$uiids=$bll->getUiid($id);

function noTopic()
{?>
    <script>
        alert("議題不存在!");
        window.location="main.php";
    </script>
    <?php exit;
}

function getValue($name)
{
    global $data,$results;
    if(!empty($results[$name])||$results[$name]=="0") {
        return $results[$name];
    }
    else {
        return $data[$name];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NTNUCIC Voting</title>
    <?php require_once("head.php");?>
</head>
<body>
    <header>
        <?php require_once("header.php");?>
    </header>
    <nav>
        <?php require_once("nav.php");?>
    </nav>
    <main>
        <?=!empty($log)&&$log->logsCount()>0?$log->toString("log"):""?>
        <form id="form1" action="" method="post">
            <input type="hidden" id="id" name="id" value="<?=$id?>">
            <input type="hidden" id="action" name="action" value="edit">
            <input type="hidden" id="action-id" name="action-id">
            <input type="hidden" id="action-value" name="action-value">
            <section>
                <h3>編輯議題：</h3>
                <label for="name">*議題名稱：</label>
                <input type="text" id="name" name="TopicName" required value="<?=getValue('TopicName')?>">
                <br>
                <label for="desc">議題描述：</label>
                <textarea name="TopicDesc" id="desc" cols="50" rows="10"><?=getValue('TopicDesc')?></textarea>
                <br>
                <label for="enable">是否啟用：</label>
                <input type="checkbox" id="enable" name="TopicEnable" value="1"<?=getValue('TopicEnable')=="1"?" checked":""?>>
                <br>
                <button type="submit">修改</button>
                <button id="delete-topic" type="button">刪除議題</button>
            </section>
            <section>
                <h3>選項：</h3>
                <table>
                    <thead>
                        <tr>
                            <td>選項內容</td>
                            <td>票數</td>
                            <td>動作</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data["Option"] as $datarow) {?>
                            <tr>
                                <td id="option-name<?=$datarow['OptionId']?>"><?=$datarow['OptionName']?></td>
                                <td><?=$datarow['OptionCount']?></td>
                                <td>
                                    <input type="hidden" class="option-id" id="option-id<?=$datarow['OptionId']?>" value="<?=$datarow['OptionId']?>">
                                    <button type="button" id="rename-option<?=$datarow['OptionId']?>">編輯</button>
                                    <button type="button" id="delete-option<?=$datarow['OptionId']?>">刪除</button>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
                <section id="options"></section>
                <label for="add-option-number">新增選項：</label>
                <input type="number" id="add-option-number" min="0" value="1">
                <button id="add-option" type="button">新增</button>
                <br>
                <input type="hidden" id="option-number" name="option-number" value="0">
                <button id="add-option-submit" type="button">新增選項</button>
            </section>
            <section>
                <h3>識別碼：</h3>
                <label for="new-uiid-number">產生識別碼：</label>
                <input type="number" id="new-uiid-number" name="new-uiid-number" min="0" value="1">
                <button id="new-uiid" type="button">產生</button>
                <br>
                <table>
                    <thead>
                        <tr>
                            <td>識別碼</td>
                            <td>使用</td>
                            <td>備註</td>
                            <td>動作</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i=0;$i<count($uiids);$i++) {?>
                            <tr>
                                <td id="uiid<?=$i?>"><?=$uiids[$i]['UIID']?></td>
                                <td><?=$uiids[$i]['UIUsed']?></td>
                                <td id="uiid-memo<?=$i?>"><?=$uiids[$i]['UIMemo']?></td>
                                <td>
                                    <button type="button" id="memo-uiid<?=$i?>">備註</button>
                                    <button type="button" id="delete-uiid<?=$i?>">刪除</button>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </section>
        </form>
    </main>
    <footer>
        <?php require_once("footer.php");?>
    </footer>
    <script>
        function addOption(id){
            var label=document.createElement("label");
            label.innerHTML="新選項"+id+"：";
            label.setAttribute("for","new-option"+id);
            var option=document.createElement("input");
            option.setAttribute("id","new-option"+id);
            option.setAttribute("name","new-option"+id);
            var br=document.createElement("br");
            document.getElementById("options").appendChild(label);
            document.getElementById("options").appendChild(option);
            document.getElementById("options").appendChild(br);
        }

        document.getElementById("add-option").addEventListener("click",function(){
            var num=Number(document.getElementById("add-option-number").value);
            var id=Number(document.getElementById("option-number").value)+1;
            for(var i=0;i<num;i++){
                addOption(id+i);
            }
            document.getElementById("option-number").value=id+num-1;
        });

        document.getElementById("add-option-submit").addEventListener("click",function(){
            document.getElementById("action").value="add-option";
            document.getElementById("form1").submit();
        });

        function renameOptionHandler(id){
            return function(e){
                var newname=prompt("輸入選項內容：",document.getElementById("option-name"+id).innerHTML);
                if(newname){
                    document.getElementById("action-id").value=id;
                    document.getElementById("action-value").value=newname;
                    document.getElementById("action").value="rename-option";
                    document.getElementById("form1").submit();
                }
            };
        }

        function deleteOptionHandler(id){
            return function(e){
                if(confirm("刪除後無法回復，是否確定刪除?")){
                    document.getElementById("action-id").value=id;
                    document.getElementById("action").value="delete-option";
                    document.getElementById("form1").submit();
                }
            };
        }

        function memoUiidHandler(id){
            return function(e){
                var newmemo=prompt("輸入註解：",document.getElementById("uiid-memo"+id).innerHTML);
                if(newmemo){
                    document.getElementById("action-id").value=document.getElementById("uiid"+id).innerHTML;
                    document.getElementById("action-value").value=newmemo;
                    document.getElementById("action").value="memo-uiid";
                    document.getElementById("form1").submit();
                }
            };
        }

        function deleteUiidHandler(id){
            return function(e){
                if(confirm("刪除後無法回復，是否確定刪除?")){
                    document.getElementById("action-id").value=document.getElementById("uiid"+id).innerHTML;
                    document.getElementById("action").value="delete-uiid";
                    document.getElementById("form1").submit();
                }
            };
        }

        document.getElementById("delete-topic").addEventListener("click",function(){
            if(confirm("刪除後無法回復，是否確定刪除?")){
                document.getElementById("action").value="delete";
                document.getElementById("form1").submit();
            }
        });

        var optionIdList=document.getElementsByClassName("option-id");
        for(var i=0;i<optionIdList.length;i++){
            var id=optionIdList[i].value;
            document.getElementById("rename-option"+id).addEventListener("click",renameOptionHandler(id));
            document.getElementById("delete-option"+id).addEventListener("click",deleteOptionHandler(id));
        }

        document.getElementById("new-uiid").addEventListener("click",function(){
            document.getElementById("action").value="new-uiid";
            document.getElementById("form1").submit();
        });

        for(var i=0;i<<?=count($uiids)?>;i++){
            document.getElementById("memo-uiid"+i).addEventListener("click",memoUiidHandler(i));
            document.getElementById("delete-uiid"+i).addEventListener("click",deleteUiidHandler(i));
        }
    </script>
</body>
</html>