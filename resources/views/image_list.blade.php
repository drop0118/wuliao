<!DOCTYPE html>
<html lang="cn">
<head>
<meta name="viewport" http-equiv="Content-Type"  content="text/html; charset=UTF-8 width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>无聊图</title>
<link href="<?php echo url('/css/image.css');?>" rel="stylesheet" type="text/css" />
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
	<div class='imageList'>
	<?php foreach ($images as $key => $image) {?>
		<div class="row" style="position: relative;">
			<?php echo $image->description ? '<p>'.$image->description.'</p>':'';?>
			<?php if(substr($image->mw_url, -3,3)=='jpg'){?>
				<img  style="max-width: 100%; max-height: 700px;" src="<?php echo $image->mw_url;?>">
			<?php }else{?>
				<img src="<?php echo $image->mw_url;?>" org_src="<?php echo $image->normal_url;?>"  class='gif' style="max-width: 100%; max-height: 700px;">
				<div class="gif-mask" style="display: none;">PLAY</div>
				<div class="pacman" style="position: absolute;display: none;">
		            <div class=""></div>
		            <div class=""></div>
		            <div class=""></div>
		            <div class=""></div>
		            <div class=""></div>
		        </div>
			<?php }?>
		</div>
	<?php }?>
	</div>
	<div class="page-Btn">
		<a href='<?php echo url('/').'?'.http_build_query(['page'=>$current_page-1]);?>' class="previous-btn"> &laquo; 前滚翻</a>
		<a href='<?php echo url('/').'?'.http_build_query(['page'=>$current_page+1]);?>' class="next-btn">后空翻 &raquo;</a>
	</div>
</body>
</html>

<script src="//cdn.bootcss.com/fastclick/1.0.6/fastclick.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.gif').one("load", function() {
        var img =$(this);
        var position = img.position();
       	$(this).next().css({
            'height': img.height(),
            'width': img.width(),
            'line-height': img.height() + 'px',
            'left': position.left,
            'top': position.top
        }).show();
        $(this).next().next().css({
        	'left': (img.width()-50)*0.5,
            'top': ($(this).parent().height()-50)*0.5,
        });
	}).each(function() {
	  if(this.complete) $(this).load();
	});
    
	FastClick.attach(document.body);
	$('.imageList img').each(function () {
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
		$(this).next().show();
	    var parent = $(this).parent();
	    var img = $(this).prev('img');
	    var org_src = img.attr('org_src');
	    img.one('load', function () {
	        $(this).next().hide();
	    });
	    if (org_src != '') {
	        img.attr('src', org_src);
	        img.removeAttr('org_src');
	        $(this).remove();
	    }
	})
})
</script>
