<?php
class CommonAction extends Action{
	/**
	 * 自动加载函数
	 */
	function _initialize()
	{
		header("Content-Type:text/html; charset=utf-8");
		//如果用户没登陆，则让用户登陆
		if(!isset($_SESSION['username']))
		{
			$this->redirect('Admin/Index/index');
		}
		//传值到MODULE_NAME 到 top.html 判断url
		$this->assign('module_name',MODULE_NAME);	
	}

	/**
	 * 上传函数,得到上传成功后的信息
	 */
	function getUploadInfo(){
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Public/Uploads/';// 设置附件上传目录
		$upload->saveRule	=	uniqid;
		if( !$upload->upload() ) {
			// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{
			// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			return $info;
		}
	}
	
	/**
	 * 自定义跳转
	 * @param string $type success Or error
	 * @param string $url 跳转路径
	 * @param string $content 错误信息
	 */
	function xz_redirect($type,$url='',$content)
	{
		if($url !== '')
		{
			$this->assign('jumpUrl',$url);
		}
		
		if ($type == 'success')
		{
			$this->success($content);
		}
		else
		{
			$this->error($content);
		}
	}
}