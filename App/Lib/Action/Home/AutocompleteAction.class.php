<?php
class AutocompleteAction extends Action
{
	//得到文章列表
	function suggestion()
	{
		//如果小于2个字符，则不进行搜索
		$term = trim($_POST['term']);
		if(strlen($term) < 2)
		{
			break;
		}
		
		//查找博客文章数据库获得类似名字
		$BlogModel = D("Blog");
		$where = array(
			'title' => array('like','%'.$term.'%')	
				);
		$list = $BlogModel->field('title')->where($where)->order('id desc')->limit(15)->select();
		
		$keywords['title'] = array();
		foreach ( $list as $val )
		{
			array_push($keywords['title'], $val['title']);
		}
		$this->ajaxReturn($keywords);
	}
}