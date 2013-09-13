<?php
	//引入公共模块
	require("foundation/module_event.php");
	require("foundation/module_mypals.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");

	//引入语言包
	$ef_langpackage=new event_frontlp;

	//变量区
	$ses_uid=get_sess_userid();
	$url_uid = intval(get_argg('user_id'));
	$event_id = intval(get_argg('event_id'));
	$send_script_js="location.href='modules.php?app=msg_creator&2id={uid}&nw=1';";
	$send_join_js="mypals_add({uid});";
	$target="frame_content";
	
	//引入模块公共权限过程文件
	$is_self_mode = 'partLimit';
	$is_login_mode = '';
	require("foundation/auser_validate.php");
	
	$status=api_proxy("event_member_by_uid","status",$event_id,$ses_uid);
	$status=intval($status['status']);
	
	
	$event_rs=array();
	//取得活动成员
	$event_members = api_proxy("event_member_by_eid","*",$event_id);

	//数据显示控制
	$isNull=0;
	if(empty($event_members)){
		$isNull=1;
	}
	
?>
