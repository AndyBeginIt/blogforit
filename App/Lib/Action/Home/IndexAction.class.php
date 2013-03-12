<?php
class IndexAction extends CommonAction {
	/**
	 * 首页
	 */
	function index($c_id='') {	
		//查询所有的文章
		$BlogViewModel = D("BlogView");
		//导入分页数据
		import("ORG.Util.Page");

		$tags = isset($_GET['tags']) ? $_GET['tags'] : '';
		$archive_date = isset($_GET['date']) ? $_GET['date'] : '';
		$search = isset($_GET['search']) ? $_GET['search'] : '';
	
		if( !empty($archive_date) )
		{
			//如果存在归档文章查询
			$where = array(	
				'datetime' => array('like','%'.$archive_date.'%'),	
				'active'	=>	1,
				);
			$count = $BlogViewModel->where($where)->count();
		}
		elseif (!empty($search)) 
		{
			//如果存在搜索，则查询所有的博客标题
			$where = array(
				'title'	=>	array('like','%'.$search.'%') ,
				'active'	=>	1,
				);
			$count = $BlogViewModel->where($where)->count();
		}
		elseif (!empty($tags)) 
		{
			//如果存在查询标签，则查询所有的标签
			$where = array(
				'tags'	=>	array('like','%'.$tags.'%'),
				'active'	=>	1,
				);
			$count = $BlogViewModel->where($where)->count();
		}
		else
		{
			//如果不存在搜索，则根据找到所有博客文章
			//如果不存在分类Id,则找到所有的博客文章
			if(empty($c_id))
			{
				$where = array('active'	=>	1,);
				$count = $BlogViewModel->count();
			}
			else
			{
				$where = array(
					'c_id'	=>	array('eq',$c_id),
					'active'	=>	1,
					);
				$count = $BlogViewModel->where($where)->count();						
			}
		}

		//分页显示
		$Page  = new Page($count,C('PAGESIZE'));	
		$blog_list = $BlogViewModel->order('id desc')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		//分页配置
		$tmpArr = explode('/', $_SERVER['PATH_INFO']);
		$Page->url = $tmpArr[1].'/'.$tmpArr[2].'/p/';
		$Page->setConfig('theme'," <span class='up_page'>%upPage%</span> <span class='down_page'>%downPage% </span>");
		$Page->setConfig('prev','← '.L('page_prev'));
		$Page->setConfig('next',L('page_next').' →');
		$show  = $Page->show();

		//获得每篇文章的回复数
		$commentModel = D("Comment");
		foreach ($blog_list as $key => $val) 
		{
			$blog_list[$key]['commentNum'] = $commentModel->where(array('blogId'	=>	array('eq',$val['id'])))->count();
		}		
		
		//调用Commom/right_slide 			获得所有的分类列表
		$this->right_slide();
		//调用Common/get_latest_comment		获取最新评论
		$this->get_latest_comment();
		//调用Common/get_latest_tag			获取tags
		$this->get_latest_tag();
		//调用Common/get_blog_archive		获得有博客文章归档的日期
		$this->get_blog_archive();
		
		$this->assign('show',$show);
		$this->assign('blog_list',$blog_list); 
		$this->display();
	}
	
	/**
	 * 完成注册用户
	 */
/*	function finish_register() {
		$email = trim($_POST['email']);
		$nickname = trim($_POST['nickname']);
		$uid = $this->xz_insert_db ( 'Users' );
		if ($uid) {
			//session data
			$_SESSION['email'] = $email;
			$_SESSION['u_id'] = $uid;
			$_SESSION['nickname'] = $nickname;
			
			$this->redirect('Index/account_set');
		} else {
			$this->xz_redirect('error','','注册失败，请联系管理员');
		}
	}*/
	
	/**
	 * 找回密码处理
	 */
	function get_pass() {
		//得到信息文件中的邮件标题和内容
		$infoArr = F ( 'informations.inc', '', CONF_PATH . 'Home/' );
		
		$email = trim ( $_POST ['email'] );
		$title = $infoArr ['MAIL_TITLE'];
		$message = $infoArr ['MAIL_MESSAGE'];
		
	 	$bools = SendMail ( $email, $title, $message );
		if($bools)
		{
			$this->xz_redirect('success',U('Index/index'),'请查看邮件重置修改密码！');
		}
		else
		{
			$this->xz_redirect('error','','邮件发送失败，请联系管理员');
		} 
	}
	
	/**
	 * 用户登录
	 */
	function finish_login()
	{
	
		$UsersModel = D("Users");
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		
		$where = array(
				'email' => $email,
				'password' => md5($password)
		);
		$info = $UsersModel->field('id')->where($where)->find();
		$UserId = $info['id'];
		
		if($UserId)
		{
			//缓存用户电子邮件和用户ID,Nickname
			session('email',$email);
			session('u_id',$UserId);
			
			$this->redirect("/Index/index");
		}
		else
		{
			$this->xz_redirect('error','', '登入失败');
		}
	}
	
	/**
	 * 用户登出
	 */
	function logout()
	{
		//删除Session
		session('email',null);
		session('u_id',null);
		
		$this->redirect("/Index/index");
	}
	
	
	/**
	 * 插入传输过来的分类名称到数据库 
	 */
	function insert_cate()
	{
		$data['category'] = trim($_POST['category']);
		$data['u_id'] = $_SESSION['u_id'];
		$c_id = $this->xz_insert_db('Category',$data);
		//如果插入成功，则返回插入的数据
		if($c_id)
		{
			$data['c_id'] = $c_id;
			$data['success'] = 1;
			echo json_encode($data);
		}
		else
		{
			echo '';
		}
	}
	
	/**
	 * 删除分类名
	 */
	 function del_cate()
	 {
		$c_id = $this->_post("c_id");
		$where = array(
			'id' => array('eq',$c_id)
		);
		$bools = $this->xz_delete_db("Category",$where);
		$ret['success'] = $bools;
		$ret['c_id'] = $c_id;
		echo json_encode($ret);
	 }
	 
	 /**
	  * 得到所有分类名称
	  */
	  function getAllCate()
	  {
		$where = array(
			'u_id' => array('eq',$_SESSION['u_id'])
		);
		$info=$this->xz_select_db('Category',$where);
		echo json_encode($info);
	  }
	  
	  /**
	   * 更新传过来的分类名
	   */
	  function up_cate()
	  {
	  	$data['id'] = $_POST['c_id'];
	  	$data['category'] = $_POST['category'];
	  	$ret = $this->xz_insert_db("Category",$data,2);
	  	echo $ret; 
	  }
	  
	 
	/**
	 * 得到发布内容插入数据库
	 */
	function finish_post()
	{
		//如果提交了表单
		if($this->isPost())
		{
			//将Tag插入
			$TagsModel = D("Tags");
			$tagData['u_id'] = $_SESSION['u_id'];
			foreach ( $_POST['tag'] as $val)
			{
				$tagData['tag'] = $val;
				$TagsModel->add($tagData);
			}
		
			$tag = '';
			foreach($_POST['tag'] as $val)
			{
				$tag .= trim($val).',';
			}
			foreach($_POST['exist_tag'] as $val)
			{
				$tag .= trim($val).',';
			}
			
			$_POST['tags'] = rtrim($tag,',');				
		 	$ret_id = $this->xz_insert_db('Blog');
			if($ret_id)
			{
				$this->redirect("/View/index/b_id/".$ret_id);
			}
			else
			{
				$this->xz_redirect('error',U('Index/post_article'),'文章创建失败！请联系管理员！');
			} 
		}		
	}
		
	/**
	 * 完成密码修改
	 */
	function finish_modify_pass()
	{
		if (!isset($_SESSION['email']) && !isset($_SESSION['u_id']) )
		{
			$this->xz_redirect('error',U('Index/login'), '请登录');
		}
		$old_pass = trim($_POST['old_pass']);
		$new_pass = trim($_POST['new_pass']);
		$re_new_pass = trim($_POST['re_new_pass']);
		if(empty($new_pass) || empty($re_new_pass) || empty($old_pass))
		{
			$this->error('密码不能为空');
		}
		//判断旧密码是否正确
		$UsersModel = D("Users");
		$where = array(
				'id' => $_SESSION['u_id'],
				);
		$u_info = $UsersModel->field('password')->where($where)->find();
		if( $u_info['password'] == md5($old_pass) )
		{
			//进行密码更新
			if($new_pass == $re_new_pass)
			{
				$data['password'] = md5($new_pass);
				if( $UsersModel->where($where)->save($data) )
				{
					$this->xz_redirect('success', U('Index/index') ,'密码修改成功');
				}
				else
				{
					$this->error('修改失败，请联系管理员！');
				}
			}
			else
			{
				$this->error('二次输入的密码不同！');
			}
		}
		else
		{
			$this->error('请输入正确的旧密码！');
		}
	}
	
	/**
	 * 查询已设置头像
	 */
	function account_set()
	{
		if (!isset($_SESSION['email']) && !isset($_SESSION['u_id']) )
		{
			$this->xz_redirect('error',U('Index/login'), '请登录');
		}
		$UserModel = D("Users");
		$where = array(
				'id' => $_SESSION['u_id']
				);	
		$u_info = $UserModel->where($where)->field('icon')->find();
		$this->assign('icon',$u_info['icon']);
		$this->display();
	}

	
	/**
	 * 完成账号设置
	 */
	function finish_account_set()
	{
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();		// 实例化上传类
		$upload->maxSize  = 5242880 ;	// 设置附件上传大小
		$upload->saveRule  = uniqid ;	// 设置命名规则
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');	// 设置附件上传类型
		$upload->thumb	=  true;    // 使用对上传图片进行缩略图处理
		$upload->thumbMaxWidth     = '50' ;	// 缩略图最大宽度
		$upload->thumbMaxHeight    = '50' ;	// 缩略图最大高度
		$upload->savePath =  './Public/Uploads/';	// 设置附件上传目录
	
		if( !$upload->upload() )
		{
			// 上传错误提示错误信息
			$data = array(
					'status' => 'failed',
					'error_msg' => $upload->getErrorMsg()
					);
			$this->ajaxReturn($data);
		}
		else
		{
			// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo(); 
			$data['icon'] = $info[0]['savename'];
			$where = array(
					'id' => $_SESSION['u_id'],
					);
			//更新用户数据库
			$UsersModel = D("Users");
			if( $UsersModel->where($where)->save($data) )
			{
				$data['status'] = 'success';
				$this->ajaxReturn($data);
			}
			else
			{
				$data = array(
						'status' => 'failed',
						'error_msg' => '文件上传失败，请联系管理员'
						);
				$this->ajaxReturn($data);
			}
		}
	}

	/**
	 * 设置只有用户登录才可以进行发布文章
	 */
	function post_article()
	{
		if (!isset($_SESSION['email']) && !isset($_SESSION['u_id']) )
		{
			$this->xz_redirect('error',U('Index/login'), '请登录');
		}
		//得到该用户的所有分类名称
		$where = array(
				'u_id' => $_SESSION['u_id'],
		);
		$list = $this->xz_select_db("Category",$where);
	
		//得到该用户下面使用过的标签
		$TagsModel = D('Tags');
		$tag_list = $TagsModel->field('id,tag')->where($where)->limit(45)->order('id desc')->select();
	
	
		//调用Commom/right_slide 			获得所有的分类列表
		$this->right_slide();
		//调用Common/get_latest_comment		获取最新评论
		$this->get_latest_comment();
		//调用Common/get_latest_tag			获取tags
		$this->get_latest_tag();
		//调用Common/get_blog_archive		获得有博客文章归档的日期
		$this->get_blog_archive();
	
		$this->assign('list',$list);
		$this->assign('tag_list',$tag_list);
		$this->display();
	}
	
	
	/**
	 * 关于我们
	 */
	function about()
	{
		//调用Commom/right_slide 获得所有的分类列表
		$this->right_slide();
		//调用Common/get_latest_comment获取最新评论
		$this->get_latest_comment();
		//调用Common/get_latest_tag获取TAGS
		$this->get_latest_tag();
		//调用Common/get_blog_archive		获得有博客文章归档的日期
		$this->get_blog_archive();
		
		$this->display();
	}
	
	/**
	 * 联系我们
	 */
	function contact()
	{
		$this->about();
	}
	
}