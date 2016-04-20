一个简单的PHP框架<br />
框架架构图:<br />
action  控制器<br />
│   ├── pc pc控制器（也可以在下面建立Wap控制器）<br />
│   │   └── Test_ActionIndex.class.php  pc控制器<br />
│   └── WebBaseAction.class.php   控制器中间层<br />
├── common 配置目录<br />
│   ├── config.php 系统配置，路由，DB，常量，LOG……<br />
│   └── Action Action基础类<br />
├── db  数据库基础类<br />
│   ├── Db.class.php 数据库操作类（基类）<br />
│   └── DbWrapper.class.php 数据库包装类<br />
├── fe 模板 <br />
│   ├── static<br />
│   │   ├── css CSS样式<br />
│   │   │   └── demo.css<br />
│   │   ├── images 图片 <br />
│   │   │   └── demo.jpg<br />
│   │   └── js JS代码<br />
│   │       └── demo.js<br />
│   └── templates 模板<br />
│       └── test 不同项目区分不同的块（这里是测试）<br />
│           └── test.tpl<br />
├── index.php 项目主入口<br />
├── module 数据层<br />
│   ├── UserDao.class.php<br />
│   └── UserService.class.php<br />
├── phplib 核心类<br />
│   ├── framework 框架类<br />
│   │   ├── Action.class.php Action抽象类<br />
│   │   ├── ActionController.class.php 操作控制器 提供URI路由行动<br />
│   │   ├── Application.class.php Application入口<br />
│   │   └── Context.class.php 模块基础类<br />
│   ├── log Log类<br />
│   │   └── CLog.class.php<br />
│   └── smarty 模板类<br />
├── README.md<br />
└── utils 系统方法<br />
    ├── ResourceFactory.class.php Smarty工厂类<br />
    ├── TableService.class.php 分表分库方法<br />
    └── Utils.class.php 常用方法<br />
<br />

