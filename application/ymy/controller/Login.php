<?php

namespace app\ymy\controller;

use think\Controller;
use app\ymy\model\LoginModel;

class Login extends Controller
{
    // public $username;
    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->model = new LoginModel();
    }

    public function login()
    {
        return $this->fetch('login', [
            'menuTitle' => 'Login',
            'subTitle' => 'login',
        ]);
    }

    // public function loginDo()
    // {
    //     // $username = $_POST['username'];
    //     // $password = $_POST['password'];
    //     $valRes = $this->model->validateUser($username, $password);
    //     if (!$valRes) exit(json_encode(['code' => -1, 'message' => '用户不存在或密码错误！'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
    //     // $_COOKIE['username'] = $username;
    //     $_SESSION['username'] = $username;
    //     $this->redirect('ymy/App/index');
    // }

    public function register()
    {
        return $this->fetch('register', [
            'menuTitle' => 'Login',
            'subTitle' => 'register',
        ]);
    }

    public function registerDo()
    {
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        if (empty($username)) return response(-1, '用户名不能为空！');
        if (empty($password)) return response(-1, '密码不能为空！');
        $registerRes = $this->model->register($username, $password);
        if ($registerRes) return response(1, '注册成功');
        else return response(-1, '注册失败');
    }

    public function afterLogin()
    {
        return 'success!';
    }
}