<?php
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");
	
	//引入语言包
	$ef_langpackage=new event_frontlp;
	
	//变量取得
	$event_id=intval(get_argg('id'));
	$user_id=get_sess_userid();
	$fs = array();
	
	//表定义区
	$t_tmp_file = $tablePreStr."tmp_file";
	
	$dbo = new dbex;
	dbtarget('r',$dbServs);
	$sql="select data_array from $t_tmp_file where mod_id=$event_id";
	$session_data=$dbo->getRow($sql);
	$fs=unserialize($session_data['data_array']);
	$sql="delete from $t_tmp_file where mod_id=$event_id";
	$dbo->exeUpdate($sql);
?>