<?php
require("foundation/modules_channel.php");
$parentid=intval(get_argp('parentid'));
$type_id=intval(get_argp('type_id'));
$outlink=get_argp('outlink_single') ? short_check(get_argp('outlink_single')):short_check(get_argp('outlink'));

if($type_id){
	$_REQUEST['is_show']=0;
	$_REQUEST['is_digg']=0;
}

//插入频道
$is_success=false;
$update_node=update_node($parentid);

//插入数据
$insert_cols=array("nodepath","out_link","parentid","name","is_menu","order_num","is_digg","is_show","type_id","meta_key","meta_descrip","meta_title");//频道插入字段
$insert_values=array("'".$update_node."'","'".$outlink."'");//初始化字段数组
$must_array=array("name"=>"名称");

$insert_values=request_array($insert_cols,$insert_values,$must_array);//赋值字段数组
$channel_insert_cols=array_combine($insert_cols,$insert_values);//链接字段与数据

$is_success=insert_spell($ArticleTable['article_channel'],$channel_insert_cols);//执行插入操作,并且返回新节点的id值

//处理返回
if($is_success){
	header("Location:index.php?app=view&channel&manage");
}else{
	echo 'error:';
}
?>