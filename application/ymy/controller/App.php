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
        dump($username);
        $matches = $this->model->findClosest($username);
        // foreach ($matches as &$match)
        // {
        //     $match['events'] = $eventToChinese[$match['event']];
        //     $match['disorders'] = $disorderToChinese[$match['disorder']];
        // }
        dump($matches);
        return $this->fetch('findChat', [
            'menuTitle' => 'App',
            'subTitle' => 'findChat',
            'matches' => $matches,
        ]);
    }

    public function findAdvisorChat()
    {
    }

    public function updateSettings()
    {
        $updData = [];
        $updData['username'] = $this->request->post('username');
        $updData['events'] = $this->request->post('events');
        $updData['disorders'] = $this->request->post('disorders');
        $updRes = $this->model->updSettings($updData);
        if (!$updRes) exit(json_encode(['code' => -1, 'message' => '更新失败']));
        return $this->fetch('changeSettings', [
            'menuTitle' => 'App',
            'subTitle' => 'changeSettings',
        ]);
    }
}