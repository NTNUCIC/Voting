<?php
namespace DAL;

use DAL\DALBase;

class AdminDAL extends DALBase
{
    // check the password
    public function hasAdmin($admin,$password)
    {
        $query="select ";
        $query.=" count(1) AC ";
        $query.=" from Admin A ";
        $query.=" where A.AdminName=? and A.PassWord=?";
        $result=$this->exec($query,[$admin,$password],true);
        return $result[0]["AC"]>0;
    }
}
