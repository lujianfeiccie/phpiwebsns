<?php
	//引入公共方法	
	require("api/base_support.php");

	//变量定义区
	$sess_uid=get_sess_userid();
	$ask_id=intval(get_argg('ask_id'));
	$reply_id=intval(get_argg('reply_id'));
	$user_id=intval(get_argg('user_id'));
	
	if($sess_uid!=$user_id){
		echo '您无权进行此操作！';
		exit;
	}
		
	//数据表定义
	$t_ask=$tablePreStr."ask";
	$t_ask_reply=$tablePreStr."ask_reply"; 

	$dbo=new dbex();
	dbtarget('w',$dbServs);	
	
	$is_true=0;
	$sql="delete from $t_ask_reply where reply_id=$reply_id";
	if($dbo->exeUpdate($sql)){
	  $sql="update $t_ask set reply_num=reply_num-1 where ask_id=$ask_id";	  
	  $is_true=$dbo->exeUpdate($sql);
	}
	if($is_true){
		echo "success";
	}else{
		echo '删除失败，请重新操作！';
	}

?>