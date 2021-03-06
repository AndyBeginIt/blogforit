<?php
class ViewAction extends CommonAction{
	/**
	 * 首页
	 */
	 function index($b_id='')
	 {
	 	//查看文章
	 	if(!empty($b_id))
	 	{
	 		//实例化模型
	 		$BlogModel = D("BlogView");
	 		$BlogAdvModel = M("AdvModel:Blog");
	 		
	 		//使用memcache
	 		import("ORG.Util.CacheMemcache");
	 		$Memcache = new CacheMemcache();
	 		$MemcacheCon = $Memcache->get('View'.$b_id);

	 		//查找对应博客文章
 			$where = array(
 					'id' => $b_id,
 			);
	 		//如果不存在Memcache，则从数据库获取数据
	 		if(!$MemcacheCon)
	 		{ 			
	 			$blog_list = $BlogModel->where($where)->find();
	 			$c_id = $blog_list['c_id'];
	 			$blogTitle = $blog_list['title'];
	 			//定义输出在页面的文章内容
	 			$BlogConStr = 	"<div class='con_datetime'>".$blog_list['datetime'].' '.L('bDiv_1')."</div>".
			 					"<h2 class='con_title'>".$blog_list['title']."</h2>".
			 					"<div class='con_body'>".$blog_list['content']."</div>".
			 					"</div>	";
	 			//写入Memcache
	 			$Memcache->set('View'.$b_id, $BlogConStr,3600); 	//864000	
	 			$Memcache->set('c_id'.$b_id, $c_id,3600);
	 			$Memcache->set('blogTitle'.$b_id, $blog_list['title'],3600);	
	 		}
	 		else
	 		{
	 			$BlogConStr = 'From Memcache'.$MemcacheCon;
	 			$c_id = $Memcache->get('c_id'.$b_id);
	 			$blogTitle = $Memcache->get('blogTitle'.$b_id);
	 		}
	 		
	 		//更新文章点击数,使用高级模型，每60秒进行一次数据库更新
	 		$BlogAdvModel->where($where)->setLazyInc("click",1,60);
 		
	 		//调用Commom/right_slide 			获得所有的分类列表
	 		$this->right_slide();
	 		//调用Common/get_latest_comment		获取最新评论
	 		$this->get_latest_comment();
	 		//调用Common/get_latest_tag			获取tags
	 		$this->get_latest_tag();
	 		//调用Common/generateCalendar		生成日历
			$this->generateCalendar();
	 		//调用Common/get_blog_archive		获得有博客文章归档的日期
	 		$this->get_blog_archive();

	 		//获取同分类下的5篇文章
	 		$where = array(
	 			'c_id'	=>	$c_id
	 			);
	 		$relateBlogs = $BlogModel->field('id,title')->where($where)->order('id desc')->limit(5)->select();
	 	
	 		
	 		$this->assign('BlogConStr',$BlogConStr);
	 		$this->assign('relateBlogs',$relateBlogs);
	 		$this->assign('b_id',$b_id);
	 		$this->assign('blogTitle',$blogTitle);
	 	}
	 	
	 	$this->display();
	 }
	 
 	
	/**
	 * 完成留言功能
	 */
	 function comment_done()
	 {
	 	if($this->isPost())
	 	{	
	 		$commentModel = D('Comment');
	 		if(!!$data = $commentModel->create())
	 		{
	 			if($commentModel->add())
	 			{
	 				//return all data to js
	 				$data['datetime'] = date('Y-m-d H:i:s',$data['datetime']);
	 				$return = $data;
	 				$return['success'] = true;
	 			}
	 			else
	 			{	
	 				$return['success'] = false;
	 				$return['msg'] = '回复失败，请联系管理员';
	 			}
	 		}
	 		else
	 		{
	 			$return['success'] = false;
	 			$return['msg'] = $commentModel->getError();
	 		}
	 		echo json_encode($return);
	 	}
	 }
	
	//get comment
	 function getComment()
	 {
	 	if (isset($_POST['blogId'])) 
	 	{
	 		$blogId = $_POST['blogId'];
	 		$commentModel = D('Comment');
	 		//防止SQL注入
	 		$where = "blogId = %d";
	 		$data = $commentModel->field('avatar,name,content,datetime')->where($where,array($blogId))->order('id desc')->select();
	 		foreach ($data as $key => $value) {
	 			$data[$key]['datetime'] = date('Y-m-d H:i:s',$data[$key]['datetime']);
	 		}
	 		echo json_encode($data);
	 	}
	 }


	 /**
	  * edit blog content
	  */
	 function edit()
	 {	
	 	if($this->isGet())
	 	{
	 		$b_id = (int)$_GET['b_id'];
	 		$blogViewModel = D('BlogView');
	 		$where = 'Blog.id = %d';
	 		$list = $blogViewModel->where($where,array($b_id))->order('id desc')->find();
	 		$this->assign('blogList',$list);

	 		//得到该用户的所有分类名称
			$categoryModel = D('Category');
	 		$cateList = $categoryModel->field('id,category')->select();
			//得到该用户下面使用过的标签
			$TagsModel = D('Tags');
			$tag_list = $TagsModel->field('id,tag')->where($where)->limit(45)->order('id desc')->select();

			$this->assign('cateList',$cateList);
			$this->assign('tag_list',$tag_list);

	 		//调用Commom/right_slide 			获得所有的分类列表
	 		$this->right_slide();
	 		//调用Common/get_latest_comment		获取最新评论
	 		$this->get_latest_comment();
	 		//调用Common/get_latest_tag			获取tags
	 		$this->get_latest_tag();
	 		//调用Common/generateCalendar		生成日历
			$this->generateCalendar();
	 		//调用Common/get_blog_archive		获得有博客文章归档的日期
	 		$this->get_blog_archive();

	 		$this->display();
	 	}
	 	
	 }


	 /**
	  * Update blog
	  */
	 function update_post()
	 {
	 	if($this->isPost())
	 	{
	 		$id = $this->_post('id');
	 		$blogModel = D('Blog');
	 		if($data = $blogModel->create($_POST,'update'))
	 		{	
	 			if($blogModel->save($data))
	 			{	
	 				$this->redirect('blog/'.$id);
	 			}
	 			else
	 			{
	 				$this->error('更新出错，请联系管理员');
	 			}
	 		}
	 		else
	 		{
	 			$this->error($blogModel->getError());
	 		}
	 	}
	 }
	 
}