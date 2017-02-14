<!DOCTYPE html>
<html lang="cn">
<head>
<meta name="viewport" http-equiv="Content-Type"  content="text/html; charset=UTF-8 width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>无聊图</title>
<link href="/css/image.css" rel="stylesheet" type="text/css" />
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
	<div class='imageList'>
	<?php foreach ($images as $key => $image) {?>
		<div class="row">
			<?php echo $image->description ? '<p>'.$image->description.'</p>':'';?>
			<?php if(substr($image->mw_url, -3,3)=='jpg'){?>
				<img  style="max-width: 100%; max-height: 700px;" src="<?php echo $image->mw_url;?>">
			<?php }else{?>
				<img src="<?php echo $image->mw_url;?>" org_src="<?php echo $image->normal_url;?>"  style="max-width: 100%; max-height: 700px;">
				<div class="gif-mask" >PLAY</div>
			<?php }?>
		</div>
	<?php }?>
	</div>
	<div class="page-Btn">
		<a href='<?php echo url('images').'?'.http_build_query(['page'=>$current_page-1]);?>' class="previous-btn"> &lt; 前滚翻</a>
		<a href='<?php echo url('images').'?'.http_build_query(['page'=>$current_page+1]);?>' class="next-btn">后空翻 &gt;</a>
	</div>
	<div id="loading" style="display: none;">
        <div class="pacman">
            <div class=""></div>
            <div class=""></div>
            <div class=""></div>
            <div class=""></div>
            <div class=""></div>
        </div>
        加载中
    </div>
</body>
</html>

<script src="//cdn.bootcss.com/fastclick/1.0.6/fastclick.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.imageList').find('.gif-mask').each(function () {
       	$(this).parent().css('position', 'relative');
        var img =$(this).prev();
        var position = img.position();
       	$(this).css({
            'height': img.height(),
            'width': img.width(),
            'line-height': img.height() + 'px',
            'left': position.left,
            'top': position.top
        });
    });
	FastClick.attach(document.body);
	$('.imageList img').each(function () {
		// $(this).css('max-width', '100%');
		$(this).click(function () {
			if ($(this).css('max-height') == 'none') {
				var max_width = $(this).parent().parent().width();
				$(this).css('max-width', max_width);
				$(this).css('max-height', '700px');
			} else {
				$(this).css('max-height', 'none');
			}
		});
	});

	$(document).on('click', '.gif-mask',function(){
	    var parent = $(this).parent();
	    var img = $(this).prev('img');
	    var org_src = img.attr('org_src');
	    if (org_src != '') {
	    	$(this).append($('#loading'));
	        img.attr('src', org_src);
	        img.removeAttr('org_src');
	        // $(this).remove();
	    }
	})
})
</script>
