<?php
//引入模块公共方法文件
require("session_check.php");
require("../foundation/fpages_bar.php");
require("../foundation/fsqlseletiem_set.php");
require("../foundation/fback_search.php");
require("../api/base_support.php");
//读写初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//定义数据库表名称
$t_event_type=$tablePreStr."event_type";
//接收参数
$op = get_argg('op');
//修改操作
if($op=="edit"){
	$typeid = get_argg('typeid');
	$typename = get_argp('typename');
	$template = get_argp('templates');
	$displayorder = get_argp('displayorder');
	//判断是否上传新的海报图片
	if($_FILES['attach']['name']){
		$_FILES['attach']['name']=array($_FILES['attach']['name']);
		$_FILES['attach']['size']=array($_FILES['attach']['size']);
		$_FILES['attach']['type']=array($_FILES['attach']['type']);
		$_FILES['attach']['tmp_name']=array($_FILES['attach']['tmp_name']);
		$_FILES['attach']['error']=array($_FILES['attach']['error']);
		$up = new upload();
		$up->set_thumb(150,150);	//缩略图设置
		$up->set_dir('../uploadfiles/event/','poster/');	//文件路径
		$fs = $up->execute();
		$fileSrcStr=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['name'];	//原图
		$thumb_src=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['thumb'];	//缩略图
		//执行sql
		$sql = "update $t_event_type set type_name='$typename',poster='$fileSrcStr',poster_thumb='$thumb_src',template='$template',display_order='$displayorder' where type_id=$typeid";
		$dbo->exeUpdate($sql);
		echo "<script language='javascript' type='text/javascript'>window.location.href='event_type_list.php';</script>";
	}else{
		$sql = "update $t_event_type set type_name='$typename',template='$template',display_order='$displayorder' where type_id=$typeid";
		$dbo->exeUpdate($sql);
		echo "<script language='javascript' type='text/javascript'>window.location.href='event_type_list.php';</script>";
	}
}


//添加操作
if($op=="add"){
	$typename = get_argp('typename');
	$template = get_argp('templates');
	$displayorder = get_argp('displayorder');
	$sql = "select max(type_id) from $t_event_type";
	$maxnum = $dbo->getRow($sql);
	$max_typeid = $maxnum[0];
	$typeid = $max_typeid+1;
	//判断是否上传新的海报图片
	if($_FILES['attach']['name']){
		$_FILES['attach']['name']=array($_FILES['attach']['name']);
		$_FILES['attach']['size']=array($_FILES['attach']['size']);
		$_FILES['attach']['type']=array($_FILES['attach']['type']);
		$_FILES['attach']['tmp_name']=array($_FILES['attach']['tmp_name']);
		$_FILES['attach']['error']=array($_FILES['attach']['error']);
		$up = new upload();
		$up->set_thumb(150,150);	//缩略图设置
		$up->set_dir('../uploadfiles/event/','poster/');	//文件路径
		$fs = $up->execute();
		$fileSrcStr=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['name'];	//原图
		$thumb_src=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['thumb'];	//缩略图
		//执行sql
		$sql = "insert into $t_event_type(type_id,type_name,poster,poster_thumb,template,display_order) values('$typeid','$typename','$fileSrcStr','$thumb_src','$template','$displayorder')";
		$dbo->exeUpdate($sql);
		echo "<script language='javascript' type='text/javascript'>window.location.href='event_type_list.php';</script>";
	}else{
		$sql = "insert into $t_event_type(type_id,type_name,poster,poster_thumb,template,display_order) values('$typeid','$typename','','','$template','$displayorder')";
		$dbo->exeUpdate($sql);
		echo "<script language='javascript' type='text/javascript'>window.location.href='event_type_list.php';</script>";
	}
}


//删除活动类型默认海报
if($op=="del_poster"){
	$typeid = get_argg('typeid');
	$sql = "select * from $t_event_type where type_id=$typeid";
	$this_type = $dbo->getRow($sql);
	$type_poster = $this_type['poster'];
	$type_poster_thumb = $this_type['poster_thumb'];
	$sql = "update $t_event_type set poster='',poster_thumb='' where type_id=$typeid";
	$dbo->exeUpdate($sql);
	@unlink($type_poster); 
	@unlink($type_poster_thumb);
	echo "<script language='javascript' type='text/javascript'>window.location.href='event_type_detail.php?op=edit&typeid=".$typeid."';</script>";
}

?>
