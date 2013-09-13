<?php
require("session_check.php");
require("../api/base_support.php");

//接收参数
$info_id=intval(get_argp('id'));
//表定义
$t_user_information=$tablePreStr.'user_information';
$t_user_info=$tablePreStr.'user_info';

$dbo = new dbex;
dbtarget('w',$dbServs);
$sql="delete from $t_user_information where info_id =$info_id";
$is_sucess=$dbo->exeUpdate($sql);
if($is_sucess===false){
	echo 'failure';
}
$sql="delete from $t_user_info where info_id=$info_id";
$is_sucess=$dbo->exeUpdate($sql);
if($is_sucess===false){
	echo 'failure';
}else{
	echo 'success';
}
?>