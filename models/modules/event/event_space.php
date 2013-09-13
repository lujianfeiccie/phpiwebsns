<?php
	//引入公共模块
	require("foundation/fpages_bar.php");
	require("foundation/module_event.php");
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	
	//引入语言包
	$ef_langpackage=new event_frontlp;
	
	//变量区
	$dbo=new dbex;
	$url_uid = intval(get_argg('user_id'));
	$ses_uid = get_sess_userid();
	$is_admin = get_sess_admin();
	$event_id = intval(get_argg('event_id'));
	$event_members = array();
	$event_row = array();
	$photo_rs = array();
	$is_join='';
	$is_join_event='';
	$is_doing='';
	$is_doing_event='';
	$join_js='';
	$time=time();
	$error_str=$ef_langpackage->ef_activity_not_exist_canceled;
	$is_show=0;
	
	//引入模块公共权限过程文件
	$is_self_mode = 'partLimit';
	$is_login_mode = '';
	require("foundation/auser_validate.php");

	//取得活动信息
	$event_row = api_proxy("event_self_by_eid","*",$event_id);
	if(!empty($event_row)){

		if(($event_row['is_pass']=='1' && $event_row['grade']>='1') || $event_row['user_id']==$ses_uid || $is_admin){
			$is_show=1;
			
			//取得活动成员
			$event_members = api_proxy("event_member_by_eid","*",$event_id,10);
			
			$user_row=api_proxy("event_member_by_uid","status,fellow,template",$event_id,$ses_uid);
			
			$is_join=$user_row['status'];
			if($is_join>1){
				$is_join_event = $ef_langpackage->ef_participated_activity;
			}else if($is_join===0){
				$is_join_event = $ef_langpackage->ef_your_app_audit;
			}else if($is_join==1){
				$is_join_event = $ef_langpackage->ef_attented_activity;
			}
			if($time<$event_row['start_time']){
				$is_doing=1;
				$is_doing_event=$ef_langpackage->ef_activity_not_start;
			}else if($time>=$event_row['start_time'] && $time<$event_row['end_time']){
				$is_doing=2;
				$is_doing_event=$ef_langpackage->ef_activity_ongoing;
			}else{
				$is_doing=0;
				$is_doing_event=$ef_langpackage->ef_activity_already_end;
			}
			
			//活动照片
			$t_event_photo=$tablePreStr."event_photo";
			$sql="select photo_id,photo_name,photo_thumb_src from $t_event_photo where event_id=$event_id limit 8";
			$photo_rs=$dbo->getRs($sql);
			
			$event_row['template'] = str_replace(array("\r\n","\n","\r"),"<br>",$event_row['template']);
			$user_row['template'] = str_replace(array("\r\n","\n","\r"),"<br>",$user_row['template']);
		
			//查看计数
			if($ses_uid!=getCookie('e_'.$event_id)){
				  //读写分离方法-写操作
				  dbtarget('w',$dbServs);
				  $t_event=$tablePreStr."event";
				  $sql="update $t_event set view_num=view_num+1 where event_id=$event_id";
				  $dbo->exeUpdate($sql);
				  set_cookie('e_'.$event_id,$ses_uid);
			}
			
			$status=api_proxy("event_member_by_uid","status",$event_id,$ses_uid);
			$status=intval($status['status']);
			
			
		}else{
			$error_str=$ef_langpackage->ef_activity_not_approved_locked;
		}
	}

?>