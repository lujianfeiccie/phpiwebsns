<?php
	//引入公共模块
	require("foundation/module_event.php");
	require("api/base_support.php");
	
	//引入语言包
	$ef_langpackage=new event_frontlp;
	
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");

	//限制时间段访问站点
	limit_time($limit_action_time);
	
	$user_id=get_sess_userid();
	$event_id=intval(get_argg('event_id'));
	$event_info = array('event_id'=>'','user_id'=>'','user_name'=>'','title'=>'','type_id'=>'','province'=>'','city'=>'','location'=>'','deadline'=>'','start_time'=>'','end_time'=>'','public'=>'2','detail'=>'','template'=>'','limit_num'=>'0','verify'=>'','allow_pic'=>'1','allow_post'=>'1','allow_invite'=>'1','allow_fellow'=>'');
	
	$action='do.php?act=event_add';
	
	if($event_id){
		//权限判断
		$status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
		$status=intval($status['status']);
		if($status < 3){
			echo "<script type='text/javascript'>alert('".$ef_langpackage->ef_no_permission."');window.history.go(-1);</script>";exit();
		}
		$field = implode(',', array_keys($event_info));
		$event_info = api_proxy("event_self_by_eid",$field,$event_id);
		$event_info['start_time'] = date('Y-m-d H:i', $event_info['start_time']);
		$event_info['end_time'] = date('Y-m-d H:i', $event_info['end_time']);
		$event_info['deadline'] = date('Y-m-d H:i', $event_info['deadline']);
		$action='do.php?act=event_edit&event_id='.$event_id;
	}

	//缓存功能区
	$event_sort_rs = api_proxy("event_sort_by_self");
	$event_type = event_sort_list($event_sort_rs, $event_info['type_id']);
?>