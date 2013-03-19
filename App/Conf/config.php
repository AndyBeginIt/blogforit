<?php
//注意，请不要在这里配置SAE的数据库，配置你本地的数据库就可以了。
$arr1 = require 'database.inc.php';
$arr2 = require 'email.inc.php';

$arr3 = array(
    //'配置项'=>'配置值'
    'SHOW_PAGE_TRACE'=>true,
    'URL_HTML_SUFFIX'=>'.html',
	

	'APP_GROUP_LIST' => 'Home,Admin',
	'DEFAULT_GROUP' => 'Home',
	
	// 模板替换
	'TMPL_PARSE_STRING' => array (
			'__HOME__' => '/Public/Home',
			'__ADMIN__' => '/Public/Admin' 
	),
	
	// 默认主题
	'DEFAULT_THEME' => 'Default',
	// 开启大小写敏感
	'URL_CASE_INSENSITIVE' => false,

	//多语言配置
	'LANG_SWITCH_ON' => true,
    'DEFAULT_LANG' => 'en-us', 		// 默认语言
    'LANG_AUTO_DETECT' => true, 	// 自动侦测语言
    'LANG_LIST'=>'en-us,zh-cn',		//必须写可允许的语言列表

    //Url 
    'URL_MODEL'	=>	2,

    //URL路由,重定义每个路径的url
    'URL_ROUTER_ON'   => true, //开启路由
	'URL_ROUTE_RULES' => array( 
		//定义路由规则
		'blog/edit/:b_id'			=>		'View/edit',
	    'blog/:b_id'		=>		'View/index',	
	    'category/:c_id' 	=>		'Index/index',
	  	'tag/:tags'			=>		'Index/index',
	  	'archive/:date'		=>		'Index/index',
	),
	
);

return array_merge($arr1,$arr2,$arr3);
?>