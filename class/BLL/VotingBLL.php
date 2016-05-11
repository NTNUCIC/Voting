<?php
namespace BLL;

use BLL\BLLBase;
use DAL\VotingDAL;
use DAL\UserIdentityDAL;
use \StringFilter;

class VotingBLL extends BLLBase
{
    public function __construct(&$dbm=null)
    {
        parent::__construct($dbm);
        $this->dal=new VotingDAL($this->db);
    }

    public function getLastTopic()
    {
        $temp=$this->dal->getTopic();
        if(count($temp)) {
            $result=$temp[0];
            $result["Option"]=$this->dal->getOption($result["TopicId"]);
        } else {
            $result=null;
        }
        return $result;
    }

    public function getAllTopic()
    {
        return $this->dal->getTopic();
    }

    public function getTopic($id)
    {
        $result=$this->dal->getTopic($id);
        if(count($result)) {
            $result=$result[0];
            $result["Option"]=$this->dal->getOption($id);
        } else {
            $result=null;
        }
        return $result;
    }

    public function vote($topic,$option,$uiid)
    {
        $uidal=new UserIdentityDAL($this->db);
        $log=new \Log();
        if(!$this->dal->topicExist($topic)) {
            $log->add("議題不存在!");
        } elseif(!$this->dal->optionExist($topic,$option)) {
            $log->add("選項不存在!");
        } elseif(!$uidal->uiidExist($uiid)) {
            $log->add("識別碼不存在!");
        } elseif($uidal->uiidUsed($uiid)) {
            $log->add("識別碼已被使用!");
        } elseif($uidal->uiidTopic($uiid)!=$topic) {
            $log->add("識別碼與議題不符合!");
        } else {
            $this->dal->vote($option);
            $uidal->useUiid($uiid);
            $log->add("投票成功!");
        }
        return $log;
    }

    public function addTopic($name,$desc,$enable,$options)
    {
        $desc=StringFilter::textareaInput($desc);
        $enable=$enable=="t"?"1":"0";
        $id=$this->dal->addTopic($name,$desc,$enable);
        foreach($options as $option) {
            $this->dal->addOption($id,$option);
        }
        return $id;
    }
}
