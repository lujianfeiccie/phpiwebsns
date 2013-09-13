<?php
//引入模块公共方法文件
require("session_check.php");
require("../foundation/fpages_bar.php");
require("../foundation/fsqlseletiem_set.php");
//语言包引入
$eb_langpackage = new event_backstagelp;

//引入模块公共方法文件
require("../foundation/fback_search.php");
require("../api/base_support.php");

//读写初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//定义数据库表名称
$t_event=$tablePreStr."event";
$t_event_members=$tablePreStr."event_members";
$t_event_pic=$tablePreStr."event_pic";
//接收参数
$op = get_argg('op');

//锁定操作
if($op=="lock"){
	$event_id=get_argg('event_id');
	$type_value=get_argg('type_value');
	$sql = "update $t_event set is_pass='$type_value' where event_id='$event_id'";
	$dbo->exeUpdate($sql);
	//发送锁定通知
	if($type_value==0){
		$sql = "select * from $t_event where event_id='$event_id'";
		$notice = $dbo->getRow($sql);
		$title = $eb_langpackage->eb_your.$notice['title'].$eb_langpackage->eb_activity_locked;
		$scrip_content = $notice['user_name']."：".$eb_langpackage->eb_your.$notice['title'].$eb_langpackage->eb_activity_notice_content;
		$is_success = api_proxy('scrip_send',$eb_langpackage->eb_system_sends,$title,$scrip_content,$notice['user_id'],0);
		if($is_success){
			api_proxy("message_set",$notice['user_id'],$eb_langpackage->eb_num_notice,"modules.php?app=msg_notice",0,1,"remind");
		}
	}
}

//多选删除
if($op=="del_plural"){
	$event_id = get_argp('checkany');
	if(!empty($event_id)){
		foreach($event_id as $rs){
			$sql = "delete from $t_event where event_id=$rs";
			$dbo->exeUpdate($sql);
			$sql = "delete from $t_event_members where event_id=$rs";
			$dbo->exeUpdate($sql);
			$sql = "delete from $t_event_pic where event_id=$rs";
			$dbo->exeUpdate($sql);
			echo "<script type='text/javascript'>window.location.href='event_list.php?order_by=event_id&order_sc=desc'</script>";
		}
	}else{
		echo "<script type='text/javascript'>window.location.href='event_list.php?order_by=event_id&order_sc=desc'</script>";
	}
}

//单条删除
if($op=="del_singular"){
	$event_id = get_argg('event_id');
	$sql = "delete from $t_event where event_id=$event_id";
	$dbo->exeUpdate($sql);
	$sql = "delete from $t_event_members where event_id=$event_id";
	$dbo->exeUpdate($sql);
	$sql = "delete from $t_event_pic where event_id=$event_id";
	$dbo->exeUpdate($sql);
	echo $eb_langpackage->eb_delete_succ;
}

//状态操作
if($op=="eo"){
	$event_id = get_argp('checkany');
	$grades = get_argp('radio');
	if(!empty($event_id) && $grades!=='' && $grades!==NULL){
		foreach($event_id as $rs){
			$sql = "select * from $t_event where event_id='$rs'";
			$events = $dbo->getRow($sql);
			if($events['public']==0 && $grades==2){
				echo $notice['title'].$eb_langpackage->eb_secret_not_recommended;
				echo "<script type='text/javascript'>window.location.href='event_list.php?order_by=event_id&order_sc=desc'</script>";
			}else{
				$sql = "update $t_event set grade='$grades' where event_id='$rs'";
				$dbo->exeUpdate($sql);
				echo "<script type='text/javascript'>window.location.href='event_list.php?order_by=event_id&order_sc=desc'</script>";
			}
		}
	}else{
		echo "<script type='text/javascript'>window.location.href='event_list.php?order_by=event_id&order_sc=desc'</script>";
	}
}

?>