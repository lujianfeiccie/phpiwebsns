<?php
$type=get_argg('type');
$id=short_check(get_args('id'));
if($id===''){
	echo '请选择';exit;
}
$result_type='';
$name=short_check(get_argp('name'));
$modules_name=short_check(get_argp('modules_name'));
switch($type){
	case "person":
	$aid=short_check(get_argg('aid'));
	$table=$ArticleTable['article_admin'];
	$condition="id=$aid";
	$update_array=array("gid"=>"'$id'","gname"=>"'$name'");
	$result_type=1;
	break;

	case "group":
	//自身信息
	$table=$ArticleTable['article_group'];
	$condition="id=$id";
	$update_array=array("name"=>"'$name'");
	$return_url="index.php?app=view&privacy&group";
	//更新admin表
	$update=array("gname"=>"'$name'");
	$condition_update="gid=$id";
	update_spell($ArticleTable['article_admin'],$update,$condition_update);
	break;

	case "resource":
	$nid=short_check(get_argp('nid'));
	//自身信息
	$table=$ArticleTable['article_resource'];
	$condition="resource_id='$id'";
	$return_url="index.php?app=view&privacy&resource";
	$update_array=array("resource_id"=>"'$nid'","name"=>"'$name'","modules_name"=>"'$modules_name'");
	//更新group表
	$update=array("rights"=>"replace(rights,',$id,',',$nid,')");
	$condition_update="rights like '%,$id,%'";
	update_spell($ArticleTable['article_group'],$update,$condition_update);
	break;
}

//更新数据
$is_success=update_spell($table,$update_array,$condition);
if($is_success!==false){
	update_privacy_cache($ArticleTable['article_resource']);
	if($result_type==1){
		echo $name;
	}else{
		header("Location:".$return_url);
	}
}else{
	echo 'error:';
}
?>