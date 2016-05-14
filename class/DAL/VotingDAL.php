<?php
namespace DAL;

use DAL\DALBase;

class VotingDAL extends DALBase
{
    public function getTopic($id=null,$enable=true)
    {
        $query="select ";
        $query.=" A.TopicId,A.TopicName,A.TopicDesc,A.TopicEnable ";
        $query.=" from Topic A ";
        $query.=" where 1=1 ";
        $param=[];
        if(!is_null($id)) {
            $query.=" and A.TopicId=? ";
            $param[]=$id;
        }
        if($enable) {
            $query.=" and A.TopicEnable='1' ";
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
        $query.=" where A.OptionId=? ";
        $param=[$option];
        if(!is_null($topic)) {
            $query.=" and A.TopicId=? ";
            $param[]=$topic;
        }
        $result=$this->exec($query,$param,true);
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

    public function editTopic($id,$name,$desc,$enable)
    {
        $query="update Topic set ";
        $query.=" TopicName=?, ";
        $query.=" TopicDesc=?, ";
        $query.=" TopicEnable=? ";
        $query.=" where TopicId=?";
        $this->exec($query,[$name,$desc,$enable,$id]);
    }

    public function deleteTopic($id)
    {
        $query="delete from Topic ";
        $query.=" where TopicId=?";
        $this->exec($query,[$id]);
    }

    public function deleteOption($topic)
    {
        $query="delete from `Option` ";
        $query.=" where TopicId=?";
        $this->exec($query,[$topic]);
    }

    public function renameOption($id,$name)
    {
        $query="update `Option` set ";
        $query.=" OptionName=? ";
        $query.=" where OptionId=?";
        $this->exec($query,[$name,$id]);
    }

    public function deleteOptionFromId($id)
    {
        $query="delete ";
        $query.=" from `Option` ";
        $query.=" where OptionId=?";
        $this->exec($query,[$id]);
    }
}
