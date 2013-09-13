<?php
//引入语言包
$ea_langpackage=new event_actionlp;

//引入模块公共方法文件
require("foundation/aanti_refresh.php");
require("api/base_support.php");

//变量定义区
$user_id=get_sess_userid();
$user_name=get_sess_username();
$user_sex=get_sess_usersex();
$user_ico=get_sess_userico();
$event_id=intval(get_argg('event_id'));
$time=time();

//表定义
$t_event=$tablePreStr."event";
$t_event_members=$tablePreStr."event_members";


if(empty($user_id)){action_return(0,$ea_langpackage->ea_operation_failed_relogin,"-1");exit;}
if(!$event_id){action_return(0,$ea_langpackage->ea_operation_failed_tryagain,"-1");exit;}

//读定义
dbtarget('r',$dbServs);
$dbo=new dbex;

//判断是否已经参加或提交了申请
$is_reg=api_proxy("event_member_by_uid","status",$event_id,$user_id);

if($is_reg['status'] && $is_reg['status']!=1){
	action_return(0,$ea_langpackage->ea_join_or_app_activity,"-1");
	exit();
}
if($is_reg['status']==1){
	action_return(0,$ea_langpackage->ea_attention_activity,"-1");
	exit();
}

//取得活动加入权限
$event_row=api_proxy("event_self_by_eid","end_time,verify",$event_id);

if($time >= $event_row['end_time']){
	action_return(0,$ea_langpackage->ea_activity_ended,"-1");
	exit();
}

//获取用户所在省市
$user_row=api_proxy('user_self_by_uid','reside_province,reside_city',$user_id);

//写定义
	dbtarget('w',$dbServs);
//插入活动成员表
	$sql="insert into $t_event_members (event_id,user_id,user_name,user_sex,user_ico,reside_province,reside_city,dateline,status) values ($event_id,$user_id,'$user_name','$user_sex','$user_ico','".$user_row['reside_province']."','".$user_row['reside_city']."',$time,1)";
	if($dbo->exeUpdate($sql)){
	//增加活动人数
		$sql="update $t_event set follow_num=follow_num+1 where event_id=$event_id";
		$dbo->exeUpdate($sql);
	}
	
	$jump="modules.php?app=event_space&event_id=$event_id";
	action_return(1,"",$jump);


?>