<?php
namespace DAL;

use DAL\DALBase;

// UserIdentity ID (uiid) is a token to verify user
class UserIdentityDAL extends DALBase
{
    // check if the uiid already exist in the database
    public function uiidExist($uiid)
    {
        $query="select ";
        $query.=" count(1) C ";
        $query.=" from UserIdentity A ";
        $query.=" where A.UIID=? ";
        $result=$this->exec($query,[$uiid],true);
        return intval($result[0]["C"])>0;
    }

    // check if the uiid already voted
    public function uiidUsed($uiid)
    {
        $query="select ";
        $query.=" A.UIUsed ";
        $query.=" from UserIdentity A ";
        $query.=" where A.UIID=?";
        $result=$this->exec($query,[$uiid],true);
        return $result[0]["UIUsed"]==1;
    }

    // return the topic the uiid belongs to
    public function uiidTopic($uiid)
    {
        $query="select ";
        $query.=" A.TopicId ";
        $query.=" from UserIdentity A ";
        $query.=" where A.UIID=?";
        $result=$this->exec($query,[$uiid],true);
        return $result[0]["TopicId"];
    }

    // mark the uiid as used
    public function useUiid($uiid)
    {
        $query="update UserIdentity set ";
        $query.=" UIUsed=1 ";
        $query.=" where UIID=?";
        $this->exec($query,[$uiid]);
    }

    // get all uiids by a topic
    public function getUiid($topic)
    {
        $query="select ";
        $query.=" A.UIID,A.UIUsed,A.UIMemo ";
        $query.=" from UserIdentity A ";
        $query.=" where A.TopicId=?";
        return $this->exec($query,[$topic],true);
    }

    // delete all uiids belong to a topic
    public function deleteTopicUiid($topic)
    {
        $query="delete ";
        $query.=" from UserIdentity ";
        $query.=" where TopicId=?";
        $this->exec($query,[$topic]);
    }

    // add a uiid
    public function addUiid($topic,$uiid)
    {
        $query="insert into UserIdentity ";
        $query.=" (UIID,TopicId,UIUsed) ";
        $query.=" values (?,?,0)";
        $this->exec($query,[$uiid,$topic]);
    }

    // add a comment for uiid
    public function memoUiid($uiid,$memo)
    {
        $query="update UserIdentity set ";
        $query.=" UIMemo=? ";
        $query.=" where UIID=?";
        $this->exec($query,[$memo,$uiid]);
    }

    // delete a uiid
    public function deleteUiid($uiid)
    {
        $query="delete ";
        $query.=" from UserIdentity ";
        $query.=" where UIID=?";
        $this->exec($query,[$uiid]);
    }
}
