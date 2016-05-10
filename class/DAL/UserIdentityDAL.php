<?php
namespace DAL;

use DAL\DALBase;

class UserIdentityDAL extends DALBase
{
    public function uiidExist($uiid)
    {
        $query="select ";
        $query.=" count(1) C ";
        $query.=" from UserIdentity A ";
        $query.=" where A.UIID=? ";
        $result=$this->exec($query,[$uiid],true);
        return intval($result[0]["C"])>0;
    }

    public function uiidUsed($uiid)
    {
        $query="select ";
        $query.=" A.UIUsed ";
        $query.=" from UserIdentity A ";
        $query.=" where A.UIID=?";
        $result=$this->exec($query,[$uiid],true);
        return $result[0]["UIUsed"]==1;
    }

    public function useUiid($uiid)
    {
        $query="update UserIdentity set ";
        $query.=" UIUsed=1 ";
        $query.=" where UIID=?";
        $this->exec($query,[$uiid]);
    }
}
