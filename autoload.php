<?php
function autoload($className)
{
    $className=ltrim($className, "\\");
    //echo "Class:".$className."<br>";
    $fileName="class/";
    if($lastNsPos=strrpos($className, "\\")) {
        $namespace=substr($className, 0, $lastNsPos);
        $className=substr($className, $lastNsPos+1);
        $fileName.=str_replace("\\", DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
    }
    $fileName.=$className.".php";
    //echo "FilePath:".$fileName."<br>";
    require_once($fileName);
}
spl_autoload_register("autoload");
