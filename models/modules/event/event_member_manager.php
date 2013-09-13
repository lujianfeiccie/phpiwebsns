<?php
	//引入公共模块
	require("foundation/module_event.php");
	require("foundation/module_mypals.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");
	
	//引入语言包
	$ef_langpackage=new event_frontlp;

	//变量区
	$role='';
	$user_id=get_sess_userid();
	$event_id=intval(get_argg('event_id'));
	$mod=intval(get_argg('mod'));

	//当前页面参数
	$page_num=trim(get_argg('page'));

	//权限判断
	$status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
	$status=intval($status['status']);
	if($status < 3){
		echo "<script type='text/javascript'>alert(\"$ef_langpackage->ef_no_permission\");window.history.go(-1);</script>";
		exit();
	}
	
	switch($mod){
		case 0:
		//取得已经审核的成员
		$member_rs=api_proxy("event_member_by_eid","*",$event_id);		
		break;
		
		case 1:
		//取得关注的用户
		$member_rs=api_proxy("event_member_by_status","*",$event_id,1);		
		break;
		
		case 2:
		//取得未审核的成员
		$member_rs=api_proxy("event_member_by_status","*",$event_id,0);		
		break;
	}

	//显示控制
	$isNull=0;
	$list_none='content_none';
	if(empty($member_rs)){
		$list_none='';
		$isNull=1;
	}
	?>
