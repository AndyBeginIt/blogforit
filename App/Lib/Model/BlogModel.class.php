<?php
class BlogModel extends Model{
	//字段合法性检测
	protected	$insertFields = array('title','content','c_id','u_id','comment_conf','tags');
//	protected	$updateFields = array('password','disabled');
	
	//自动验证
	protected $_validate = array(
			array('title','require','博客标题必须填写！'),
			array('content','require','博客内容必须填写！'),
			array('c_id','vali_cate','请选择博客类别！',0,'callback'),
			);
	
	//自动完成
	protected $_auto = array(
			array('datetime','getDatetime',1,'callback'),
			);
	
	function getDatetime()
	{
		return date('Y-m-d H:i:s',time());
	}
	
	function vali_cate($cate)
	{
		if( $cate == '-1' )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}