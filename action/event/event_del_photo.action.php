<?php
	//引入公共函数
	require("api/base_support.php");

	//变量取得
	$photo_ids=get_argp('checkany');
	$event_id=intval(get_argg('event_id'));
	$user_id=get_sess_userid();
	
	//数据库表定义
	$t_event = $tablePreStr."event";
	$t_event_photo = $tablePreStr."event_photo";
	$dbo = new dbex;
	
	//读写初始化
	dbtarget('r',$dbServs);
	foreach($photo_ids as $rs){
		$rs=intval($rs);
		$sql="select photo_thumb_src,photo_src from $t_event_photo where photo_id=$rs";
		$photo_row=$dbo->getRow($sql);
		@unlink($photo_row['photo_src']);
		@unlink($photo_row['photo_thumb_src']);
		$sql = "delete from $t_event_photo where photo_id=$rs";
		if($dbo -> exeUpdate($sql)){
			$sql = "update $t_event set photo_num=photo_num-1,update_time='".constant('NOWTIME')."' where event_id=$event_id";
			$dbo -> exeUpdate($sql);
		}
	}
	//回应信息
	action_return(1,"","modules.php?app=event_list_photo&mod=manager&event_id=$event_id");
?>
