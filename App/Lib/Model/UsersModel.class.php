<?php
class UsersModel extends Model{
	//字段合法性检测
	/* protected	$insertFields = array('email','password','repassword');
	protected	$updateFields = array('password','disabled'); */
	
	//自动验证
	protected $_validate = array(
			array('email','require','电子邮件必须填写！'),
			array('email','email','电子邮件格式错误！'),
			array('nickname','require','昵称必须填写！'),
			array('password','require','密码必须填写！'),
			array('repassword','password','确认密码不正确！',0,'confirm'),
			);
	
	//自动完成
	protected $_auto = array(
			array('datetime','getDatetime',1,'callback'),
			array('password','md5',1,'function') ,
			);
	
	function getDatetime()
	{
		return date('Y-m-d H:i:s',time());
	}
}