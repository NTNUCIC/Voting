<?php
//php setting override
define("SERVER_ENV","PROD");
error_reporting(E_ALL);
if(SERVER_ENV=="PROD") {
    display_errors(false);
    log_errors(true);
}
//autoload
function autoload($className)
{
    $className=ltrim($className, "\\");
    $fileName="class/";
    if($lastNsPos=strrpos($className, "\\")) {
        $namespace=substr($className, 0, $lastNsPos);
        $className=substr($className, $lastNsPos+1);
        $fileName.=str_replace("\\", DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
    }
    $fileName.=$className.".php";
    require_once($fileName);
}
spl_autoload_register("autoload");
