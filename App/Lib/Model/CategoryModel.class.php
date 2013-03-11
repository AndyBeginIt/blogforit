<?php
class CategoryModel extends Model{
	//自动验证
	protected $_validate = array(
			array('category','require','必须填写类别名！'),
			);
	
	//自动完成
	protected $_auto = array(
			array('datetime','getDatetime',1,'callback'),
			);
	
	function getDatetime()
	{
		return date('Y-m-d H:i:s',time());
	}
}