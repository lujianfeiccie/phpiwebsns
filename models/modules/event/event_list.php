<?php
	//引入公共模块
	require("foundation/module_event.php");
	require("foundation/fpages_bar.php");
	require("api/base_support.php");

	//引入语言包
	$ef_langpackage=new event_frontlp;

	//表定义
	$t_event=$tablePreStr."event";
	$t_event_invite=$tablePreStr."event_invite";
	$t_event_members=$tablePreStr."event_members";
 
	//变量区
	$page_num=trim(get_argg('page'));
	$ses_uid=get_sess_userid();
	$mod=get_argg('mod');
	$event_rs=array();
	$list_none='';
	$mod_all='';
	$mod_recom='';
	$mod_city='';
	$no_event=''; 
	$sql='';
	$page_total='';
	$event_id_str='';
	
	//读定义
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	
	switch($mod){
		case "all":
		$event_id_str = get_show_event_id($dbo,$ses_uid);
		$spell_str='';
		if($event_id_str){
			$spell_str=" or event_id in ($event_id_str) ";
		}
		$sql="select * from $t_event where is_pass=1 and grade>=1 and (public >=1 $spell_str ) ";
		$mod_all="class=active";
		break;
		
		case "recom":
		$mod_recom="class=active";
		$sql="select * from $t_event where is_pass=1 and grade=2 and public = 2";
		break;
		
		case "city":
		$mod_city="class=active";
		$user_row=api_proxy('user_self_by_uid','reside_city',$ses_uid);
		$spell_str='';
		if($user_row['reside_city']){
			$event_id_str = get_show_event_id($dbo,$ses_uid);
			if($event_id_str){
				$spell_str=" or event_id in ($event_id_str) ";
			}
			$sql="select * from $t_event where is_pass=1 and grade>=1 and city='".$user_row['reside_city']."' and (public >=1 $spell_str ) ";
		}else{
			$no_event=$ef_langpackage->ef_set_live_city.', <a href="modules.php?app=user_info">'.$ef_langpackage->ef_now_settings.'</a>';
		}
		break;
	}
	
	if($sql){
		$dbo->setPages(10,$page_num);//设置分页
		$event_rs=$dbo->getRs($sql." order by event_id desc "); //取得结果集
		$page_total=$dbo->totalPage; //分页总数		
	}

	$no_event=$no_event ? $no_event:$ef_langpackage->ef_no_related_activity;
	
	//数据显示控制
	$list_none="content_none";
	$isNull=0;
	if(empty($event_rs)){
		$list_none="";
		$isNull=1;
	}
?>
