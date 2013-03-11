<?php
class ManageAction extends CommonAction{
	/**
	 * 显示后台首页
	 */
	function index(){
		$commentModel = D('Comment');
		$comList = $commentModel->order('id desc')->limit(8)->select();

		$this->assign('comList',$comList);
		$this->display();
	}
}