<?php
class DBManager
{
    protected $db=null;

    public function __construct()
    {
        try {
            require("db.ini");
            $this->db=new PDO("mysql:host=".$host.";dbname=".$dbname.";charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_TIMEOUT,65535);
        }
        catch(PDOException $ex) {
            $errorlog=new Log();
            $errorlog->add("Database Connection Error:".$ex->getMessage());
            $errorlog->log();
            throw new Exception("DB Error.");
        }
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }

    public function exec($query, $param=null, $return=false, $namedParam=false)
    {
        try {
            $exec=$this->db->prepare($query);
            if(is_array($param)) {
                if($namedParam) {
                    foreach ($param as $key => $value) {
                        $exec->bindValue($key, $value);
                    }
                }
                else {
                    for($i=1;$i<=count($param);$i++) {
                        $exec->bindValue($i, $param[$i-1]);
                    }
                }
            }
            $exec->execute();
            if($return) {
                return $exec->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        catch(PDOException $ex) {
            if(SERVER_ENV!="PROD") {
                echo $ex->getMessage();
                /*$errorlog=new Log();
                $errorlog->add("PDO Execute Error:".$ex->getMessage());
                $errorlog->log();*/
            }
            throw new Exception("DB Error.");
        }
    }
}
