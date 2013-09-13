<?php
	//引入公共模块
	require("foundation/module_event.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//引入语言包
	$ef_langpackage=new event_frontlp;

	//变量区
	$user_id=get_sess_userid();
	$event_id=intval(get_argg('event_id'));
	$time=time();
	$no_event="";
	
	//表定义
	$t_event_members=$tablePreStr."event_members";
	$t_event_invite=$tablePreStr."event_invite";
	$t_pals_mine=$tablePreStr."pals_mine";

	//当前页面参数
	$page_num=trim(get_argg('page'));
	$my_pals_rs=array();
	$event_row=array();
	
	//权限判断
	$status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
	$event_row=api_proxy("event_self_by_eid","deadline,end_time,member_num,grade,limit_num,allow_invite,is_pass",$event_id);
	if(intval($status['status']) < 3){
		//活动是否允许邀请
		if($event_row['allow_invite']==0){
			echo "<script type='text/javascript'>alert(\"$ef_langpackage->ef_no_permission\");window.history.go(-1);</script>";
			exit();
		}
	}

if($event_row['is_pass']==0 || $event_row['grade']<=0){
	$no_event=$ef_langpackage->ef_donot_failed_or_locked;
}
else if($time >= $event_row['end_time']){
	$no_event=$ef_langpackage->ef_donot_ended;
}
else if($time >= $event_row['deadline']){
	$no_event=$ef_langpackage->ef_donot_deadline;
}
else if($event_row['limit_num']>'0' && $event_row['member_num']>=$event_row['limit_num']){
	$no_event=$ef_langpackage->ef_donot_number_full;
}
else{

	//定义写操作
	dbtarget('w',$dbServs);
	$dbo=new dbex();

	//取得我的好友
	$sql="select pm.pals_id,pm.pals_name, pm.pals_sex,pm.pals_ico,(select ei.user_id from $t_event_invite as ei where ei.to_user_id = pm.pals_id and ei.event_id = $event_id) as to_user_id, (select em.user_id from $t_event_members as em where em.user_id = pm.pals_id and em.event_id = $event_id and em.status!=1) as user_id from $t_pals_mine pm where pm.user_id=$user_id and pm.accepted > 0";
	
	//$dbo->setPages(10,$page_num);//设置分页
	$my_pals_rs=$dbo->getRs($sql);
	//$page_total=$dbo->totalPage; //分页总数	
}

//显示控制
$list_none="content_none";
$isNull=0;
if(empty($my_pals_rs)){
	$isNull=1;
	$list_none="";
	$no_event=$no_event?$no_event:$ef_langpackage->ef_not_friends.', <a href="modules.php?app=mypals_search">'.$ef_langpackage->ef_add_friend_now.'</a>';
}
?>
