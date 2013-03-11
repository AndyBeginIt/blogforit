<?php
class BlogViewModel extends ViewModel{
	public $viewFields = array(
			'Blog'	=>	array('id','title','content','tags','datetime','click','active','comment_conf'),
			'Category'	=>	array('id'=>'c_id','category','_on'=>'Blog.c_id=Category.id')
			);
}