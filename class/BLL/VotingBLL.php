<?php
namespace BLL;

use BLL\BLLBase;
use DAL\VotingDAL;
use DAL\UserIdentityDAL;
use \StringFilter;
use \Log;

class VotingBLL extends BLLBase
{
    private $uiidBase=[];

    public function __construct(&$dbm=null)
    {
        parent::__construct($dbm);
        $this->dal=new VotingDAL($this->db);
    }

    // get the latest topic
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

    //get the topic list
    public function getAllTopic()
    {
        return $this->dal->getTopic(null,false);
    }

    // get a topic by id
    public function getTopic($id)
    {
        $result=$this->dal->getTopic($id,false);
        if(count($result)) {
            $result=$result[0];
            $result["Option"]=$this->dal->getOption($id);
        } else {
            $result=null;
        }
        return $result;
    }

    // get all uiid related to a topic
    public function getUiid($topic)
    {
        $uidal=new UserIdentityDAL($this->db);
        return $uidal->getUiid($topic);
    }

    // vote for an option
    public function vote($topic,$option,$uiid)
    {
        $uidal=new UserIdentityDAL($this->db);
        $log=new Log();
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

    // add a new topic
    public function addTopic($name,$desc,$enable,$options)
    {
        $desc=StringFilter::textareaInput($desc);
        $enable=$enable=="t"?"1":"0";
        $id=$this->dal->addTopic($name,$desc,$enable);
        $this->addOption($id,$options);
        return $id;
    }

    // edit a topic
    public function editTopic($id,$name,$desc,$enable)
    {
        $log=new Log();
        if(!$this->dal->topicExist($id)) {
            $log->add("議題不存在!");
        } else {
            $desc=StringFilter::textareaInput($desc);
            $enable=$enable=="1"?"1":"0";
            $this->dal->editTopic($id,$name,$desc,$enable);
            $log->add("修改完成!");
        }
        return $log;
    }

    // delete a topic
    public function deleteTopic($id)
    {
        $uidal=new UserIdentityDAL($this->db);
        $this->dal->deleteTopic($id);
        $this->dal->deleteOption($id);
        $uidal->deleteTopicUiid($id);
    }

    // add a uiid into db
    public function addUiid($topic,$number)
    {
        $uidal=new UserIdentityDAL($this->db);
        $log=new Log();
        if(!is_numeric($number)) {
            $log->add("格式錯誤!");
        } elseif(!$this->dal->topicExist($topic)) {
            $log->add("議題不存在!");
        } else {
            for($i=0;$i<=9;$i++) {      //0~9
                $this->uiidBase[]=$i;
            }
            for($i=97;$i<=122;$i++) {    //a~z
                $this->uiidBase[]=sprintf("%c", $i);
            }
            $number=intval($number);
            for($i=0;$i<$number;$i++) {
                $uiid=$this->generateUiid();
                if($uidal->uiidExist($uiid)) {
                    $i--;
                    continue;
                } else {
                    $uidal->addUiid($topic,$uiid);
                }
            }
            $log->add("新增".$number."個識別碼成功!");
        }
        return $log;
    }

    // add a comment for an uiid
    public function memoUiid($uiid,$memo)
    {
        $uidal=new UserIdentityDAL($this->db);
        $log=new Log();
        if(!$uidal->uiidExist($uiid)) {
            $log->add("識別碼不存在!");
        } else {
            $uidal->memoUiid($uiid,$memo);
            $log->add("修改備註完成!");
        }
        return $log;
    }

    // delete an uiid
    public function deleteUiid($uiid)
    {
        $uidal=new UserIdentityDAL($this->db);
        $log=new Log();
        $uidal->deleteUiid($uiid);
        $log->add("刪除識別碼成功!");
        return $log;
    }

    // rename an option
    public function renameOption($id,$name)
    {
        $log=new Log();
        if(!$this->dal->optionExist(null,$id)) {
            $log->add("選項不存在!");
        } else {
            $this->dal->renameOption($id,$name);
            $log->add("修改完成!");
        }
        return $log;
    }

    public function deleteOption($id)
    {
        $log=new Log();
        if(!$this->dal->optionExist(null,$id)) {
            $log->add("選項不存在!");
        } else {
            $this->dal->deleteOptionFromId($id);
            $log->add("刪除完成!");
        }
        return $log;
    }

    // add an option
    public function addOption($topic,$options)
    {
        $log=new Log();
        if(!$this->dal->topicExist($topic)) {
            $log->add("議題不存在!");
        } else {
            foreach($options as $option) {
                if(!(empty($option)&&$option!=0)) {
                    $this->dal->addOption($topic,$option);
                }
            }
            $log->add("新增選項成功!");
        }
        return $log;
    }

    // genereate an uiid
    private function generateUiid()
    {
        $uiid="";
        for($i=0;$i<64;$i++) {
            $uiid.=$this->uiidBase[rand(0,35)];
        }
        return $uiid;
    }
}
