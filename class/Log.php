<?php
class Log
{
    private $logs=array();

    public function add($log)
    {
        $this->logs[]=$log;
    }

    public function addLogs($log2)
    {
        $logs2=$log2->getLogs();
        foreach($logs2 as $value) {
            $this->add($value);
        }
    }

    public function logsCount()
    {
        return count($this->logs);
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function toString($id="log")
    {
        $result="";
        if($this->logsCount()>0) {
            $result.="<ul id=".$id.">";
            foreach ($this->logs as $value) {
                $result.="<li>".$value."</li>";
            }
            $result.="</ul>";
        }
        return $result;
    }

    public function log()
    {
        $i=1;
        $filename=date("Ymdhisa")."-";
        while(file_exists($filename.$i.".log")) {
            $i++;
        }
        $filename.=$i.".log";
        $file=fopen($filename,"w");
        fwrite($file, date("Y-m-d h:i:sa")."\n");
        foreach ($this->logs as $value) {
            fwrite($file, $value."\n");
        }
        fclose($file);
    }
}
