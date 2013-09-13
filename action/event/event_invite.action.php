<?php
//引入语言包
	$ea_langpackage=new event_actionlp;

//引入公共函数
	require("api/base_support.php");

//变量区
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$event_id=short_check(get_argp('event_id'));
	$pals_id=get_argp('pals_id');
	$pals_name=get_argp('pals_name');

//数据表定义
	$t_event_invite=$tablePreStr."event_invite";
	
//权限判断
	$event_row=api_proxy("event_self_by_eid","allow_invite",$event_id);
	if($event_row['allow_invite']==0){
		$status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
		if($status['status'] < 3){
			action_return(0,$ea_langpackage->ea_no_permission,"-1");
		}
	}
	
//活动名字
	$event_name=api_proxy("event_self_by_eid","title",$event_id);
	$event_name=$event_name['title'];

//定义写操作
	dbtarget('w',$dbServs);
	$dbo=new dbex();
if(!empty($pals_id)){
	$title=$user_name.$ea_langpackage->ea_invite_participate.$event_name.$ea_langpackage->ea_activity;
	$scrip_content=$user_name.$ea_langpackage->ea_invite_participate.'<a href="home.php?h='.$user_id.'&app=event_space&event_id='.$event_id.'" target="_blank">'.$event_name.'</a>'.$ea_langpackage->ea_activity.'<br />'.$ea_langpackage->ea_you_can.'<a href="javascript:void(0)" onclick="join_event('.$event_id.')">'.$ea_langpackage->ea_accept_invite.'</a>'.$ea_langpackage->ea_or_view.'<a href="home.php?h='.$user_id.'&app=event_space&event_id='.$event_id.'" target="_blank">'.$ea_langpackage->ea_event_details.'</a>';
	
	foreach($pals_id as $key => $value){
		$sql="insert into $t_event_invite (event_id,user_id,user_name,to_user_id,to_user_name,dateline) values ($event_id,$user_id,'$user_name','$value','".$pals_name[$key]."',".time().")";
		$dbo->exeUpdate($sql);
		$is_success=api_proxy('scrip_send',$ea_langpackage->ea_system_sends,$title,$scrip_content,$value,0);
		if($is_success){
			api_proxy("message_set",$value,$ea_langpackage->ea_num_notice,"modules.php?app=msg_notice",0,1,"remind");
		}
	}
}
	$jump="modules.php?app=event_invite&event_id=$event_id";
	action_return(1,"",$jump);
?>

