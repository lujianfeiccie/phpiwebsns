<?php
$id=intval(get_argg('id'));

//取得删除节点的nodepath数据
$del_row=select_spell($ArticleTable['article_channel'],"nodepath,count","id=$id","","","getRow");
$parent_path=$del_row['nodepath'];
$channel_count=$del_row['count'];
if($channel_count){
	echo 'error:此分类下还有内容';exit;
}

$del_row=select_spell($ArticleTable['article_channel'],"count(*) num","parentid=$id","","","getRow");
if($del_row['num']>0){
	echo 'error:此分类下还有子分类';exit;
}
//删除其子节点及其本身
$is_success=del_spell($ArticleTable['article_channel'],"nodepath like '$parent_path%'");

//返回值
if($is_success){
	//$key_array=array('all_c[nodepath]_mt','all/is_digg','all/display','all/menu');
	//updateCache('channel/list/',$key_array);
	header("Location:index.php?app=view&channel&manage");
}else{
	echo 'error:';
}
?>