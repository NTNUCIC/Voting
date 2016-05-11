<?php
require_once("../autoload.php");
SessionManager::start();
require_once("CheckAdmin.php");


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
        <h2>新增議題：</h2>
        <form action="" method="post">
            <label for="name">*議題名稱：</label>
            <input type="text" id="name" name="name" required value="">
            <br>
            <label for="desc">*議題描述：</label>
            <textarea name="desc" id="desc" cols="50" rows="10"></textarea>
            <br>
            <input type="hidden" name="action" value="add">
            <h4>選項：</h4>
            <section id="options"></section>
            <label for="add-option-number">新增選項：</label>
            <input type="number" id="add-option-number" min="0" value="1">
            <button id="add-option" type="button">新增</button>
            <br>
            <input type="hidden" id="option-number" name="option-number" value="0">
            <button type="submit">送出新增</button>
        </form>
    </main>
    <footer>
        <?php require_once("footer.php");?>
    </footer>
    <script>
        function addOption(id){
            var label=document.createElement("label");
            label.innerHTML="選項"+id+"：";
            label.setAttribute("for","option"+id);
            var option=document.createElement("input");
            option.setAttribute("id","option"+id);
            option.setAttribute("name","option"+id);
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
    </script>
</body>
</html>