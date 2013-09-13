<?php
	//引入模块公共权限过程文件
	require("foundation/fpages_bar.php");
	require("foundation/module_event.php");
	require("api/base_support.php");

	//引入语言包
	$ef_langpackage=new event_frontlp;
	
	$event_name=short_check(get_argg('event_name'));
	$province=short_check(get_argg('province'));
	$city=short_check(get_argg('city'));
	$start_time1=short_check(get_argg("start_time1"));$start_time1=$start_time1 ? strtotime($start_time1):0;
	$start_time2=short_check(get_argg("start_time2"));$start_time2=$start_time2 ? strtotime($start_time2):0;
	$deadline1=short_check(get_argg("deadline1"));$deadline1=$deadline1 ? strtotime($deadline1):0;
	$deadline2=short_check(get_argg("deadline2"));$deadline2=$deadline2 ? strtotime($deadline2):0;
	$type_id=intval(get_argg('type_id'));

	$cols="";
	$ses_uid=get_sess_userid();
	$page_num=trim(get_argg('page'));
	$no_event = "";
	$mod_all = "";
	$mod_recom = "";
	$mod_city = "";
	$event_rs=array();

	//表定义
	$t_event=$tablePreStr."event";
	$t_event_invite=$tablePreStr."event_invite";
	
	//读定义
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	
	if($event_name!=''){
		$cols.=" and title like '%$event_name%' ";
	}
	
	if($province!=''){
		$cols.=" and (province like '%$province%') ";
	}

	
	if($city!=''){
		$cols.=" and (city like '%$city%') ";
	}
		
	if($deadline1 && $deadline2){
		$cols.=" and deadline BETWEEN $deadline1 and $deadline2 ";
	}

	if($start_time1 && $start_time2){
		$cols.=" and start_time BETWEEN $start_time1 and $start_time2 ";
	}
	
	if($type_id){
		$cols.=" and type_id = $type_id ";
	}
	
	$event_id_str = get_show_event_id($dbo,$ses_uid);
	$event_id_str=$event_id_str?" or event_id in ($event_id_str) ":"";
	$sql="select * from $t_event where is_pass=1 and grade>=1 and (public >=1 $event_id_str ) $cols ";

	if($sql){
		$dbo->setPages(10,$page_num);//设置分页
		$event_rs=$dbo->getRs($sql); //取得结果集
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