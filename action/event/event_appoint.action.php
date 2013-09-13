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
if($user_status['status']<4){
	action_return(0,$ea_langpackage->ea_no_permission,"-1");
}

//活动名字
$event_name=api_proxy("event_self_by_eid","title",$event_id);
$event_name=$event_name['title'];

//定义写操作
	dbtarget('w',$dbServs);
	$dbo=new dbex();

  $sql="update $t_event_members set status=3 where user_id=$member_id and event_id=$event_id";
  $dbo->exeUpdate($sql);

	$title=$ea_langpackage->ea_you_assigned_to.$event_name.$ea_langpackage->ea_organizing_people;
	$scrip_content=$ea_langpackage->ea_you_assigned_to.'<a href="home.php?h='.$user_id.'&app=event_space&event_id='.$event_id.'" target="_blank">'.$event_name.'</a>'.$ea_langpackage->ea_organizing_people;
	$is_success=api_proxy('scrip_send',$ea_langpackage->ea_system_sends,$title,$scrip_content,$member_id,0);
	if($is_success){
		api_proxy("message_set",$member_id,$ea_langpackage->ea_num_notice,"modules.php?app=msg_notice",0,11,"remind");
	}
  $jump="modules.php?app=event_member_manager&event_id=$event_id";
  action_return(1,'',$jump);
?>

