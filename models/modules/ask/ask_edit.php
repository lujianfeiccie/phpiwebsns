<?php
	//必须登录才能浏览该页面
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");

	//限制时间段访问站点
	limit_time($limit_action_time);

	//引入模块公共方法文件
	require("foundation/module_ask.php");
	require("foundation/fplugin.php");

	//语言包引入
	$b_langpackage=new bloglp;

	//变量定义
	$user_id=get_sess_userid();
	$ask_id=intval(get_argg('id'));
	$reward_arr=array(0,5,10,20,30,50,100);

	//数据表定义
	$t_ask=$tablePreStr."ask";
	$t_ask_type=$tablePreStr."ask_type";
	$t_users=$tablePreStr."users";
	
	//读定义
	dbtarget('r',$dbServs);
	$dbo=new dbex;
	
	//获取用户积分,用于js校验
	$sql="select integral from $t_users where user_id=$user_id ";		
	$integral=$dbo->getRow($sql);
	$integral=$integral['integral'];

	$titleStr='新问题';
	$goBackUrl='modules.php?app=ask';
	$form_action="do.php?act=ask_add";
	$ask_row=array(
		'title' => '',
		'detail' => '',
		'replenish' => '',
		'type_id' => '0',
		'type_name' => '',
		'reward' => '0'
	);

	//判断是否编辑问题内容
	if($ask_id!=""){
		$titleStr='修改问题';
		$goBackUrl='modules.php?app=ask_show&id='.$ask_id;
    	$form_action="do.php?act=ask_edit&id=".$ask_id;
		$sql="select * from $t_ask where ask_id=$ask_id ";		
		$ask_row=$dbo->getRow($sql);
	}
	
	$sql="select * from $t_ask_type order by order_num asc";	
	$type_rs = $dbo->getAll($sql);
	
?>
