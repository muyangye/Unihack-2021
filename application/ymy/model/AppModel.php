<?php
namespace app\ymy\model;

use think\Db;
use think\Model;

class AppModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    public function validateUser($username, $password)
    {
        $where['name'] = $username;
        $data = $this->db->table('user')->where($where)->find();
        if (empty($data)) return false;
        if ($data['password'] != md5($password)) return false;
        return true;
    }

    public function getEvents($username)
    {
        $where['name'] = $username;
        $events = explode(',', $this->db->table('user')->where($where)->find()['events']);
        return $events;
    }

    public function getDisorders($username)
    {
        $where['name'] = $username;
        $disorders = explode(',', $this->db->table('user')->where($where)->find()['disorders']);
        return $disorders;
    }
}