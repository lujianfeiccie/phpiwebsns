<?php
//引入语言包
$ea_langpackage=new event_actionlp;

require("api/base_support.php");

//变量区
	$user_id=get_sess_userid();
	$event_id=intval(get_argg('event_id'));

//权限判断
	$member_status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
	if($member_status['status']!=4){
		action_return(0,"$ea_langpackage->ea_no_permission","-1");
		exit();
	}

//数据表定义
	$t_event=$tablePreStr."event";
	$t_event_members=$tablePreStr."event_members";
	$t_event_comment=$tablePreStr."event_comment";
	$t_event_photo=$tablePreStr."event_photo";

//定义写操作
	dbtarget('w',$dbServs);
	$dbo=new dbex();

//卸载活动照片
	$sql="select photo_src, photo_thumb_src from $t_event_photo where event_id=$event_id";
	$photo_rs = $dbo->getRs($sql);
	foreach($photo_rs as $val){
		@unlink($val['photo_src']);
		@unlink($val['photo_thumb_src']);
	}

//卸载活动表
	$sql="delete from $t_event where event_id=$event_id";
	$dbo->exeUpdate($sql);

//卸载活动成员表
  $sql="delete from $t_event_members where event_id=$event_id";
  $dbo->exeUpdate($sql);

//卸载评论
  $sql="delete from $t_event_comment where event_id=$event_id";
  $dbo->exeUpdate($sql);

//卸载图片
  $sql="delete from $t_event_photo where event_id=$event_id";
  $dbo->exeUpdate($sql);

action_return(1,'',"");
?>