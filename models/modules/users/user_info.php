<?php
 //引入模块公共方法文件
 require("foundation/fgrade.php");
 require("foundation/module_users.php");
 require("api/base_support.php");

	//语言包引入
	$u_langpackage=new userslp;

	//变量获得
	$url_uid=intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();
	$show_type=intval(get_argg('single'));
	$is_finish=intval(get_argg('is_finish'));

  //引入模块公共权限过程文件
	$is_self_mode='partLimit';
	$is_login_mode='';
	require("foundation/auser_validate.php");
	
	//数据表定义
	$t_user_information=$tablePreStr."user_information";

	dbtarget('r',$dbServs);
	$dbo=new dbex;
	
	//获取用户自定义属性列表
	$information_rs=array();
	$information_rs=userInformationGetList($dbo,'*');
	
	//用户自定义资料预定义
	$info_c_rs=array();
	$info_c_rs=userInformationCombine($dbo,$userid);
	
	//用户已定义资料
	$user_row = api_proxy("user_self_by_uid","*",$userid);

	//性别预定义
	$woman_c=$user_row['user_sex'] ? "checked=checked":"";
	$man_c=$user_row['user_sex'] ? "":"checked=checked";
	
?>