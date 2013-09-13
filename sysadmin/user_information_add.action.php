<?php
require("session_check.php");
require("../api/base_support.php");

//表定义
$t_user_information=$tablePreStr.'user_information';
//数据库
$dbo = new dbex;
dbtarget('w',$dbServs);
//接收参数
$info_name=short_check(get_argp('info_name'));
$type=get_argp('info_type');
$values=trim(short_check(get_argp('info_values')));
$sort=get_argp('info_sort')?intval(get_argp('info_sort')): 0;
if(empty($info_name)){
	echo "<script type='text/javascript'>alert('信息名称不能为空');window.history.go(-1);</script>";
}
if($type!=0 && empty($values)){
	echo "<script type='text/javascript'>alert('信息值不能为空');window.history.go(-1);</script>";
}
$sql="insert into $t_user_information (info_name,input_type,info_values,sort) values('$info_name',$type,'$values',$sort)";
$is_success=$dbo->exeUpdate($sql);
if($is_success){
  echo "<script type='text/javascript'>window.location.href='user_custom.php'</script>";
}else{
  echo "<script type='text/javascript'>alert('添加失败');window.history.go(-1);</script>";
}

?>