<?php
//引入语言包
$ea_langpackage=new event_actionlp;

require("api/base_support.php");
//变量区
	$user_id=get_sess_userid();
	$event_id=intval(get_argg('event_id'));

//数据表定义
	$t_event=$tablePreStr."event";
	$t_event_members=$tablePreStr."event_members";

//定义写操作
	dbtarget('w',$dbServs);
	$dbo=new dbex();

//成员信息
	$member_status=api_proxy("event_member_by_uid","status,fellow",$event_id,$user_id);
	
	if($member_status['status']==4){
		echo $ea_langpackage->ea_sponsor_not_out;
		exit();
	}
	
//更新活动成员表
	$sql="delete from $t_event_members where user_id=$user_id and event_id=$event_id";
  	if($dbo->exeUpdate($sql)){
		if($member_status['status']){
			//更新群组人数
			$sql="update $t_event set member_num=member_num-".($member_status['fellow']+1)." where event_id=$event_id";
			$dbo->exeUpdate($sql);
		}
	}

?>