<?php
namespace DAL;

use DAL\DALBase;

class VotingDAL extends DALBase
{
    public function getTopic($id=null)
    {
        $query="select ";
        $query.=" A.TopicId,A.TopicName,A.TopicDesc,A.TopicEnable ";
        $query.=" from Topic A ";
        $query.=" where A.TopicEnable='1' ";
        $param=[];
        if(!is_null($id)) {
            $query.=" and A.TopicId=? ";
            $param[]=$id;
        }
        $query.=" order by A.TopicEnable desc,A.TopicId desc ";
        return $this->exec($query,$param,true);
    }

    public function getOption($topic)
    {
        $query="select ";
        $query.=" A.OptionId,A.OptionName,A.OptionCount ";
        $query.=" from `Option` A ";
        $query.=" where A.TopicId=?";
        return $this->exec($query,[$topic],true);
    }

    public function topicExist($id)
    {
        $query="select ";
        $query.=" count(1) C ";
        $query.=" from Topic A ";
        $query.=" where A.TopicId=? ";
        $result=$this->exec($query,[$id],true);
        return intval($result[0]["C"])>0;
    }

    public function optionExist($topic,$option)
    {
        $query="select ";
        $query.=" count(1) C ";
        $query.=" from `Option` A ";
        $query.=" where A.TopicId=? and A.OptionId=? ";
        $result=$this->exec($query,[$topic,$option],true);
        return intval($result[0]["C"])>0;
    }

    public function vote($option)
    {
        $query="update `Option` set ";
        $query.=" OptionCount=OptionCount+1 ";
        $query.=" where OptionId=?";
        $this->exec($query,[$option]);
    }

    public function addTopic($name,$desc,$enable)
    {
        $query="insert into Topic ";
        $query.=" (TopicName,TopicDesc,TopicEnable) ";
        $query.=" values (?,?,?)";
        $this->exec($query,[$name,$desc,$enable]);
        $query="select last_insert_id() id";
        $result=$this->exec($query,null,true);
        return $result[0]["id"];
    }

    public function addOption($topic,$option)
    {
        $query="insert into `Option` ";
        $query.=" (TopicId,OptionName,OptionCount) ";
        $query.=" values (?,?,0)";
        $this->exec($query,[$topic,$option]);
    }
}
