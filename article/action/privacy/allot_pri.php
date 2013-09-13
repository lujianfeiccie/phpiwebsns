<?php
$resource_name_array=get_argp("resource_name");
$id=short_check(get_argg('id'));
if(!$id){
	echo '请选择要删除的对象';exit;
}
$rights=empty($resource_name_array) ? "":join(",",$resource_name_array);
$update_array=array("rights" => "'$rights'");
//更新数据
$is_success=update_spell($ArticleTable['article_group'],$update_array,"id=$id");
if($is_success!==false){
	header("Location:index.php?app=view&privacy&group");
}else{
	echo 'error:';
}
?>