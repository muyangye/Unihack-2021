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

    public function getIdByUsername($username)
    {
        $where['name'] = $username;
        return $this->db->table('user')->where($where)->find()['id'];
    }

    public function getEvents($username)
    {
        $where['name'] = $username;
        return $this->db->table('user')->where($where)->find()['events'];
    }

    public function getDisorders($username)
    {
        $where['name'] = $username;
        return $this->db->table('user')->where($where)->find()['disorders'];
    }

    public function validateUser($username, $password)
    {
        $where['name'] = $username;
        $data = $this->db->table('user')->where($where)->find();
        if (empty($data)) return false;
        if ($data['password'] != md5($password)) return false;
        return true;
    }

    // 找到与自己经历最相似的受害者
    public function findClosest($username)
    {
        $allUsers = $this->db->table('user')->field(['name', 'role', 'events', 'disorders'])->select();
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
        $result = $advisors = [];
        foreach ($allUsers as $user)
        {
            // 自己不能匹配到自己
            if ($user['name'] == $username) continue;
            // Advisor存在另一个数组里
            if ($user['role'] == 'advisor')
            {
                $advisors [] = $user;
                continue;
            }
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
        return [$result, $advisors];
    }

    public function findAdvisors()
    {

    }

    public function updateSettings($username, $updData)
    {
        $id = $this->getIdByUsername($username);
        // dump($id);
        $where['id'] = $id;
        return $this->db->table('user')->where($where)->update($updData);
    }

    // username是当前登陆账户的名字，who是对方的名字
    public function getMessages($username, $who)
    {
        $avatar = [];
        // 先联表查询头像
        $where1 ['name'] = $username;
        $where2 ['name'] = $who;
        $avatar ['myAvatar'] = $this->db->table('user')->where($where1)->find()['avatar_url'];
        $avatar ['whoAvatar'] = $this->db->table('user')->where($where2)->find()['avatar_url'];
        $messages = [];
        $allMessages = $this->db->table('message')->select();
        foreach ($allMessages as $message)
        {
            // 假设自己不能跟自己对话
            if ($message['sender'] == $username)
            {
                $temp ['text'] = $message['text'];
                $temp ['time'] = $message['time'];
                $temp ['send'] = true;
                $messages [] = $temp;
            }
            if ($message['receiver'] == $username)
            {
                $temp['text'] = $message['text'];
                $temp ['time'] = $message['time'];
                $temp['send'] = false;
                $messages [] = $temp;
            }
        }
        return ['messages' => $messages, 'avatar' => $avatar];
    }
}