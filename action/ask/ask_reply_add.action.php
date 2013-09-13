<?php
	//引入公共方法
	require("foundation/fcontent_format.php");
	require("api/base_support.php");
	require("foundation/module_ask.php");
	require("foundation/aanti_refresh.php");

	//变量定义区
	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$user_ico=get_sess_userico();

	//引入语言包
	$b_langpackage=new bloglp;
	$content=html_filter(ubbImage(get_argp('CONTENT')));
	$host_id=intval(get_argp('holder_id'));
	$ask_id=intval(get_argp('ask_id'));
	$last_id='';
	$is_true=0;

	if($host_id==$user_id){
		action_return(0,'您不能回答自己的问题',-1);
	}

	antiRePost($content);

	//数据表定义
	$t_ask=$tablePreStr."ask";
	$t_ask_reply=$tablePreStr."ask_reply";

	$dbo=new dbex();
	dbtarget('w',$dbServs);

	//插入答案表
	$sql="insert into $t_ask_reply (ask_id,user_id,user_name,user_ico,content,add_time) values ($ask_id,'$user_id','$user_name','$user_ico','$content','".constant('NOWTIME')."')";
	if($dbo->exeUpdate($sql)){
		$last_id=mysql_insert_id();
		//更新问题表
		$sql="update $t_ask set reply_num=reply_num+1, reply_time='".constant('NOWTIME')."' where ask_id=$ask_id";
		$is_true=$dbo->exeUpdate($sql);

		//获取提醒机制参数
		$sql="select title,user_id,user_name from $t_ask where ask_id=$ask_id";
		$ask_title=$dbo->getRow($sql);
		$title=$ask_title['title'];
		$link="main.php?app=ask_show&id=$ask_id";


		//提醒机制
		//提醒提问者
		if($user_id!=$host_id){
			$whole='回答了您的{title}问题';
			$remind_content=str_replace("{title}","<a href=\'{link}\' onclick=\'{js}\' target=\'_blank\'>".sub_str($title,45)."</a>",$whole);
			$focus=api_proxy("message_get_remind_count",$host_id);
			if($focus[0]>=20){
				api_proxy("message_set_del","remind",'',$host_id);
			}
			api_proxy("message_set",$host_id,$remind_content,$link,1,0,"remind");
		}

		$title=$b_langpackage->b_write_ask_re."<a href=\"home.php?h=".$ask_title['user_id']."&app=ask_show&id=".$ask_id."\" target='_blank'>(".$ask_title['user_name'].$b_langpackage->b_write_ask_qu.")".$title."</a>";
		$content=get_lentxt($content);
		$is_suc=api_proxy("message_set",0,$title,$content,0,12);

	}
	action_return(1,'',-1);

?>