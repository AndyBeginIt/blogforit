<?php
class TestAction extends Action{
	function index()
	{
		echo 'test';		
	}
	
	
	function test()
	{
		$model = D("Category");
		$data['id'] = 2;
		$data['category']  = 'new';
		$data['x']=1;
	
		$model->save($data);
	}
}