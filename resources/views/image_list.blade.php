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
			<p><?php echo $image->description;?></p>
			<?php if(){?>
				<img  src="<?php echo $image->mw_url?>">
			<?php }else{?>
			<?php }?>
		</div>
	<?php }?>
	</div>
	<div class="page-Btn">
		<div class="previous-btn"> &lt; 前滚翻</div>
		<div class="next-btn">后空翻 &gt;</div>
	</div>
</body>
</html>

<script src="//cdn.bootcss.com/fastclick/1.0.6/fastclick.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	FastClick.attach(document.body);
	$('.imageList img').each(function () {
		$(this).css('max-width', '100%');
		$(this).css('max-height', '700px').click(function () {
			if ($(this).css('max-height') == 'none') {
				var max_width = $(this).parent().parent().width();
				$(this).css('max-width', max_width);
				$(this).css('max-height', '700px');
			} else {
				$(this).css('max-height', 'none');
			}
		});
	});
	$(document).on('click','.next-btn',function(){
		window.location.href = '<?php echo url('images').'?'.http_build_query(['page'=>$current_page+1]);?>';
	});

	$(document).on('click','.previous-btn',function(){
		window.location.href = '<?php echo url('images').'?'.http_build_query(['page'=>$current_page-1]);?>';
	});
})
</script>
