<?php
	//引入公共方法
	require("api/base_support.php");
	require("foundation/module_ask.php");


	//变量取得
	$reply_id=intval(get_argp("reply_id"));
	$user_id=intval(get_argp("user_id"));
	$content=html_filter(ubbImage(get_argp("CONTENT")));

  $sess_uid=get_sess_userid();

	if($sess_uid!=$user_id){
		action_return(0,'您无权进行此操作！',-1);
	}

	//数据表定义区
	$t_ask_reply=$tablePreStr."ask_reply";

	//读写分离定义函数
	$dbo = new dbex;
	dbtarget('w',$dbServs);


	$sql= "update $t_ask_reply set content='$content',edit_time='".constant('NOWTIME')."' where reply_id=$reply_id";
 	if($dbo->exeUpdate($sql)!==false){
		action_return(1,'',-1);
	}else{
		action_return(0,'修改失败，请重新操作！',-1);
	}
?>
