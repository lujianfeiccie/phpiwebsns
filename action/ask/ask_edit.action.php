<?php
	require("foundation/aintegral.php");
	//引入语言包
	$b_langpackage=new bloglp;

	//变量取得
	$ask_id=intval(get_argg("id"));

	$replenish=html_filter(get_argp("replenish"));
	$reward=intval(get_argp("reward"));

	$user_id=get_sess_userid();
	$user_name=get_sess_username();

	//数据表定义区
	$t_ask=$tablePreStr."ask";
	$t_users=$tablePreStr."users";

	//读写分离定义函数
	$dbo = new dbex;
	dbtarget('w',$dbServs);

	$sql="select user_id, reward from $t_ask where ask_id=$ask_id ";
	$old_reward=$dbo->getRow($sql);
	if($old_reward['user_id']!=$user_id){
		action_return(0,'您没有权限进行此操作!','-1');
	}


	//判断用户积分是否足够
	$sql="select integral from $t_users where user_id=$user_id ";
	$integral=$dbo->getRow($sql);

	$gap_num=$reward-$old_reward['reward'];//现在积分与原积分之差
	//大于0 说明增加了悬赏积分
	if($gap_num>0){
		if($integral['integral']<$gap_num){
			action_return(1,'您的悬赏积分不足',-1);exit;
		}
		//更新用户积分
		increase_integral($dbo,-$gap_num,$user_id);
	}


	$sql= "update $t_ask set replenish='$replenish',reward='$reward' where ask_id=$ask_id";
 	if($dbo->exeUpdate($sql)!==false){
		action_return(1,'','modules.php?app=ask_show&id='.$ask_id);
	}else{
		action_return(0,'操作失败，请重新操作!','-1');
	}
?>
