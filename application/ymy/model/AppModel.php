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

    public function findClosest($username)
    {
        $allUsers = $this->db->table('user')->field(['name', 'events', 'disorders'])->select();
        dump($this->db->getLastSql());
        $myEvents = $myDisorders = [];
        // 两次循环，避免重查数据库
        foreach ($allUsers as $user)
        {
            if ($user['name'] == $username)
            {
                $myEvents = $user['events'];
                $myDisorders = $user['disorders'];
            }
        }
        $result = [];
        // dump($allUsers);
        foreach ($allUsers as $user)
        {
            // 自己不能匹配到自己
            if ($user['name'] == $username) continue;
            $eventsIndex = $disordersIndex = 0;
            if (empty($myEvents) || empty($user['events'])) $eventsIndex = 0;
            else
            {
                foreach ($myEvents as $myEvent)
                {
                    if (array_search($myEvent, $user['events'])) $eventsIndex += 1;
                }
            }
            if (empty($myDisorders) || empty($user['disorders'])) $disordersIndex = 0;
            else
            {
                foreach ($myDisorders as $myDisorder)
                {
                    if (array_search($myDisorder, $user['disorders'])) $disordersIndex += 1;
                }
            }
            // 优先匹配拥有相同经历的
            $result [] = array_merge($user, ['index' => 1.5 * $eventsIndex + $disordersIndex]);
        }
        return $result;
    }
}