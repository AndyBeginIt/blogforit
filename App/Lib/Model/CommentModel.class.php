<?php
class CommentModel extends Model{
	//字段合法性检测
	protected	$insertFields = array('blogId','name','email','content','datetime','avatar');
//	protected	$updateFields = array('password','disabled');
	
	//自动验证
	protected $_validate = array(
			array('name','require','留言称号不能为空！'),
			array('email','email','留言邮件不正确！'),
			array('content','validateLen','留言内容长度不符合要求！',1,'callback'),
			);
	
	//自动完成
	protected $_auto = array(
			array('datetime','time',1,'function'),
			array('avatar','createAvatar',1,'callback'),
			);

	//Validate Comment length
	function validateLen($str)
	{
		if(strlen(trim($str)) > 10 && strlen(trim($str)) < 500)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//Create Avatar icon number
	function createAvatar()
	{
		return rand(1,96).'.jpg';
	}
}