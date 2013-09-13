<?php
$type=get_argg('type');
$id=short_check(get_argg('id'));
if(!$id){
	echo 'error:请选择要删除的对象';exit;
}
switch($type){
	case "person":
	$table=$ArticleTable['article_admin'];
	$condition="user_id=$id";
	$return_url="index.php?app=view&privacy&person";
	break;
	case "group":
	$table=$ArticleTable['article_group'];
	$condition="id=$id";
	$return_url="index.php?app=view&privacy&group";
	$update_array=array("gid"=>"","gname"=>"");
	$update_condition="gid=$id";
	update_spell($ArticleTable['article_admin'],$update_array,$update_condition);
	break;
	case "resource":
	$table=$ArticleTable['article_resource'];
	$condition="resource_id='$id'";
	$return_url="index.php?app=view&privacy&resource";
	$update_array=array("rights"=>"replace(rights,',$id,',',')");
	$update_condition="rights like '%,$id,%'";
	update_spell($ArticleTable['article_group'],$update_array,$update_condition);
	break;
}

//删除数据
$is_success=del_spell($table,$condition);
if($is_success){
	update_privacy_cache($ArticleTable['article_resource']);
	header("Location:".$return_url);
}else{
	echo 'error:';
}
?>