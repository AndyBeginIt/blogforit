<?php
class CommonAction extends Action{
	/**
	 * 自动加载函数
	 */
	function _initialize()
	{
		header("Content-Type:text/html; charset=utf-8");
		//得到用户的type
		$UsersModel = D("Users");
		if(isset($_SESSION['u_id']))
		{
			$info = $UsersModel->field('type')->getById($_SESSION['u_id']);
			$this->assign('u_type',$info['type']);
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
	
	/**
	 * 自定义数据插入或更新
	 * @param  $tableName  表名
	 * @param  $data	     数据
	 * @param  $type	  类型：1代表插入(默认值),2代表更新	     
	 * @return 插入成功返回ID,插入失败返回false
	 */
	function xz_insert_db($tableName,$data='',$type=1)
	{
		$model = D($tableName) ? D($tableName) : M($tableName);
		if( $model->create($data,$type) !== false)
		{
			if($type == 1)
			{
				$id = $model->add();
			}
			else
			{
				$id = $model->save();	
			}
			
			if($id)
			{
				return $id;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$this->error($model->getError());
		}
	}
	
	/**
	 * 自定义查找
	 * @param  $tableName string 查找的表名
	 * @param  $where	   array 查找的where语句
	 * @param  $order	   string 排序的字段
	 * @param  $field	   string 要查找的字段
	 * @return 查询成功返回array,查询失败返回false
	 */
	function xz_select_db($tableName,$where=array(),$order='',$field='')
	{
		$model = D($tableName) ? D($tableName) : M($tableName);
		$list = $model->where($where)->field($field)->order($order)->select();
		if($list)
		{
			return $list;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 自定义删除数据
	 * @param  $tableName  要删除数据的表名
	 * @param  $where	   删除数据的where语句
	 * @return 删除成功返回true,查询失败返回false
	 */
	function xz_delete_db($tableName,$where)
	{
		$model = D($tableName) ? D($tableName) : M($tableName);
		if($model->where($where)->delete())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 获取所有的分类列表,自动分配变量$cates_list到Tpl中
	 */
	function right_slide()
	{
		//获得缓存数据
		$cates_list = S('cates_list');
		//如果不存在缓存，则获得数据并写入缓存
		if(!$cates_list)
		{
			$BlogModel = D("Blog");
			//查询当前用户所有分类
			$cates_list = $this->xz_select_db('Category',array(),'id desc','id,category');
			foreach ($cates_list as $key=>$val)
			{
				//得到每一种分类的总博客数
				$cates_list[$key]['count'] = $BlogModel->where( array('c_id' => $val['id']) )->count();
			}
			//写入缓存
			S('cates_list',$cates_list,3600);
		}
		
		$this->assign('cates_list',$cates_list);
	}
	
	/**
	 * 获取所有最新文章回复,自动分配变量$com_list到Tpl中
	 */
	function get_latest_comment()
	{
		
		//获得缓存数据
		$com_list = S('com_list');
		//如果不存在缓存，则获得数据并写入缓存
		if(!$com_list)
		{
			$CommentModel = D("Comment");
			$com_list = $CommentModel->order('id desc')->limit(5)->select();
			//写入缓存
			S('com_list',$com_list,3600);
		}
		$this->assign('com_list',$com_list);
		
	}
	
	/**
	 * 获得最近使用的标签
	 */
	function get_latest_tag()
	{
		//获得缓存数据
		$tags_list = S('tags_list');
		//S('tags_list',null);
		//如果不存在缓存，则获得数据并写入缓存
		if(!$tags_list)
		{
			$TagsModel = D("Tags");
			$tags_list = $TagsModel->order('id desc')->limit(45)->select();
			//写入缓存
			S('tags_list',$tags_list,3600);
		}				

		$this->assign('tags_list',$tags_list);
	}
	

	/**
	 * 生成日历
	 * @return [type] [description]
	 */
	function generateCalendar()
	{
		import('@.Action.Calendar');
		$year = date('Y');
		$month = date('F');
		$obj = new calendar($month,$year);
		$calendar = $obj->generateCalendar();
		$this->assign('calendar',$calendar);
	}


	/**
	 * 获得有博客文章归档的日期
	 */
	function get_blog_archive()
	{
		//获得缓存数据
		$dateArchiveArr = S('dateArchiveArr');
		//如果不存在缓存，则写入缓存
		if(!$dateArchiveArr)
		{
			$dateArchiveArr = array();
			$start_year = 2012;
			$start_month = 10;
			$cur_year = date('Y');
			$cur_month = date('m');
			//如果当前年与第一篇文章的发布年相同
			if($start_year == $cur_year)
			{
				for($start_month;$start_month<=$cur_month;$start_month++)
				{
					array_push($dateArchiveArr, $start_year.'-'.$start_month);
				}
			}
			//如果当前年大于第一篇文章的发布年，则将月数转为大于12的数字进行运算
			else
			{
				$cur_month_total = ($cur_year-$start_year)*12 + $cur_month;
				for ($start_month;$start_month<=$cur_month_total;$start_month++)
				{
					if($start_month > 12)
					{
						$tmp_year_num = floor($start_month/12);		
						//如果月小于10，则前面补0
						$tmp_cur_month = $start_month%12;
						$tmp_cur_month = strlen($tmp_cur_month)==1 ? '0'.$tmp_cur_month : $tmp_cur_month;				
						array_push($dateArchiveArr, ($start_year+$tmp_year_num).'-'.$tmp_cur_month);
					}
					else
					{
						array_push($dateArchiveArr, $start_year.'-'.$start_month);
					}
				}
			}

			$BlogModel = D("Blog");
			foreach($dateArchiveArr as $key=>$val)
			{
				$where = array(
					'datetime' => array('like','%'.$val.'%'),
					);
				$bools = $BlogModel->where($where)->limit(1)->find();
				//如果该年月不存在文章，则在归档数组中删除该年月
				if(empty($bools))
				{
					unset($dateArchiveArr[$key]);
				}
			}
			
			//写入缓存，缓存时间为1天
			S('dateArchiveArr',$dateArchiveArr,86400);
		}
		$this->assign('dateArchiveArr',	array_reverse($dateArchiveArr));
	}
	
}