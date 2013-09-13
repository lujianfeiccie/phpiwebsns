<?php
	require("foundation/auser_mustlogin.php");
	require("api/base_support.php");
	
	//引入语言包
	$ef_langpackage=new event_frontlp;
	
	//限制时间段访问站点
	limit_time($limit_action_time);
	
	//变量取得
	$user_id=get_sess_userid();
	$event_id=intval(get_argg('event_id'));
	$mod=short_check(get_argg('mod'));

	$status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
	$status=$status['status'];
	if(!isset($status)||$status<2){
		echo "<script type='text/javascript'>alert(\"$ef_langpackage->ef_no_permission\");window.history.go(-1);</script>";
		exit();
	}

	//变量定义区
	$t_online = $tablePreStr."online";
	$session_code=md5(rand(0,10000));

	$sess_code_str=$session_code."|".$user_id;

	$dbo = new dbex;
	dbtarget('w',$dbServs);
	$sql="update $t_online set session_code = '$session_code' where user_id=$user_id";
	$dbo->exeUpdate($sql);
?>