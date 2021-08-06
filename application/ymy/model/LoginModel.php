<?php
namespace app\ymy\model;

use think\Db;
use think\Model;

class LoginModel
{
    public $db;
    public function __construct()
    {
        $this->db = Db::connect();
    }

    public function getIdByName($name)
    {
        $where['name'] = $name;
        return $this->db->table('user')->where($where)->find();
    }

    public function register($username, $password)
    {
        $data = [];
        $data['name'] = $username;
        $data['password'] = md5($password);
        $registerRes = $this->db->table('user')->insert($data);
        return $registerRes;
    }

    public function validateUser($username, $password)
    {
        $where['name'] = $username;
        $data = $this->db->table('user')->where($where)->find();
        if (empty($data)) return false;
        if ($data['password'] != md5($password)) return false;
        return true;
    }
}