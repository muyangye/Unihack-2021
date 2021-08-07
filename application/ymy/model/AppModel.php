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

    public function findClosest($username)
    {
        $allUsers = $this->db->table('user')->field(['name', 'events', 'disorders'])->select();
        // dump($this->db->getLastSql());
        $myEvents = $myDisorders = [];
        // 两次循环，避免重查数据库
        foreach ($allUsers as $user)
        {
            if ($user['name'] == $username)
            {
                $myEvents = explode(',', $user['events']);
                $myDisorders = explode(',', $user['disorders']);
            }
        }
        $result = [];
        foreach ($allUsers as $user)
        {
            // 自己不能匹配到自己
            if ($user['name'] == $username) continue;
            $eventsIndex = $disordersIndex = 0;
            if (empty($myEvents) || empty($user['events'])) $eventsIndex = 0;
            else
            {
                $userEvents = explode(',', $user['events']);
                foreach ($myEvents as $myEvent)
                {
                    if (in_array($myEvent, $userEvents)) $eventsIndex += 1;
                }
            }
            if (empty($myDisorders) || empty($user['disorders'])) $disordersIndex = 0;
            else
            {
                $userDisorders = explode(',', $user['disorders']);
                foreach ($myDisorders as $myDisorder)
                {
                    if (in_array($myDisorder, $userDisorders)) $disordersIndex += 1;
                }
            }
            // 优先匹配拥有相同经历的
            $result [] = array_merge($user, ['index' => 1.5 * $eventsIndex + $disordersIndex]);
        }
        // 最相近的五个
        $indices = array_column($result, 'index');
        array_multisort($indices, SORT_DESC, $result);
        if (count($result) > 5) $result = array_slice($result, 0, 5);
        return $result;
    }
}