<?php
class NormalAction extends CommonAction{
	/**
	 * 显示前台设置页面
	 */
	function frontconf(){
		//得到前台配置文件
		$configs	=	F('informations.inc','','./App/Conf/Home/');
		$this->assign('configs',$configs);
		$this->display();
	}
	
	/**
	 * 完成前台页面的设置
	 */
	function finish_frontconf(){
		unset($_POST['__hash__']);
		//将得到的文件写入到normal.conf.php配置文件里面
		$bools	=	F('informations.inc',$_POST,'./App/Conf/Home/');
		if($bools)
		{
			$this->assign('jumpUrl',U('Admin/Normal/frontconf'));
			$this->success('更新成功');
		}
		else
		{
			$this->error('更新失败，请联系管理员');
		}
	}
	
	/**
	 * 显示后台设置页面
	 */
	function backendconf(){
		//得到前台配置文件
		$configs	=	F('informations.inc','','./App/Conf/Admin/');
		$this->assign('configs',$configs);

		$this->display();
	}
	
	/**
	 * 完成后台页面的设置
	 */
	function finish_backendconf(){
		unset($_POST['__hash__']);
		//将得到的文件写入到normal.conf.php配置文件里面
		$bools	=	F('informations.inc',$_POST,'./App/Conf/Admin/');
		if($bools)
		{
			$this->assign('jumpUrl',U('Admin/Normal/backendconf'));
			$this->success('更新成功');
		}
		else
		{
			$this->error('更新失败，请联系管理员');
		}
		
	}
	
	/**
	 * 清除缓存
	 */
	function clearCache(){
		$CachePath	=	'./App/Runtime/';
		$bools	=	delDir($CachePath);
		if( $bools ){
			$this->assign('jumpUrl',U('Admin/Manage/index'));
			$this->success('清除缓存成功');
		}else{
			$this->assign('jumpUrl',U('Admin/Manage/index'));
			$this->success('清除缓存失败');
		}
	}
	
}