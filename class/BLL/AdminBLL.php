<?php
namespace BLL;

use BLL\BLLBase;
use DAL\AdminDAL;
use \SessionManager;

class AdminBLL extends BLLBase
{
    public function __construct(&$dbm=null)
    {
        parent::__construct($dbm);
        $this->dal=new AdminDAL($this->db);
    }

    // admin login
    public function logIn($admin,$password)
    {
        $password=\StringFilter::hash($password);
        if(!$this->dal->hasAdmin($admin,$password)) {
            return false;
        } else {
            SessionManager::set("Admin","1");
            return true;
        }
    }

    // check if is logged in
    public static function isLogIn()
    {
        return SessionManager::has("Admin");
    }

    // logout
    public static function logOut()
    {
        SessionManager::clear();
    }
}
