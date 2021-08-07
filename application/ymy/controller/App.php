<?php

namespace app\ymy\controller;

use app\ymy\model\AppModel;

class App extends Base
{
    public $username;
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
        // dump($username);
        $valRes = $this->model->validateUser($username, $password);
        if (!$valRes) return response(-1, '用户不存在或密码错误！');
        cookie('username', $username, 3600);
        return response(1, '登陆成功');
    }

    public function moments()
    {
        dump(cookie('username'));
        return $this->fetch('test', [
            'menuTitle' => 'App',
            'subTitle' => 'test',
        ]);
    }

    public 
}