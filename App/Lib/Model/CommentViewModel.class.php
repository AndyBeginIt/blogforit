<?php
class CommentViewModel extends ViewModel{
	public $viewFields = array(
			'Comment'	=>	array('id','name','email','content','datetime'),
			'Blog'	=>	array('title'=>'blogTitle','_on'=>'Blog.id=Comment.blogId')
			);
}