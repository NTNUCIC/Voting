<?php
namespace BLL;

class BLLBase
{
    protected $db;
    protected $dal;

    public function __construct(&$dbm=null)
    {
        if(!empty($dbm)) {
            $this->db=$dbm;
        }
    }

    public function &getDB()
    {
        return $this->db;
    }
}
