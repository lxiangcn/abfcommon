<div class="row">
	<div class="col-md-3" role="complementary">
		<div class="list-group affix help-nav">
			<a href="<?php echo site_url('wiki/db')?>#db" class="list-group-item disabled">2、数据实体</a>
			<a class="list-group-item" href="<?php echo site_url('wiki/db')?>#link1-1">1.1、CI实体</a>
			<a class="list-group-item" href="<?php echo site_url('wiki/db')?>#link1-2">1.2、数据基类</a>
		</div>
	</div>
	<div class="col-md-9" role="main">
		<a class="btn btn-success btn-xs m_ml25" href="<?php echo site_url('wiki/ci')?>">上一章</a>
		<a class="btn btn-success btn-xs m_ml5" href="<?php echo site_url('wiki/ui')?>">下一章</a>
		<a class="btn btn-success btn-xs m_ml5" href="<?php echo site_url('wiki')?>">返回</a>
		<div class="help">
			<h1 id="db" class="page-header">1、数据实体</h1>
			<h2 id="link1-1">1.1、CI实体</h2>
			<p class="lead">
				CI实体类在线帮助文档
				<a target="_blank" class="btn btn-primary btn-xs" href="http://codeigniter.org.cn/user_guide/database/index.html">点击查看</a>
			</p>
<pre class="list">
    <i class="i2">//实体调用方式:$this-&gt;db-&gt;<i class="i1">method()</i>;</i>
    <b>1.1SQL执行</b>
    <i>query($sql)</i>         <i>执行sql语句,如果是readsql可以继续调用显示结果集方法显示;如果是writesql返回true或者false</i>
    <i>query_simply($sql)</i>  <i>执行sql语句,返回true或者false</i>
    <b>1.2获取结果</b>
    <i class="i2">调用示例:   $query = $this-&gt;db-&gt;query($readSql);    $rt = $query-&gt;<i class="i1">method()</i>;</i>
    result              执行成功时返回<i class="i1">对象数组</i>,失败时返回空数组,用<i class="i1">elem-&gt;key</i>取值
    result_array        执行成功时返回<i class="i1">关联数组</i>,失败时返回空数组,用<i class="i1">elem['key']</i>取值
    row                 执行成功时返回第一行数据,结果为<i class="i1">对象</i>;失败时返回为空
    row_array           执行成功时返回第一行数据,结果为<i class="i1">数组</i>;失败时返回为空
    free_result         释放当前查询关联的内存和结果集
    <b>1.3辅助函数</b>
    <i>count_all($table)</i>   <i>查询指定表的记录数目</i>
    <i>insert_id()</i>         <i>获取<i class="i1">上次插入操作的记录id</i>,通常在增加自增长id记录后调用</i>
    
    <i class="i2">//Active Record模式,类似于活动记录的方式来执行SQL语句</i>
    <b>2.1查询项</b>
    <i>select($fields)</i>     <i>设置查询返回的结果项,示例: $this-&gt;db-&gt;select('name, age, sex');</i>
    <i>select_max($field)</i>  <i>设置查询对应域的最大值,类似可查询min/avg/sum等</i>
    <b>2.2查询表</b>
    <i>from($table)</i>        <i>查询指定表的记录</i>
    <i>join($table,$state,$flag='');</i> <i>关联查询表,这里$state为关联条件,$flag为可选值:left, right等</i>
    <i class="i1">get($table=null)</i>     <i class="i1">执行结果,这里表名为可选项,返回$query对象</i>
    <b>2.3查询条件</b>
    <i>where($left,$right)</i> <i>设置where条件,示例：$this-&gt;db-&gt;where('age>',18),参数可用数组array('age>',18);</i>
    <i class="i2">(类似方法有: or_where、 where_[not_]in、 or_where_[not_]in、 [not_]like  or_[not_]like)</i>
    <i>order_by($str)</i>      <i>设置排序条件</i>
    <i>limit($nums)</i>        <i>设置显示结果集的数量</i>
    <i>group_by($field)</i>    <i>设置分组选项</i>
    <i>having($str)</i>        <i>设置SQL语句中包含having条件</i>
    <i>distinct()</i>          <i>设置结果集去除重复项</i>
    <b>2.4实体操作</b>
    set($field,$val)             设置指定列名和对应的值,示例: $this-&gt;db-&gt;set('age',18);
    <i class="i2">(这里可以定义额外参数,避免$val被转义,示例:$this-&gt;db-&gt;set('age','age+1',FALSE);</i>
    insert($table,$obj)        将$obj对象存放到数据库中,当存在set方法时,这里$obj参数可省略
    update($table,$obj,$con)   将$obj对象更新到数据库中,这里$con为更新条件,示例: <i class="i2">array('age>',18)</i>;
    delete($table,$con)        将$obj对象更新到数据库中,这里$con为删除条件
    <i class="i2">(这里$obj参数可以通过set方法等价定义,$con参数可以通过where方法等价定义,也可混合使用)</i>
    
    <i class="i2">//事务相关操作</i>
    <b>3.事务处理</b>
    <i>trans_begin()</i>       <i>开启事务</i>
    <i>trans_complete()</i>    <i>提交事务</i>
    <i>trans_rollback()</i>    <i>回退事务</i>
</pre>
			<h2 id="link1-2">1.2、数据基类</h2>
			<p class="lead">
				<i>目录:apps/modules/模块名/models</i>
			</p>
<pre>
    方法定义位于apps/core/Base_Model.php中,主要是对CI实体的封装,以及相关数据库常用表的操作,调用示例: $this->method();
    <b>1.执行SQL</b>
    <i>executeReadSql($readSql)</i>   <i>执行readSQL,返回<i class="i1">关联数组</i>,失败时返回空数组或FALSE,用<i class="i1">elem['key']</i>取值</i>
    <i>executeWriteSql($writeSql)</i> <i>执行writeSQL,返回执行结果,TRUE或FALSE</i>
    
    <b>2.CRUD操作</b>
    <i>find_by($con,$select,$table)</i>        <i>查询对象,返回<i class="i1">关联数组</i>集合</i>,$con为处理条件,示例:array('age>',18)
    <i>find($selected,$table,$key,$val)</i>    <i>查询对象,返回一条记录,属性为数组,查询条件为$key=$val</i>
    <i>insert($obj,$table)</i>                 <i>插入一条记录</i>
    <i>update($obj,$con,$table)</i>            <i>更新指定记录</i>
    <i>delete($con,$table)</i>                 <i>删除指定记录</i>
    
    <b>3.事务操作</b>
    <i>transBegin()</i>                        <i>开启事务</i>
    <i>transCommit($blResult)</i>              <i>如果blResult为true则提交事务,否则回退事务</i>
    
    <b>4.常用表操作</b>
    <i class="i2">//这里strCategory可以是字符值,也可以是值的数组,这里$strCond直接用于where方法的参数</i>
    <i class="i1">a.getKVs</i>($strCategory, $strCond = '', $nInuse = 1, $strOrder = '')
    查询<b>CRM_DICT</b>表,返回指定C_CATEGORY项的值为$strCategory的关联数组集合,返回结果集格式:<i class="i2">
    array( 
       'C_CATEGORY1'=>array( 'N_KEY1'=>'N_VALUE1', 'N_KEY2'=>'N_VALUE2', .. ),
       'C_CATEGORY2'=>array( 'N_KEY1'=>'N_VALUE1', 'N_KEY2'=>'N_VALUE2', .. ),
        ..., ... 
    );</i>
    <i class="i1">b.setKVs</i>($strCategory, $arrData, $blReplace = false, $blSameOrder = false)
    设置K-V记录到<b>CRM_DICT</b>表,这里$arrData格式为:array(k1=>v1, k2=>v2, ..)
    
    <i class="i1">c.getMapping</i>($strCategory, $strCond = '', $nInuse = 1)
    查询<b>CRM_RELATION</b>表,返回指定C_CATEGORY项的值为$strCategory的关联数组集合,返回结果集格式:<i class="i2">
    array( 
       'C_CATEGORY1'=>array( $obj1,  $obj2, ... ),
       'C_CATEGORY2'=>array( $obj1,  $obj2, ... ),
        ..., ... 
    );//这里obj格式为: array( N_RID=> '', C_LEFT=>'', C_RIGHT=>'', ... ) </i>
    <i class="i1">d.setMapping</i>($strCategory, $arrLeft, $arrRight, $override=false)
    设置Map记录到<b>CRM_RELATION</b>表,这里数组$arrLeft和$arrRight数据项一一对应
</pre>
		</div>
	</div>
</div>