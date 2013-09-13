<?php
//引入语言包
$ea_langpackage=new event_actionlp;

//引入模块公共方法文件
require("foundation/aanti_refresh.php");
require("api/base_support.php");

//变量定义区
$user_id=get_sess_userid();
$event_id=short_check(get_argg('event_id'));

//表定义
$t_event=$tablePreStr."event";
$t_event_members=$tablePreStr."event_members";

if(empty($user_id)){echo $ea_langpackage->ea_operation_failed_relogin;exit;}
if(!$event_id){echo $ea_langpackage->ea_operation_failed_tryagain;exit;}

//读定义
dbtarget('r',$dbServs);
$dbo=new dbex;

//判断是否已经关注
$is_reg=api_proxy("event_member_by_uid","status",$event_id,$user_id);

if($is_reg['status']!=1){
	echo $ea_langpackage->ea_no_attention_activity;
	exit();
}

//写定义
	dbtarget('w',$dbServs);
//更新活动成员表
	$sql="delete from $t_event_members where user_id=$user_id and event_id=$event_id";
	if($dbo->exeUpdate($sql)){
		//更新活动关注人数
		$sql="update $t_event set follow_num=follow_num-1 where event_id=$event_id";
		$dbo->exeUpdate($sql);
	}
?>