<?php
//注意，请不要在这里配置SAE的数据库，配置你本地的数据库就可以了。
$arr1 = require 'database.inc.php';
$arr2 = require 'email.inc.php';

$arr3 = array(
    //'配置项'=>'配置值'
    'SHOW_PAGE_TRACE'=>true,
    //'URL_HTML_SUFFIX'=>'.html',
	

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
	
);

return array_merge($arr1,$arr2,$arr3);
?>