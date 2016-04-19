#ABFCommon
##基础应用框架
=====
###1、CI框架
####1.1、框架目录
```php
├-apps    //目录:(后台目录)
├---cache   //缓存
├---config    //配置 (database.php: 对应环境的数据库链接,routes.php: 路由控制,隐藏访问路径)
├---controllers   //总控制类
├---core    //核心类 (对system/core核心类的继承扩展)
├---helpers   //相关帮助方法 (这里方法定义要判断是否存在以避免定义异常)
├---hooks   //钩子扩展,在加载节点进行回调(不建议使用,节点回调会影响项目整体操作)
├---language    //国际化支持chinese/english (默认的语言加载项)
├---libraries   //引入的类库
├---logs    //日志记录(图片上传和日志所有的目录内容)
├---migrations    //数据库迁移相关的处理
├---models    //数据库的ORM处理类
├---modules   //功能模块目录(核心目录)
├------config   //模块配置
├------controllers    //请求控制
├------libraries    //类库
├------models   //数据模型
├------views    //界面视图  (类似于MVC实现)
├---third_party   //第三方组件
├---views   //通用的界面视图封装
├public   //目录,系统访问目录,系统入口文件,日志,缓存目录,静态资源
├system   //codeigniter目录,-使用的codeingiter基础组件,如需扩展可到apps/core目录调整
└vendor   //目录,提供给composer工具进行模块可拆卸配置(类似于java里面的ant/maven等构建工具)
```
