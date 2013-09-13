<?php
	//引入公共模块
	require("foundation/module_event.php");
	require("foundation/module_users.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");

	//引入语言包
	$ef_langpackage=new event_frontlp;

	//变量区
	$page_num=intval(get_argg('page'));
	$ses_uid=get_sess_userid();
	$url_uid = intval(get_argg('user_id'));
	$event_rs=array();
	$page_total='';
	
  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");	
	
	if($is_self=='Y'){
		$str_title=$ef_langpackage->ef_activity;
	}else{
		$holder_name=get_hodler_name($url_uid);
		$str_title=str_replace("{holder}",$holder_name,'{holder}'.$ef_langpackage->ef_is_activity);
	}	

	//缓存功能区
	$event_rs = api_proxy("event_self_by_uid","*",$userid,"getRs");

	//数据显示控制
	$list_none="content_none";
	$isNull=0;
	if(empty($event_rs)){
		$isNull=1;
		$list_none="";
	}
?>
