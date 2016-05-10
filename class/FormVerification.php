<?php
class FormVerification
{
    private $result=array();
    private $required=array();
    private $equal=array();
    private $errorlog;

    public function __construct($method="post")
    {
        $this->errorlog=new Log();
        $arr=$method=="post"?$_POST:$_GET;
        foreach ($arr as $key => $value) {
            $this->result[StringFilter::cleanInput($key)]=StringFilter::cleanInput($value);
        }
    }

    public function __call($method,$arguments)
    {
        switch ($method) {
            case "toUpper":
                if(is_array($arguments[0])) {
                    $arguments=$arguments[0];
                }
                foreach ($arguments as $key) {
                    $this->result[$key]=strtoupper($this->result[$key]);
                }
                break;
            case "setEqual":
                if(is_string($arguments[0])) {
                    $key=$arguments[0];
                    $value=$arguments[1];
                    $name=empty($arguments[2])?$key:$arguments[2];
                    $this->equal[]=array("key"=>$key,"value"=>$value,"name"=>$name);
                }
                else {
                    foreach ($arguments as $arr) {
                        if(is_array($arr)) {
                            $this->setEqual($arr[0],$arr[1],$arr[2]);
                        }
                    }
                }
                break;
            case "setRequired":
                if(!is_array($arguments[0])) {
                    $key=$arguments[0];
                    $name=empty($arguments[1])?$key:$arguments[1];
                    $this->required[$key]=$name;
                }
                else {
                    foreach ($arguments[0] as $key => $name) {
                        if(is_int($key)) {
                            $key=$name;
                        }
                        $this->setRequired($key,$name);
                    }
                }
                break;
            default:
                throw new Exception("No Method!");
        }
    }

    public function verify()
    {
        //Required
        foreach ($this->required as $key => $value) {
            if(\StringFilter::isempty($this->result[$key])) {
                $this->errorlog->add("請填寫".$value."!");
            }
        }
        //Equal
        foreach ($this->equal as $value) {
            if($this->result[$value["key"]]!=$value["value"]) {
                $this->errorlog->add($value["name"]."錯誤!");
            }
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function noError()
    {
        return $this->errorlog->logsCount()==0;
    }

    public function getErrorLog()
    {
        return $this->errorlog;
    }
}
