<?php
	//引入公共方法	
	require("api/base_support.php");
	require("foundation/aintegral.php");

	//变量定义区
	$sess_uid=get_sess_userid();
	$ask_id=intval(get_argg('ask_id'));
	$reply_id=intval(get_argg('reply_id'));
	$user_id=intval(get_argg('user_id'));//回答者id
		
	//数据表定义
	$t_ask=$tablePreStr."ask";
	$t_ask_reply=$tablePreStr."ask_reply"; 

	$dbo=new dbex();
	dbtarget('w',$dbServs);	
	
	$sql="select user_id,reward,title from $t_ask where ask_id=$ask_id";
	$ask_row=$dbo->getRow($sql);
	if($ask_row['user_id']!=$sess_uid){
		echo '您无权进行此操作！';
		exit;
	}		
	if($sess_uid==$user_id){
		echo '不能采纳自己的回答为最佳答案！';
		exit;
	}
	
	$is_true=0;
	$sql="update $t_ask_reply set is_answer=1 where reply_id=$reply_id and user_id=$user_id";  
	if($dbo->exeUpdate($sql)){
		$sql="update $t_ask set status=1, solved_time='".constant('NOWTIME')."' where ask_id=$ask_id";
		$is_true=$dbo->exeUpdate($sql);
		//给与回答者积分
		increase_integral($dbo,$ask_row['reward'],$user_id);
		
		
		//获取提醒机制参数
		$title=$ask_row['title'];
		$link="main.php?app=ask_show&id=$ask_id";		
	
		//提醒回答者
		$whole='您在{title}的回答被选为最佳答案';
		$remind_content=str_replace("{title}","<a href=\'{link}\' onclick=\'{js}\' target=\'_blank\'>".sub_str($title,45)."</a>",$whole);
		$focus=api_proxy("message_get_remind_count",$user_id);
		if($focus[0]>=20){
			api_proxy("message_set_del","remind",'',$user_id);
		}
		api_proxy("message_set",$user_id,$remind_content,$link,1,0,"remind");
	}
	
	if($is_true){
		echo "success";
	}else{
		echo '采纳答案失败，请重新操作！';
	}

?>