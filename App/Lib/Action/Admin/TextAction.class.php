<?php
class TextAction extends CommonAction{
	/**
	 * 博客管理
	 */
	function viewBlog(){
		$blogViewModel = D('BlogView');
		import("ORG.Util.Page");

		//得到排序数据
		$sort	=	isset($_GET['sort']) ? $_GET['sort'] : '';
		$order 	= 	isset($_GET['order']) ? $_GET['order'] : '';
		$searchName = isset( $_GET['searchName'] ) ? $_GET['searchName'] : '';
		$searchVal = isset( $_GET['searchVal'] ) ? trim($_GET['searchVal']) : '';
		if( !empty($sort) )
		{
			//进行排序获得数据
			$order = $sort.' '.$order;
			$where = array();
		}
		elseif(!empty($searchName))
		{
			//搜索
			$where = array( $searchName => array('like','%'.$searchVal.'%') );
			$order = 'id desc';
		}
		else
		{
			//获得所有数据
			$where = array();
			$order = 'id desc';
		}

		$count = $blogViewModel->where($where)->count();
		$Page  = new Page($count,C('PAGESIZE'));
		$show  = $Page->show();
		$list = $blogViewModel->order($order)->where($where)->field('id,title,tags,click,active,datetime,category')->limit($Page->firstRow.','.$Page->listRows)->select();

		$this->assign('list',$list);
		$this->assign('show',$show);
		$this->display();
	}
	
	/**
	 * 创建博客
	 */
	function addBlog()
	{
		$categoryModel = D("Category");
		$cateList = $categoryModel->select();
		$this->assign('cateList',$cateList);
		$this->display();
	}

	/**
	 * 创建博客 插入数据库
	 */
	function addBlogDone()
	{
		if($this->isPost())
		{
			$blogModel = D('Blog');
			if(!!$data = $blogModel->create())
			{
				if($blogModel->add())
				{
					$this->assign('jumpUrl',U('Admin/Text/viewBlog'));
					$this->success('博文创建成功');
				}
				else
				{
					$this->error('创建博文失败，请联系管理员！');
				}
			}
			else
			{
				$this->error($blogModel->getError());
			}
		}
	}
	
	/**
	 * 删除
	 */
	function delBlog()
	{
		$blogModel = D('Blog');
		$tmpIds = '';
		foreach ($_POST['selected'] as $key => $val) 
		{
			$tmpIds .= $val.',';
		}
		$tmpIds = rtrim($tmpIds,',');
		if($blogModel->delete($tmpIds))
		{
			$this->success('删除成功');
		}
		else
		{
			$this->error('删除失败，请联系管理员');
		}
	}
	
	/**
	 * 编辑文章
	 */
	function editBlog($blogId='')
	{
		if(!empty($blogId))
		{
			$blogViewModel = D("BlogView");
			$blogList = $blogViewModel->getById($blogId);

			//得到所有分类
			$categoryModel = D("Category");
			$cateList = $categoryModel->field('id,category')->select();

			$this->assign('blogList',$blogList);
			$this->assign('cateList',$cateList);
			$this->display();
		}
	}
	
	/**
	 * 完成编辑更新
	 */
	function editBlogDone()
	{
		if($this->isPost())
		{
			$blogModel = D("Blog");
			if($data = $blogModel->create($_POST,'update'))
			{
				if($blogModel->save($data))
				{
					$this->assign('jumpUrl',U('Admin/Text/viewBlog'));
					$this->success('更新成功');
				}
				else
				{
					$this->error('更新失败，请联系管理员');
				}
			}
			else
			{
				$this->error($blogModel->getError());
			}
		}
	}
	
	
	/**
	 * 查看comment
	 */
	function viewComment()
	{
		$commentViewModel = D('CommentView');
		import("ORG.Util.Page");

		//得到排序数据
		$sort	=	isset($_GET['sort']) ? $_GET['sort'] : '';
		$order 	= 	isset($_GET['order']) ? $_GET['order'] : '';
		$searchName = isset( $_GET['searchName'] ) ? $_GET['searchName'] : '';
		$searchVal = isset( $_GET['searchVal'] ) ? trim($_GET['searchVal']) : '';
		if( !empty($sort) )
		{
			//进行排序获得数据
			$order = $sort.' '.$order;
			$where = array();
		}
		elseif(!empty($searchName))
		{
			//搜索
			$where = array( $searchName => array('like','%'.$searchVal.'%') );
			$order = 'id desc';
		}
		else
		{
			//获得所有数据
			$where = array();
			$order = 'id desc';
		}

		$count = $commentViewModel->where($where)->count();
		$Page  = new Page($count,C('PAGESIZE'));
		$show  = $Page->show();
		$list = $commentViewModel->order($order)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();

		foreach ($list as $key => $val) {
			$list[$key]['datetime']	= date("Y-m-d H:i:s",$val['datetime']);
		}

		$this->assign('list',$list);
		$this->assign('show',$show);
		$this->display();
	}
	
	/**
	 * 删除评论
	 */
	function delComment(){
		$commentModel = D('Comment');
		$tmpIds = '';
		foreach ($_POST['selected'] as $key => $val) 
		{
			$tmpIds .= $val.',';
		}
		$tmpIds = rtrim($tmpIds,',');
		if($commentModel->delete($tmpIds))
		{
			$this->success('删除成功');
		}
		else
		{
			$this->error('删除失败，请联系管理员');
		}
	}
}
?>