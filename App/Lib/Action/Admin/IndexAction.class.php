<?php
class IndexAction extends Action {
	/**
	 * 管理员登陆
	 */
	function login()
	{
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		$where = array(
				'username' => $username,
				'password' => md5($password),
				);
		$adminModel = D('Administrator');
		$bools = $adminModel->where($where)->count();
		if($bools)
		{
			session('username',$username);
			$this->redirect("Manage/index");
		}
		else
		{
			$this->xz_redirect('error','', '用户名或密码出错，请重新登陆');
		}
	}

	 /**
     * 登出
     */
    function logout()
    {
    	session('username',null);
    	$this->redirect('Admin/Index/index');
    }
}