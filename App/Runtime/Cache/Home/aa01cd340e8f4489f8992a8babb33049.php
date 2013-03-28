<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo (L("nav_1")); ?></title>
<link rel='stylesheet' type='text/css' href='__HOME__/Css/index.css' />
<script  type="text/javascript" src="__HOME__/Js/jquery-1.7.min.js"></script>
<script  type="text/javascript" src="__HOME__/Js/common.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//填充空白
	fillBlank();
});
</script>
</head>
<body>
	<!-- 包含头部内容 -->
	<!-- 这2个有关Auto Complete的库 -->
<link rel='stylesheet' type='text/css' href='__HOME__/Css/jquery-ui.css' />
<script  type="text/javascript" src="__HOME__/Js/jquery-ui.min.js"></script>

<!-- Start Header -->
<div id="header">
	<div class='logo'>
		<h1><?php echo (L("title_1")); ?></h1>
		<h2><?php echo (L("title_2")); ?></h2>
	</div>
	<div id='nav_div'>
		<ul id='nav'>
			<li><a href="<?php echo U('Index/index');?>"><?php echo (L("nav_1")); ?></a></li>
			<li><a href="<?php echo U('Index/about');?>"><?php echo (L("nav_2")); ?></a></li>
			<li><a href="<?php echo U('Index/contact');?>"><?php echo (L("nav_3")); ?></a></li>
		</ul>
		<form action="<?php echo U('Index/index');?>" method='get' class='search'>
			<input type='text' placeholder="Search" id="autocomplete_tag" class='search_input' name='search'  />
		</form>
	</div>
</div>

<script type='text/javascript'>
//搜索输入框的自动完成
$(document).ready(function() {
	//自动完成功能
    $(function() {
        $( "#autocomplete_tag" ).autocomplete({
            source: function(request, response) {
                $.ajax({
                     url	: "<?php echo U('Autocomplete/suggestion');?>",
               		 data	: { 
                   		term	:	$("#autocomplete_tag").val(), 
                		},
               	 dataType	: "json",
               		 type	: "POST",
               	 success	: function(data){ 
                	var tmp = [];
					for(var i in data)
					{
						//alert(i);
						for(var n in data[i])
						{
							//alert(n);
							tmp.push(data[i][n]);
						}
					}
					response(tmp);
                }
            });
        },
        minLength: 2
        });
    });

    //按回车进行搜索功能
    $('.search_input').keydown(function(event){
    	if(event.keyCode == 13)
    	{
    		$('.search').submit();
    	}
    });
});
</script>
	
	<!-- Start Body -->
	<div id="wrap_body">
		<!-- 右侧边栏 -->
		<div id='rightSlide'>
			<!-- 游客功能 -->
<div class='slide_div'>
	<h3><?php echo (L("right_6")); ?></h3>
	<select class='chooseLang'>
		<option><?php echo (L("right_6_1")); ?></option>
		<option value='1'><?php echo (L("right_6_1_1")); ?></option>
		<option value='2'><?php echo (L("right_6_1_2")); ?></option>
	</select>
	<!-- <a class='chinese' href="?l=zh-cn">简体中文</a> 
	<a class='english' href="?l=en-us">English</a></p>	 -->
</div>

<!-- 有关文章的分类 -->
<div class='slide_div'>
	<h3><?php echo (L("right_1")); ?></h3>
	<ul class='slide_c'>
		<?php if(is_array($cates_list)): $i = 0; $__LIST__ = $cates_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c_vo): $mod = ($i % 2 );++$i;?><li class='icons s_c_l'>
				<a href='<?php echo U("category/$c_vo[id]");?>'><?php echo ($c_vo["category"]); ?><span class='s_c_s'>&nbsp;(<?php echo ($c_vo["count"]); ?>)</span></a>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
</div>

<!-- 最近文章的回复 -->
<div class='slide_div'>
	<h3><?php echo (L("right_2")); ?></h3>
	<ul class='slide_c'>	
		<?php if(is_array($com_list)): $i = 0; $__LIST__ = $com_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$com_vo): $mod = ($i % 2 );++$i;?><li class='icons s_c_l'>		
				<div class='com_vo_name'><?php echo ($com_vo["name"]); ?> Say:</div>
				<p class='com_vo_content'><a href='<?php echo U("blog/$com_vo[blogId]");?>'><?php echo (msubstr($com_vo["content"],0,25)); ?></a></p>
				<div class='com_vo_datetime'><?php echo (date('Y-m-d H:i:s',$com_vo["datetime"])); ?></div>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
</div>

<!-- 标签  -->
<div class='slide_div slide_tag_div'>
	<h3><?php echo (L("right_3")); ?></h3>
	<div class='tag_a_div'>
		<?php if(is_array($tags_list)): $i = 0; $__LIST__ = $tags_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tags_vo): $mod = ($i % 2 );++$i;?><a class='tag_a' href="/tag/<?php echo (trim($tags_vo["tag"])); ?>"><?php echo (trim($tags_vo["tag"])); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
</div>


<!-- 文章归档 -->
<div class='clear'></div>
<div class='slide_div'>
	<h3><?php echo (L("right_4")); ?></h3>
	<ul class='slide_c'>
		<?php if(is_array($dateArchiveArr)): $i = 0; $__LIST__ = $dateArchiveArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$archive_vo): $mod = ($i % 2 );++$i;?><li class='icons s_c_l'>
				<a  href='<?php echo U("archive/$archive_vo");?>'>
					<?php $tmp = str_replace('-','/',$archive_vo); echo $tmp; ?>
				</a>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
</div>

<!-- 日历 -->
<div class='clear'></div>
<div class='slide_div'>
	<h3><?php echo (L("right_7")); ?></h3>
	<?php echo ($calendar); ?>
</div>

<!-- 管理员 -->
<div class='slide_div'>
	<h3><?php echo (L("right_5")); ?></h3>
	<div class='admin_div' >
		<?php if($_SESSION['u_id'] != null): ?><a><?php echo (L("right_5_1")); ?> : <?php echo ($_SESSION['email']); ?></a>
			<a href='__APP__/Index/post_article'><?php echo (L("right_5_2")); ?> </a> 
			<a href='__APP__/Index/logout'><?php echo (L("right_5_3")); ?> </a> 
		<?php else: ?>
			<a href='__APP__/Index/login'><?php echo (L("right_5_4")); ?> </a><?php endif; ?>
		<a href='__APP__/Admin'><?php echo (L("right_5_5")); ?> </a> 
	</div>
</div>
		</div>
		

		<!-- 页面主要内容 -->
		<div id="bd_content">
			<!-- 如果分类没有文章 -->
			<?php if($blog_list == null): ?><div class='b_error'>Sorry,没有找到任何文章喔！</div>
			<?php else: ?>
			<!-- 存在文章 -->
				<?php if(is_array($blog_list)): $i = 0; $__LIST__ = $blog_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$blog_vo): $mod = ($i % 2 );++$i;?><div class='b_div'>
						<div>
							<span><?php echo ($blog_vo["datetime"]); ?>&nbsp;<?php echo (L("bDIv_1")); ?></span>
							<span class='b_div_1'><?php echo ($blog_vo["commentNum"]); ?> <?php echo (L("bDIv_2")); ?> | <?php echo ($blog_vo["click"]); ?> <?php echo (L("bDIv_3")); ?></span>
						</div>
						<h2 class='b_title'><a href='<?php echo U("blog/$blog_vo[id]");?>'><?php echo ($blog_vo["title"]); ?></a></h2>
						<div class='b_con'>
							<?php echo (msubstr(strip_tags($blog_vo["content"]),0,300)); ?>
						</div>
						<div class='b_info'>
							<span class='b_info_s1'>
								<span class='tag_name'><?php echo (L("bDIv_4")); ?>：</span>
								<?php $tags = explode(',',$blog_vo['tags']); foreach($tags as $val) { ?>
										<a class='b_tags' href='<?php echo U("tag/$val");?>'><?=$val?></a>
								<?php } ?>
							</span>
						</div>
						<div class='b_info1'><?php echo (L("bDIv_5")); ?>：<a href='<?php echo U("category/$blog_vo[c_id]");?>'><?php echo ($blog_vo["category"]); ?></a></div>
						<div class='readmore'><a href='<?php echo U("blog/$blog_vo[id]");?>' class='icons b_more'><?php echo (L("bDIv_6")); ?></a></div>
					</div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		</div>
		<div id='page'><?php echo ($show); ?></div>
	</div>
	
	<!-- 包含公用尾部 -->
	<div id="footer">
    <p>
    	© 2013 Copyright <a href="__URL__">Andy's Blog</a>
    </p>
</div>
<!-- 返回头部 -->
<a href='#top' class='gototop'></a>
</body>
</html>