<?php

namespace app\ymy\controller;

use app\ymy\model\AppModel;

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
        cookie('username', $username, 3600);
        return response(1, '登陆成功');
    }

    public function findChat()
    {
        $username = cookie('username');
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
        $username = cookie('username');
        $events = $this->model->getEvents($username);
        $disorders = $this->model->getDisorders($username);
        return $this->fetch('settings', [
            'menuTitle' => 'App',
            'subTitle' => 'settings',
            'username' => $username,
            'events' => $events,
            'disorders' => $disorders,
        ]);
    }

    public function updateSettings()
    {
        $updData = [];
        $username = cookie('username');
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
        $result = $this->model->getMessages('ymy', 'lyt');
        dump($result);
        return ; 
    }
}