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
$event_id=intval(get_argp('event_id'));
$fellow=intval(get_argp('fellow'));
$template=short_check(get_argp('template'));
$time=time();

//表定义
$t_users=$tablePreStr."users";
$t_event=$tablePreStr."event";
$t_event_members=$tablePreStr."event_members";
$t_event_invite=$tablePreStr."event_invite";

if(empty($user_id)){echo $ea_langpackage->ea_operation_failed_relogin;exit;}
if(!$event_id){echo $ea_langpackage->ea_operation_failed_tryagain;exit;}

//读定义
dbtarget('r',$dbServs);
$dbo=new dbex;

//判断是否已经参加或提交了申请
$is_reg=api_proxy("event_member_by_uid","status",$event_id,$user_id);

if($is_reg['status']>=2){
	echo $ea_langpackage->ea_participated_activity;
	exit();
}
if($is_reg['status']==='0'){
	echo $ea_langpackage->ea_app_submitted;
	exit();
}

//取得活动加入权限
$event_row=api_proxy("event_self_by_eid","title,deadline,end_time,member_num,public,limit_num,verify",$event_id);

if($time >= $event_row['deadline']){
	echo $ea_langpackage->ea_reg_closed;
	exit();
}
if($time >= $event_row['end_time']){
	echo $ea_langpackage->ea_activity_ended;
	exit();
}
if($event_row['limit_num']!=0){
	if($event_row['member_num'] >= $event_row['limit_num']){
		echo $ea_langpackage->ea_people_aged;
		exit();
	}
	if(($event_row['member_num']+1+$fellow)>$event_row['limit_num']){
		echo $ea_langpackage->ea_carry_people_excessive;
		exit();
	}
}
if($event_row['public']==0){
	echo $ea_langpackage->ea_private_activity_rejoin;
	exit();
}

$sql="select to_user_id from $t_event_invite where event_id=$event_id and to_user_id=$user_id";
$is_invite=$dbo->getRow($sql);
if($event_row['public']==1 && !$is_invite['to_user_id']){
	echo $ea_langpackage->ea_activity_invited_join;
	exit();
}

//获取用户所在省市
$user_row=api_proxy('user_self_by_uid','reside_province,reside_city',$user_id);

//写定义
dbtarget('w',$dbServs);

//如果不需要审核 或 是被邀请者 直接加入
if($event_row['verify']==0 || $is_invite['to_user_id']){

	//插入活动成员表
	$sql="insert into $t_event_members (event_id,user_id,user_name,user_sex,user_ico,reside_province,reside_city,dateline,status,fellow,template) values ($event_id,$user_id,'$user_name','$user_sex','$user_ico','".$user_row['reside_province']."','".$user_row['reside_city']."',".time().",2,'$fellow','$template')";

	//如果关注了该活动
	if($is_reg['status']==1){
		$sql="update $t_event set follow_num=follow_num-1 where event_id=$event_id";
		$dbo->exeUpdate($sql);
		$sql="update $t_event_members set status=2 where user_id=$user_id and event_id=$event_id";
	}

	if($dbo->exeUpdate($sql)){
		//增加活动人数
		$sql="update $t_event set member_num=member_num+1+$fellow where event_id=$event_id";
		$dbo->exeUpdate($sql) or die($ea_langpackage->ea_operation_failed);
	}
	//更新邀请表
	$sql="delete from $t_event_invite where event_id=$event_id and to_user_id=$user_id";
	$dbo->exeUpdate($sql);

	//纪录新鲜事
	$title=$ea_langpackage->ea_add_activity.'<a href="home.php?h='.$user_id.'&app=event_space&event_id='.$event_id.'" target="_blank">'.$event_row['title'].'</a>';
	$content='<a href="home.php?h='.$user_id.'&app=event_space&event_id='.$event_id.'" target="_blank">'.$event_row['title'].'</a>';
	$is_suc=api_proxy("message_set",0,$title,$content,0,11);
	echo $ea_langpackage->ea_successfully_add;
}else{

	$sql="insert into $t_event_members (event_id,user_id,user_name,user_sex,user_ico,reside_province,reside_city,dateline,status,fellow,template) values ($event_id,$user_id,'$user_name','$user_sex','$user_ico','".$user_row['reside_province']."','".$user_row['reside_city']."',".time().",0,'$fellow','$template')";

	//如果关注了该活动
	if($is_reg['status']==1){
		$sql="update $t_event set follow_num=follow_num-1 where event_id=$event_id";
		$dbo->exeUpdate($sql);
		$sql="update $t_event_members set status=0 where user_id=$user_id and event_id=$event_id";
	}

	if($dbo->exeUpdate($sql)){
		$e_manager=api_proxy("event_member_by_eid","user_id",$event_id,'',3);
		foreach($e_manager as $val){
			api_proxy("message_set",$val['user_id'],$ea_langpackage->ea_num_people_request_join,"modules.php?app=event_member_manager&event_id=".$event_id,0,11,"remind");//提醒机制
		}
		echo $ea_langpackage->ea_app_submitted_later;
	}else{
		echo $ea_langpackage->ea_operation_failed_tryagain;
	}
}
?>