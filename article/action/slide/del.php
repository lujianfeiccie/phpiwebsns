<?php
$id = intval(get_argg('id'));
if(!$id){
	echo 'error:无幻灯片id';exit;
}
$slide_row=select_spell($ArticleTable['article_slide'],'photo_src',"id=$id",'','','getRow');
@unlink('../'.$slide_row['photo_src']);
$is_success=del_spell($ArticleTable['article_slide'],"id=$id");
if($is_success!==false){
	$key_mt='slide/list/all/';
	updateCache($key_mt);	
	header("Location:index.php?app=view&slide&manage");
}else{
	echo 'error:删除错误';
}
?>