<?php
//The data access layer
namespace DAL;

use DBManager;

class DALBase
{
    protected $db;

    public function __construct(&$dbm=null)
    {
        if(empty($dbm)) {
            $this->db=new DBManager();
        }
        else {
            $this->db=$dbm;
        }
    }

    public function &getDB()
    {
        return $this->db;
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
        return $this->db->exec($query,$param,$return,$namedParam);
    }
}
