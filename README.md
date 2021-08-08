# PsyChonnect：创伤事件后心理援助平台

## What inspires us

近些年来，世界变化莫测。在最近的两年里，各种各样的变化也对人们的身心健康提出了更加严峻的考验。大到影响世界的新冠疫情，暴雨，森林火灾，到性侵，诽谤等等难以对社会整体造成很大影响但深刻影响着个体的事情。

而并不广为人知的是，在遭遇过这些创伤性事件后，很多人们出现了各种情况的心理问题。

根据 *Mental health today* 的文章 [*Communication is a key ingredient in mental health recovery*](https://www.mentalhealthtoday.co.uk/blog/therapy/communication-is-a-key-ingredient-in-mental-health-recovery) 表示，

> *Effective communication is essential in building rapport and developing therapeutic relationships.*

而由于社会鲜有人关注这方面的问题，很多人没有合适的交流或者宣泄途径，进而导致短期的心理创伤演变为心理疾病。

**PsyChonnect**的建立旨在为经历过心理创伤群体提供一个互相倾诉和帮助，以及获取外界关注和援助的平台。并有专业的心理咨询人员入驻，对受助者的心理状况进行干预。

## What it does

心理互助平台主要由以下部分组成：

- 登陆界面
- 注册界面
- 匹配界面
- 聊天界面

## How to use

1. 首先进入注册界面，输入姓名，年龄，所经历过的对心理健康产生影响的时间，以及自我认知的心理健康方面的问题。
2. 根据您输入的信息，系统会运行匹配算法，并在屏幕上根据匹配程度从高到低展示出适合沟通的人选（demo中默认为契合度最高的五个人）。
3. 浏览他们的经历并选择想要沟通的人选，在聊天框输入你想对ta说的话，开始愉快的聊天吧！
4. 在左侧的sidebar可以切换聊天的对象。
5. 与之前对象的记录会保存，在每次重新进入和ta的会话时聊天历史会全部加载。

## How we build it

1. ThinkPHP
  - 采用MVC设计模式
  - Controllers有如下:
    - Controller: ThinkPHP框架自带的基础Controller
    - Base: 在Controller基础上新增name属性及cookie验证
    - Login: 注册及登录相关操作
    - App: 主应用，聊天、修改档案相关操作
  - Models有如下:
    - Model: ThinkPHP框架自带的基础Model
    - BaseModel: 在Model基础上新增数据库配置
    - LoginModel: 注册及登录相关操作
    - App: 主应用，聊天、修改档案相关操作
  - Views
    - Public: header.html, footer.html, 事实上project中并没有真正用到header和footer, 这两个文件仅用来引入公用css和js
    - Login: register.html, login.html
    - App: findChat.html, chat.html, settings.html, index.html原作为入口文件但项目放弃入口，test.html为debug文件

2. MySQL：数据都比较有结构，所以采用关系型数据库

   - `user` 存储用户信息，
     - `name`：用户姓名
     - `create_time`：账号创建日期
     - `password`：密码
     - `disorders`：心理异常症状
     - `events`：创伤经历
     - `avator_url`：用户头像url
     - `role`：身份为受助者or咨询师

   - `message` 存储用户间的通信消息，
     - `sender`：消息发送人
     - `receiver`：消息接受人
     - `time`：发送时间
     - `text`：消息内容

## Accomplishments that we're proud of

- 36小时内写出一个基本的网站
- 前后端大量代码的编写及协商传参，积累开发经验

## What to expect && some problems

- 登录cookie修复，用primary key(id)匹配而不是名字(数据表已有primary key id列，但后端没有实现)
- 头像支持上传而不是url
- 用户表新增别的列，用于更好地匹配，目前匹配算法仅为1.5 * 相同经历数 + 1 * 相同创伤数
- 引入advirsor模块，advisor会填写自己擅长的领域，系统会据此与受助者进行匹配，为他们提供更专业细致的帮助。
- 引入moments模块（类似朋友圈），允许匿名或实名发布动态，分享自己的心理创伤经历，以及采取何种方法来逐渐克服的。
- 新增推送功能，邀请advisor根据不同心理疾病总结的应对措施，并根据用户画像进行推送。

## What we learned
- Teaaaaaammmmwork!
- 登录界面的设计，如何存储登录数据(cookie)
- "伪聊天"系统的数据库设计
- ajax和form action和window.location.href三种跳转及传输数据到后端的区别
- ThinkPHP框架中fetch与ajax联用会把html自动变成字符串，这个框架的"bug"无法修正，可能作者觉得这样不make sense，最终直接改为其他方式
- 不应该根据名字去查数据，否则就会出现比如修改名字后聊天记录丢失的bug(现在就有)
- 