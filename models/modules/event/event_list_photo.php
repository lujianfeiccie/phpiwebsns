<?php
	//引入公共模块
	require("foundation/module_event.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");

	//引入语言包
	$ef_langpackage=new event_frontlp;

	//变量区
	$page_num=intval(get_argg('page'));
	$ses_uid=get_sess_userid();
	$url_uid = intval(get_argg('user_id'));
	$event_id = intval(get_argg('event_id'));
	$photo_rs=array();
	
	//引入模块公共权限过程文件
	$is_self_mode = 'partLimit';
	$is_login_mode = '';
	require("foundation/auser_validate.php");
	
  //数据表定义
  $t_event_photo=$tablePreStr."event_photo";
  
	//权限判断
	$status=api_proxy("event_member_by_uid","status",$event_id,$ses_uid);
	$status=intval($status['status']);  
		
	$dbo=new dbex;
	dbtarget('r',$dbServs);

	$dbo->setPages(20,$page_num);//设置分页
	$sql="select * from $t_event_photo where event_id=$event_id";
	$photo_rs = $dbo->getRs($sql);
	$page_total=$dbo->totalPage;//分页总数

	//数据显示控制
	$list_none="content_none";
	$isNull=0;
	if(empty($photo_rs)){
		$isNull=1;
		$list_none="";
	}
?>