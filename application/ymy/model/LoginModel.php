<?php
namespace app\ymy\model;

use think\Db;
use think\Model;

class LoginModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIdByName($name)
    {
        $where['name'] = $name;
        return $this->db->table('user')->where($where)->find();
    }

    public function register($username, $password, $avatar, $role, $events, $disorders)
    {
        $data = [];
        $data['name'] = $username;
        $data['password'] = md5($password);
        $data['avatar_url'] = $avatar;
        $data['role'] = $role;
        $data['events'] = implode(',', $events);
        $data['disorders'] = implode(',', $disorders);
        $registerRes = $this->db->table('user')->insert($data);
        return $registerRes;
    }
}