<?php
//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");
	require("foundation/fcontent_format.php");
	//引入公共模块
	require("foundation/module_mypals.php");
	require("foundation/module_users.php");	
	require("foundation/fpages_bar.php");

	//引入语言包
	$mp_langpackage=new mypalslp;
	$monthArr = array(
		1=>"一月",
		2=>"二月",
		3=>"三月",
		4=>"四月",
		5=>"五月",
		6=>"六月",
		7=>"七月",
		8=>"八月",
		9=>"九月",
		10=>"十月",
		11=>"十一月",
		12=>"十二月",		
	);
	$send_hi="hi_action";
	$user_id=get_sess_userid();
	$page_num=trim(get_argg('page'));
	$month = intval(get_argg("month"));
	if (!$month){
		$month = date("n");
	}
	$firBirth = pals_friBirthMon($month,$user_id,$page_num,20);	
	$page_total = $firBirth[1];
	$isNull=0;
	if(empty($firBirth[0])){		
		$isNull=1;
		$fir_list =array();
	} else {
		$fir_list =$firBirth[0];
	}	
?>