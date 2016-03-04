#ABF
##基础应用框架
=====
###1、CI框架
####1.1、框架目录
>
apps目录:(后台目录)<br/>
├--cache-----缓存 (tpl页面实际生成的真实访问页面)<br/>
├--config-----配置 (*/database.php: 对应环境的数据库连接   routes.php: 路由控制,隐藏访问路径    gravity.php: 访问权限配置)<br/>
├--controllers-----总控制类 (test目录: 测试用例目录     *.php  系统相关控制类,会优先读取)<br/>
├--core-----核心类 (对sys/core核心类的继承扩展)<br/>
├--helpers-----相关帮助方法 (这里方法定义要判断是否存在以避免定义异常)<br/>
├--hooks-----钩子扩展,在加载节点进行回调(不建议使用,节点回调会影响项目整体操作)<br/>
├--language-----国际化支持chinese/english (默认的语言加载项)<br/>
├--libraries-----引入的类库(包括登陆DX_Auth和tpl模板smarty_parser)<br/>
├--logs-----日志记录(图片上传和日志所有的目录内容)	<br/>
├--migrations-----数据库迁移相关的处理	<br/>
├--models-----数据库的ORM处理类	<br/>
├--modules-----功能模块目录(核心目录)<br/>
├------config-----模块配置<br/>
├------controllers请求控制<br/>
├------libraries-----类库<br/>
├------models-----数据模型<br/>
├------views-----界面视图<br/>
├-----------(类似于MVC实现)<br/>
├--third_party-----第三方组件<br/>
├--views-----通用的界面视图封装<br/>
├--public-----目录:(前端目录)<br/>
├--data-----文件目录 (包含上传文件，日志，缓存目录)，(文件访问和静态资源的入口,这里所有操作都通过index.php处理,根据不同的请求参数include不同的界面,其他目录不允许外部访问))<br/>
├--system-----目录:
├---------------使用的codeingiter基础组件,如需扩展可到apps/core目录调整<br/>
├--vendor-----目录:<br/>
└---------------提供给composer工具进行模块可拆卸配置(类似于java里面的ant/maven等构建工具)<br/>
>