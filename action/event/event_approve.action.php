<?php
//引入语言包
	$ea_langpackage=new event_actionlp;

//引入模块公共方法文件
	require("api/base_support.php");

//变量区
	$user_id=get_sess_userid();
	$event_id=intval(get_argg('event_id'));
	$member_id=intval(get_argg('member_id'));

//数据表定义
	$t_event=$tablePreStr."event";
	$t_event_members=$tablePreStr."event_members";

//权限判断
	$user_status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
	$member_status=api_proxy("event_member_by_uid","status,fellow",$event_id,$member_id);

	if($user_status['status']<3 || $user_status['status']<=$member_status['status']){
		action_return(0,$ea_langpackage->ea_no_permission,"-1");exit;
	}

//定义读操作
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//引入公共函数
	require("foundation/module_group.php");

//权限判断
	$user_status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
	if($user_status['status']<3){
		action_return(0,$g_langpackage->g_no_privilege,"-1");
	}

//活动的信息
	$event_info=api_proxy("event_self_by_eid","title,detail",$event_id);

//携带人数
	$fellow=api_proxy("event_member_by_uid","fellow",$event_id,$member_id);
	$fellow=$fellow['fellow'];

//定义写操作
	dbtarget('w',$dbServs);
	$dbo=new dbex();

	$sql="update $t_event_members set status=2 where user_id=$member_id and event_id=$event_id";
	if($dbo->exeUpdate($sql)){
		$sql="update $t_event set member_num=member_num+1+$fellow where event_id=$event_id";
		$dbo->exeUpdate($sql);
	}

//通知
	$title=$ea_langpackage->ea_you_join.$event_info['title'].$ea_langpackage->ea_activity_app_by;
	$scrip_content=$ea_langpackage->ea_you_join.'<a href="home.php?h='.$user_id.'&app=event_space&event_id='.$event_id.'" target="_blank">'.$event_info['title'].'</a>'.$ea_langpackage->ea_activity_app_by;
	$is_success=api_proxy('scrip_send',$ea_langpackage->ea_system_sends,$title,$scrip_content,$member_id,0);
	if($is_success){
		api_proxy("message_set",$member_id,$ea_langpackage->ea_num_notice,"modules.php?app=msg_notice",0,1,"remind");
	}

//纪录新鲜事
	$title=$ea_langpackage->ea_add_activity.'<a href="home.php?h='.$user_id.'&app=event_space&event_id='.$event_id.'" target="_blank">'.$event_info['title'].'</a>';
	$content=$event_info['detail'];
	$is_suc=api_proxy("message_set",0,$title,$content,0,11,$member_id);
	
  $jump="modules.php?app=event_member_manager&event_id=$event_id";
  action_return(1,'',$jump);

?>

