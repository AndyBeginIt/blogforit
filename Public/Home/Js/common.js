$(document).ready(function(){
	//滚动到头部按钮
	$(window).scroll(function(){
	   if ($(window).scrollTop() > 100) 
	   {
          $(".gototop").fadeIn(1500);
       }
       else 
       {
          $(".gototop").fadeOut(1000);
       }
   })

	//当点击跳转链接后，回到页面顶部位置
    $('.gototop').click(function () {
        $('body,html').animate({ scrollTop: 0 }, 1000);
        return false;
    });

    //切换英文与中文
    $(".chooseLang").change(function(){
    	var langType = $(this).val();
    	if(langType == 1)
    	{
    		window.location.href='?l=en-us';
    	}
    	else if(langType == 2)
    	{
    		window.location.href='?l=zh-cn';
    	}
    });
})


//填充空白
function fillBlank(extraHeight)
{
	var height = parseInt($(document).height()) - parseInt($("#header").height()) - parseInt($("#footer").height()) - parseInt($("#footer").css('margin-bottom'));
	if(typeof extraHeight != 'undefined')
 	{
 		height = parseInt($("#wrap_body").height()) + parseInt(extraHeight);
 	}
	$('#rightSlide').css('height',height);
 	$('#wrap_body').css('height',height);
}

//comment reply @name
function replyComment(name)
{
	var str = '@'+name+' ';
	//选择DOM对象给予焦点
	$('.form_com_textarea')[0].focus();
	//选择Jquery对象添加文字
	$('.form_com_textarea').val(str);
}

//clear all data
function clearAllData()
{
	//clear write comment value
	$('.c_name').val('');
	$('.c_email').val('');
	$('.form_com_textarea').val('');
}