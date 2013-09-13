<?php
//引入语言包
$ea_langpackage=new event_actionlp;

//引入模块公共方法文件
require("foundation/aanti_refresh.php");
require("api/base_support.php");

//变量定义区
$user_id=get_sess_userid();
$event_id=intval(get_argp('event_id'));
$fellow=intval(get_argp('fellow'));
$template=short_check(get_argp('template'));
$time=time();

//表定义
$t_event=$tablePreStr."event";
$t_event_members=$tablePreStr."event_members";

if(empty($user_id)){echo $ea_langpackage->ea_operation_failed_relogin;exit;}
if(!$event_id){echo $ea_langpackage->ea_operation_failed_tryagain;exit;}

//读定义
dbtarget('r',$dbServs);
$dbo=new dbex;

//判断是否已经参加或提交了申请
$is_reg=api_proxy("event_member_by_uid","status,fellow",$event_id,$user_id);

if($is_reg['status']!=='0' && $is_reg['status']<2){
	echo $ea_langpackage->ea_not_participate_activity;
	exit();
}
$add_follow_num=$fellow-$is_reg['fellow'];

//取得活动加入权限
$event_row=api_proxy("event_self_by_eid","title,deadline,end_time,member_num,limit_num,verify",$event_id);

if($time >= $event_row['deadline']){
	echo $ea_langpackage->ea_reg_closed;
	exit();
}
if($time >= $event_row['end_time']){
	echo $ea_langpackage->ea_activity_ended;
	exit();
}
if($event_row['limit_num']!=0){
	if(($event_row['member_num']+($add_follow_num))>$event_row['limit_num']){
		echo $ea_langpackage->ea_carry_people_excessive;
		exit();
	}
}

//写定义
dbtarget('w',$dbServs);

	$sql="update $t_event_members set fellow=$fellow,template='$template' where user_id=$user_id and event_id=$event_id";
	if($dbo->exeUpdate($sql)===false){
		echo $ea_langpackage->ea_operation_failed_tryagain;
		exit();
	}
	$sql="update $t_event set member_num=member_num+($add_follow_num) where event_id=$event_id";
	if($dbo->exeUpdate($sql)===false){
		echo $ea_langpackage->ea_operation_failed_tryagain;
		exit();
	}
	echo $ea_langpackage->ea_info_modified;
?>