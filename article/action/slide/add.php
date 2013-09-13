<?php
$photo_src=short_check(get_argp('upload_name'));
if($photo_src==''){
	echo 'error:图片不能为空';exit;
}
$insert_cols = array("photo_src","title","link","order_num");
$insert_values=array("'".$photo_src."'");
$must_array=array("title"=>"标题");
$insert_values=request_array($insert_cols,$insert_values,$must_array);//赋值字段数组
$slide_insert_cols=array_combine($insert_cols,$insert_values);//链接字段与数据
$is_success=insert_spell($ArticleTable['article_slide'],$slide_insert_cols);
if($is_success!==false){
	$key_mt='slide/list/all/';
	updateCache($key_mt);
	header("Location:index.php?app=view&slide&manage");
}else{
	echo 'error:添加错误';
}
?>