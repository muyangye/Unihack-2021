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
    //     $username = $_POST['username'];
    //     if (!isset($_COOKIE['username'])) {
    //         $res = setcookie('username', $username, time()+3600, '/', 'hack.cn');
    //         echo "setcookie 成功，刷新下页面";
    //         var_dump($_COOKIE);
    //         var_dump($res);die;
    //     } else {
    //         echo "已设置cookie";
    //         // var_dump($_COOKIE);
    //         // 让cookie失效，
    //         setcookie('username', $username, time()-3600, '/', 'hack.cn');
    //         // thinkphp redirect
    //         // header("/ymy/App/findChat");
    //         $this->redirect('/ymy/App/findChat');
    //     }
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
        $events = $disorders = [];
        if (!empty($this->request->post('covid'))) $events [] = 'covid';
        if (!empty($this->request->post('rainstorm'))) $events [] = 'rainstorm';
        if (!empty($this->request->post('typhoon'))) $events [] = 'typhoon';
        if (!empty($this->request->post('sexual'))) $events [] = 'sexual';
        if (!empty($this->request->post('PTSD'))) $disorders [] = 'PTSD';
        if (!empty($this->request->post('depression'))) $disorders [] = 'depression';
        if (!empty($this->request->post('anxiety'))) $disorders [] = 'anxiety';
        $registerRes = $this->model->register($username, $password, $events, $disorders);
        if ($registerRes) return response(1, '注册成功');
        else return response(-1, '注册失败');
    }

    public function afterLogin()
    {
        return 'success!';
    }
}