<?php
	//引入公共模块
	require("foundation/module_ask.php");
	require("api/base_support.php");

	//语言包引入
	$b_langpackage=new bloglp;
	$pu_langpackage=new publiclp;

	//变量区
	$url_uid = intval(get_argg('user_id'));
	$ses_uid = get_sess_userid();
	$ask_id=intval(get_argg("id"));
	$ask_row=array();
	$is_show=1;
	$status=0;	//是否已解决问题
	$is_asker=0;	//是否提问者
	$is_reply=0;	//是否回答过
	$page_num=intval(get_argg('page'));
	$reply_rs=array();
	$answer_row=array();
	$condition='';

	//引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");

	//数据表定义
	$t_ask=$tablePreStr."ask";
	$t_ask_reply=$tablePreStr."ask_reply";

	//初始化数据库操作对象
	$dbo=new dbex;
	dbtarget('w',$dbServs);

	//更新查看次数
	if($ses_uid!=getCookie('ask_'.$ask_id)){
		$sql="update $t_ask set view_num=view_num+1 where ask_id=$ask_id";
		$dbo->exeUpdate($sql);
		set_cookie('ask_'.$ask_id,$ses_uid);
	}

	if($ask_id){
		$sql="select * from $t_ask where ask_id=$ask_id";
		$ask_row=$dbo->getRow($sql);
		
		$status = $ask_row['status'];
		$is_asker=$ask_row['user_id']==$ses_uid?1:0;
	}

	//控制数据显示
	$content_data_none="content_none";
	if(empty($ask_row)){
		$is_show=0;
		$content_data_none="";
	}

	//是否已解决问题
	if($status==1){
		$sql="select * from $t_ask_reply where ask_id=$ask_id and is_answer=1 ";
		$answer_row=$dbo->getRow($sql);
		//其余的回答
		$condition=" and is_answer=0 ";
	}

	//回答列表
	$sql="select * from $t_ask_reply where ask_id=$ask_id $condition order by `reply_id` asc ";
	$reply_rs=$dbo->getRs($sql); //取得结果集
?>