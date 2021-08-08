<?php

namespace app\ymy\controller;

use app\ymy\model\AppModel;
use think\Config;
use think\Cookie;

class App extends Base
{
    // public $username;
    public function __construct()
    {
        parent::__construct();
        $this->model = new AppModel();
    }

    public function index()
    {
        return $this->fetch('index', [
            'menuTitle' => 'App',
            'subTitle' => 'index',
        ]);
    }

    public function loginDo()
    {
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $valRes = $this->model->validateUser($username, $password);
        if (!$valRes) return response(-1, '用户不存在或密码错误！');
        // cookie('username', $username, 3600);
        $opt = ['expire' => time()+3600];
        Cookie::set('username', $username, $opt);
        return response(1, '登陆成功');
    }

    public function findChat()
    {
        // $username = Cookie::get('username');
        $username = $this->username;
        // dump($username);
        $matches = $this->model->findClosest($username);
        // foreach ($matches as &$match)
        // {
        //     $match['events'] = $eventToChinese[$match['event']];
        //     $match['disorders'] = $disorderToChinese[$match['disorder']];
        // }
        // dump($matches);die;
        return $this->fetch('findChat', [
            'menuTitle' => 'App',
            'subTitle' => 'findChat',
            'matches' => $matches[0],
            'advisors' => $matches[1],
        ]);
    }

    public function findAdvisorChat()
    {
    }

    public function settings()
    {
        $username = Cookie::get('username');
        $model = new AppModel();
        $avatar = $model->db->table('user')->where(['name' => $username])->find()['avatar_url'];
        $events = $this->model->getEvents($username);
        $disorders = $this->model->getDisorders($username);
        return $this->fetch('settings', [
            'menuTitle' => 'App',
            'subTitle' => 'settings',
            'username' => $username,
            'events' => $events,
            'disorders' => $disorders,
            'avatar' => $avatar,
        ]);
    }

    public function updateSettings()
    {
        $updData = [];
        $username = Cookie::get('username');
        if (empty($this->request->post('username'))) return response(-1, '用户名不能为空！');
        $updData['name'] = $this->request->post('username');
        $updData['events'] = $this->request->post('events');
        $updData['disorders'] = $this->request->post('disorders');
        $updRes = $this->model->updateSettings($username, $updData);
        if (!$updRes) return response(-1, '保存失败');
        return response(1, '保存成功');
    }

    public function chat()
    {
        // if ($this->request->isAjax()) Config::set('default_ajax_return','html');
        $username = Cookie::get('username');
        $who = $_GET['who'];
        $result = $this->model->getMessages($username, $who);
        $findRes = $this->model->findClosest($username);
        $closest = $findRes[0];
        $advisors = $findRes[1];
        // $people = array_merge($closest, $advisors);
        // dump($people);die;
        // $advisors = $this->model->findAdvisors($username);
        $people = json_encode(array_merge($closest, $advisors), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        // dump($people);
        // dump(array_merge($closest, $advisors));
        $avatar = $result['avatar']['whoAvatar'];
        // dump($result['messages']);
        $messages = json_encode($result['messages'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $this->assign('avatar', $avatar);
        $this->assign('who', $who);
        $this->assign('user', $username);
        $this->assign('messages', $messages);
        $this->assign('people', $people);
        return $this->fetch('chat', [
            'menuTitle' => 'App',
            'subTitle' => 'chat',
        ]);
    }

    public function newMessage()
    {
        $newMessage = [];
        // $newMessage['sender'] = 'ymy';
        // $newMessage['receiver'] = 'lyt';
        // $newMessage['time'] = '2021-8-8 4:5:1';
        // $newMessage['text'] = 'hi';
        $newMessage['sender'] = $this->request->post('user');
        $newMessage['receiver'] = $this->request->post('who');
        $newMessage['time'] = $this->request->post('time');
        $newMessage['text'] = $this->request->post('text');
        $res = $this->model->newMessage($newMessage);
        if (!$res) return response(-1, '发送失败');
        return response(1, '发送成功');
    }
}