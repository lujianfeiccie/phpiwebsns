<?php
  //引入公共模块
	require("foundation/fpages_bar.php");
	require("foundation/module_ask.php");
	require("api/base_support.php");

	//语言包引入
	$b_langpackage=new bloglp;

	//变量区

	$ses_uid=get_sess_userid();
	$url_uid = intval(get_argg('user_id'));
	$mod=get_argg('mod');
	$type=intval(get_argg('type'));
	$page_num=intval(get_argg('page'));
	$page_total='';
	$mod_unsolved='';
	$mod_solved='';
	$mod_reply='';
	$mod_reward='';
	$mod_mine='';
	$mod_mine_reply='';
	$sql='';
	$ask_rs=array();
	
	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");	

	//数据表定义
	$t_ask=$tablePreStr."ask";
	$t_ask_type=$tablePreStr."ask_type";

	dbtarget('r',$dbServs);
	$dbo=new dbex;
	
	switch($mod){
		//已解决
		case "solved":
			$mod_solved='class=active';
			$condition=" where status=1 ";
			$order=" order by ask_id desc ";
		break;
		//最新回复
		case "reply":
			$mod_reply='class=active';
			$condition=" where status=0 and reply_num>0 ";
			$order=" order by reply_time desc ";
		break;
		//高分问题
		case "reward":
			$mod_reward='class=active';
			$condition=" where status=0 ";
			$order=" order by reward desc ";
		break;
		//我的问题
		case "mine":
			$mod_mine='class=active';
			$condition=" where user_id=$userid ";
			$order=" order by ask_id desc ";
		break;
		//我回答过的问题
		case "mine_reply":
			$mod_mine_reply='class=active';		
			$ask_id_str=get_reply_askid($dbo,$userid);
			$order="";
			if($ask_id_str){
				$condition=" where ask_id in ($ask_id_str) ";
			}else{
				$condition=" where ask_id=0 ";
			}
		break;
		//默认未解决问题
		default:
			$mod_unsolved='class=active';
			$condition=" where status=0 ";
			$order=" order by ask_id desc ";
		break;
	}
	
	if($type){
		$condition.=" and type_id=$type ";
	}
	
	$sql="select * from $t_ask ".$condition.$order;
	$dbo->setPages(20,$page_num);//设置分页
	$ask_rs=$dbo->getRs($sql); //取得结果集
	$page_total=$dbo->totalPage; //分页总数
	
	
	//分类列表
	$sql="select * from $t_ask_type order by order_num asc ";
	$ask_type_rs=$dbo->getAll($sql);


	//控制数据显示
	$content_data_none="content_none";
	$isNull=0;
	if(empty($ask_rs)){
		$isNull=1;
		$content_data_none="";
	}
	?>