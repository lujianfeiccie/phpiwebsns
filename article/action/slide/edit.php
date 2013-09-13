<?php
$id = intval(get_argg('id'));
$photo_src=short_check(get_argp('upload_name'));
if(!$id){
	echo 'error:无幻灯片id';exit;
}
if(!$photo_src){
	echo 'error:图片不能为空';exit;
}

$update_cols = array("photo_src","title","link","order_num");
$update_values=array("'".$photo_src."'");
$must_array=array("title"=>"标题");
$update_values=request_array($update_cols,$update_values,$must_array);//赋值字段数组
$slide_update_cols=array_combine($update_cols,$update_values);//链接字段与数据

$is_success=update_spell($ArticleTable['article_slide'],$slide_update_cols,"id=$id");
if($is_success!==false){
	$key_mt='slide/list/all/';
	updateCache($key_mt);	
	header("Location:index.php?app=view&slide&manage");
}else{
	echo 'error:修改错误';
}
?>