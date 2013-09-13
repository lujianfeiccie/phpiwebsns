<?php
$type=get_argg('type');
$is_admin=array();
switch($type){
	//添加管理员
	case "person":
	$pals_id=get_argg('pals_id');
	if(!$pals_id){
		echo 'error:请选择管理员';exit;
	}
	$is_admin=select_spell($ArticleTable['article_admin'],"id","user_id=$pals_id",'','',"getRow");
	if($is_admin){
		echo 'error:不要重复添加管理员';exit;
	}
	
	//取得添加的管理员信息
	$pals_row=api_proxy("pals_self_by_paid","pals_id,pals_name,pals_sex,pals_ico",$pals_id);
	$pals_id=$pals_row['pals_id'];
	$pals_name=$pals_row['pals_name'];
	$pals_ico=$pals_row['pals_ico'];
	$pals_sex=$pals_row['pals_sex'];

	//合并数据
	$key_array=array("user_id","user_name","user_ico","user_sex");
	$value_array=array("'$pals_id'","'$pals_name'","'$pals_ico'","'$pals_sex'");
	$insert_array=array_combine($key_array,$value_array);
	$table=$ArticleTable['article_admin'];
	$return_url="index.php?app=view&privacy&person";
	break;

	//添加管理组
	case "group":
	$group=get_argp('name');
	if(!$group){
		echo 'error:管理组不能为空';exit;
	}
	$key_array=array("name");
	$value_array=array();
	$value_array=request_array($key_array,$value_array);
	$insert_array=array_combine($key_array,$value_array);
	$table=$ArticleTable['article_group'];
	$return_url="index.php?app=view&privacy&group";
	break;

	//添加资源
	case "resource":
	$resource_id=get_argp('resource_id');
	$name=get_argp('name');
	if(!$resource_id||!$name){
		echo 'error:权限名或权限id值不能为空';exit;
	}
	$key_array=array("resource_id","name","modules_name");
	$value_array=array();
	$value_array=request_array($key_array,$value_array);
	$insert_array=array_combine($key_array,$value_array);
	$table=$ArticleTable['article_resource'];
	$return_url="index.php?app=view&privacy&resource";
	break;
}

//插入数据
$is_success=insert_spell($table,$insert_array);
if($is_success){
	update_privacy_cache($ArticleTable['article_resource']);
	header("Location:".$return_url);
}else{
	echo 'error:';
}
?>