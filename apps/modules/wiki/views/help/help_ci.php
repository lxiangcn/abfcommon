		<div class="row">
			<div class="col-md-3" role="complementary">
				<div class="list-group affix help-nav">
					<a href="<?php echo site_url('wiki/ci')?>#ci" class="list-group-item disabled">1、CI框架</a>
					<a class="list-group-item" href="<?php echo site_url('wiki/ci')?>#link1-1">1.1、框架目录</a>
					<a class="list-group-item" href="<?php echo site_url('wiki/ci')?>#link1-2">1.2、框架模块</a>
					<a class="list-group-item" href="<?php echo site_url('wiki/ci')?>#link1-3">1.3、测试用例</a>
				</div>
			</div>
			<div class="col-md-9" role="main">
			    <a class="btn btn-success btn-xs m_ml25" href="<?php echo site_url('wiki/db')?>">下一章</a>
    			<a class="btn btn-success btn-xs m_ml5" href="<?php echo site_url('wiki')?>">返回</a>
				<div class="help">
					<h1 id="ci" class="page-header">
						1、CI框架
					</h1>
					<h2 id="link1-1">
						1.1、框架目录
					</h2>
						<pre>
apps目录:(后台目录)
    cache       缓存 (tpl页面实际生成的真实访问页面)
    config      配置 (*/database.php: 对应环境的数据库连接   routes.php: 路由控制,隐藏访问路径    gravity.php: 访问权限配置)
    controllers 总控制类 (test目录: 测试用例目录     *.php  系统相关控制类,会优先读取)
    core        核心类 (对sys/core核心类的继承扩展)
    helpers     相关帮助方法 (这里方法定义要判断是否存在以避免定义异常)
    hooks       钩子扩展,在加载节点进行回调(不建议使用,节点回调会影响项目整体操作)
    language    国际化支持chinese/english (默认的语言加载项)
    libraries   引入的类库(包括登陆DX_Auth和tpl模板smarty_parser)
    logs        日志记录(图片上传和日志所有的目录内容)
    migrations  数据库迁移相关的处理
    models      数据库的ORM处理类
    modules     功能模块目录(核心目录)
                config模块配置    
                controllers请求控制	
                libraries类库   
                models数据模型    
                views界面视图
                (类似于MVC实现)
    third_party	第三方组件
    views	通用的界面视图封装
public目录:(前端目录)
    data	文件目录 
		(包含上传文件，日志，缓存目录)
    		(文件访问和静态资源的入口,这里所有操作都通过index.php处理,
    		根据不同的请求参数include不同的界面,其他目录不允许外部访问))
system目录:
    使用的codeingiter基础组件,如需扩展可到apps/core目录调整
vendor目录:
    提供给composer工具进行模块可拆卸配置(类似于java里面的ant/maven等构建工具)
						</pre>

					<h2 id="link1-2">
						<a class="anchorjs-link " href="#link1.2" aria-label="Anchor link for: grunt commands" data-anchorjs-icon="" style="font-family: anchorjs-icons; font-style: normal; font-variant: normal; font-weight: normal; position: absolute; margin-left: -1em; padding-right: 0.5em;"></a>
						1.2、框架模块
					</h2>
					<p class="lead">目录：apps/modules/模块名</p>
					<pre>
<b>controller:</b>
    <i>定义并使得url地址解析信息，继承自apps/core/MY_Controller.php，一个简单示例如下：</i>
    <i>MY_Controller.php定义有前台和后台，以及其他集成类Admin_Controller.php、
    	Web_Controller.php、Other_Controller.php
    	Admin_Controller.php为管理后他继承类
    	Web_Controller.php为前台继承类
    	Other_Controller.php为其他非关键继承类</i>
    <i>a.在apps/modules下新建一个demo目录(demo模块),在demo目录新建controllers目录(控制层)</i>
    <i>b.在控制层controllers目录下新建一个Mydemo.php文件(控制类)，内容如下：</i>
    <i class="i1">&lt;?php defined('BASEPATH') || exit('No direct script access allowed');
    class MyDemo extends Admin_Controller{
        public function __construct(){
            parent::__construct();
        }
        <i class="i2">//当未指定调用方法时会默认调用控制类的index方法</i>
        public function index(){
            <i class="i2">//php中调用类里面的方法需要使用$this变量</i>
            echo $this-&gt;render('welcome to huiber CodeIgniter framework.');
        }
        //定义一个私有方式，给需要显示的消息内容加上部分样式
        private function render($msg){
            header("Content-type: text/html; charset=utf-8"); <i class="i2">//定义字符集</i>
            return '&lt;h3 style="color:red; text-align:center; margin:25px;"&gt;'.$msg.'&lt;/h3&gt;';
        }
    }
    <i class="i2">//说明:这里没有&lt;?php对应的关闭符号&gt;是为了避免多文件模板可能导致的加载异常</i>
	</i>
    <i>c.访问测试地址：<a target="_blank" href="<?php echo site_url('demo/mydemo')?>"><?php echo site_url('demo/mydemo')?></a></i>
    <i>d.在控制类Mydemo.php中增加一个可以带参数的自定义方法，方法示例如下：</i>
    <i class="i1">
    <i class="i2">//带参数的自定义方法</i>
    public function hello($name){
        echo $this->render('say hello to guest['.$name.'].');
    }</i>
    <i>f.访问测试地址：<a target="_blank" href="<?php echo site_url('demo/mydemo/hello/tom')?>"><?php echo site_url('demo/mydemo/hello/tom')?></a>
						</i>
    <i class="i2">关于Controller类简单示例至此结束,下一节将带来Model类简单示例</i>
</pre>
					<pre>
<b>model:</b>
    <i>定义数据库和逻辑层的操作，继承自apps/core/MY_Model.php，一个简单示例如下(<i class="i2">数据库操作在下一章介绍</i>)：</i>
    <i>a.在apps/modules/demo目录下新建一个models目录(models模块)</i>
    <i>b.在模型层models目录下新建一个Mydemo_model.php文件(模型类)，内容如下：</i>
    <i class="i1">&lt;?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }
    class MyDemo_Model extends MY_Model{
        public function __construct(){
            parent::__construct();
        }
        <i class="i2">//简单的示例方法，提供给Controller类调用</i>
        public function when($name){
            $now = date('%Y-%m-%d %H:%i:%s',time());
            return 'hi, '.$name.', now is ['.$now.']';
        }
    }</i>
    <i>c.在控制类mydemo.php中的初始化方法中加入load方法加载模型类，并定义一个测试方法，代码如下：</i>
    <i class="i1">...
    public function __construct(){
        parent::__construct();
        <i class="i2">$this-&gt;load-&gt;model('demo/MyDemo_Model');</i>
    }
    //测试model类的方法
    public function when(){
        <i class="i2">$when = $this-&gt;MyDemo_Model-&gt;when();</i>
        echo $this->render($when);
    }
    ...</i>
    <i>d.测试访问地址：<a target="_blank" href="<?php echo site_url('demo/mydemo/when')?>"><?php echo site_url('demo/mydemo/when')?></a>
						</i>
    <i class="i2">关于Model类简单示例至此结束,下一节将带来Config、language、view模块的简单示例</i>
</pre>

					<pre>
<b>config:</b>
    <i>定义配置项的内容</i>
    <i>a.在apps/modules/demo目录下新建一个config文件夹(配置目录),在目录下新建mydemo.php文件(配置文件)，示例内容如下：</i>
    <i class="i1">&lt;?php defined('BASEPATH') || exit('No direct script access allowed');
    $config['sys_version'] = '1.0';    <i class="i2">//定义系统版本号的配置值</i>
    </i>
    <i>b.在模型类mydemo_model.php的初始化方法中load配置文件，然后在调用方法中获取配置项的值，示例代码如下：</i>
    <i class="i1">...
    public function __construct(){
        parent::__construct();
        <i class="i2">$this-&gt;load-&gt;config('demo/mydemo');</i>
    }
    public function when(){
        $now = date('Y-m-d H:i:s',time());
        <i class="i2">$ver = $this-&gt;config-&gt;item('sys_version');</i>
        return 'now is ['.$now.'],current system version is ['.$ver.']';
    }
    ...</i>
    <i>c.测试访问地址：<a target="_blank" href="<?php echo site_url('demo/mydemo/when')?>"><?php echo site_url('demo/mydemo/when')?></a></i>
    <i class="i1">
    注：所有的模块需配置模块基本信息
    创建info.php，并填写信息
    &lt;?php
    defined('BASEPATH') || exit('No direct script access allowed');
    
    $config['version'] = '1.0';			<i class="i2">//定义模块版本号</i>
    $config['module_name'] = '模块管理';		<i class="i2">//定义模块名称</i>
    $config['description'] = '基本管理平台';	<i class="i2">//定义模块描述信息</i>
    </i>
    </pre>
    <pre>
<b>language:</b>
    <i>定义国际化支持</i>
    <i>a.在apps/modules/demo目录下新建一个language文件夹(国际化目录),目录下新建zh_CN目录存放中文化,english英文</i>
    <i>b.在语言支持目录下，新建mydemo_lang.php，示例内容如下(这里仅列出chinese目录下内容)：</i>
    <i class="i1">&lt;?php
    $lang['LANG_TEST'] = '测试language模块。';</i>
    <i>b.在模型类mydemo_model.php中使用国际化，先在初始化方法中load配置文件，示例代码如下：</i>
    <i class="i1">...
    public function __construct(){
        parent::__construct();
        $this->load->config('demo/mydemo');
        <i class="i2">$this->load->language('demo/mydemo');</i>
    }
    public function when(){
        $now = date('Y-m-d H:i:s',time());
        $ver = $this->config->item('sys_version');
        <i class="i2">$lang = $this->lang->line('LANG_TEST');</i>
        return 'now is ['.$now.'],current system version is ['.$ver.'], language test ['.$lang.']';
    }
    ...</i>
    <i>c.测试访问地址：<a target="_blank" href="<?php echo site_url('demo/mydemo/when')?>"><?php echo site_url('demo/mydemo/when')?></a></i>
	</pre>
    <pre>
<b>view:</b>
    <i>定义模块的tpl文件,在界面中可加载对应文件，可使用this-&gt;data存储数据加载到tpl文件</i>
    <i>a.在apps/modules/demo目录下新建一个views文件夹(视图目录),在目录下新建demo.tpl文件,内容如下：</i>
    <i class="i1">
    <i class="i2">//复制如下内容时，请去除$msg两边的空格</i>
    &lt;html&gt;
      &lt;head&gt;
        &lt;meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /&gt;  
      &lt;/head&gt;
      &lt;body&gt;
      &lt;h3 style="text-align:center; margin:25px; color:green;"&gt;测试消息：{ $msg }&lt;/h3&gt;
      &lt;/body&gt;
    &lt;/html&gt;</i>
    <i class="i2">(说明：这里$msg通过this-&gt;data属性获取)</i>
    b.在控制类mydemo.php里面增加对视图的测试方法，内容如下：
    <i class="i1">
	<i class="i2">//测试视图tpl文件的方法</i>
    public function view(){
         $tpl = realpath(APPPATH).'/modules/demo/views/demo.tpl';
         $this->data['msg'] = $this->MyDemo_Model->when();
         render_tpl($tpl, $this->data, false);  <i class="i2">//方法定义见apps/helpers/crm_helper.php</i>
    }</i>
    <i>c.测试访问地址：<a target="_blank" href="<?php echo site_url('demo/mydemo/view')?>"><?php echo site_url('demo/mydemo/view')?></a></i>
</pre>
		<h2 id="link1-3">
			1.3、测试用例
		</h2>
		<pre>
<b>apps/controllers/test目录</b>
    <i>定义测试用例，可在命令行下批量执行,简单示例：</i>
    <i>a.在apps/controllers/test下新建文件z_demo_tests.php，内容如下：</i>
    <i class="i1">&lt;?php
    require_once(APPPATH . '/controllers/test/Toast.php');
    class Z_demo_tests extends Toast{
        function __construct(){
            parent::__construct(__FILE__);
        }
        <i class="i2">//测试方法</i>
        public function test_when(){
            $this->load->model('demo/MyDemo_Model');
            $when = $this->MyDemo_Model->when();
            $this->myEcho($when);
        }
        private function myEcho($msg){
            header("Content-type: text/html; charset=utf-8");
            echo '&lt;span style="color:red; text-align:left; margin-left:25px;"&gt;'.$msg.'&lt;/span&gt;';
        }
    }</i>
    <i>b.在apps/config/gravity.php中配置测试用例访问权限，这里使用<i class="i1">z_demo_tests=&gt;'*'</i>,表示无限制</i>
    <i>c.访问测试地址：<a target="_blank" href="<?php echo site_url('test/z_demo_tests')?>"><?php echo site_url('test/z_demo_tests')?></a></i>
	</pre>
		</div>
		<p class="m_tc m_mt25 m_green">本章节结束，下一章节将介绍Model的数据库相关操作
		    <a class="btn btn-primary btn-xs m_ml15" href="<?php echo site_url('wiki/db')?>">下一章</a>
		</p>
	</div>
</div>