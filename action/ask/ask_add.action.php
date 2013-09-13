<?php
  //引入模块公共方法文件
  require("foundation/fcontent_format.php");
  require("api/base_support.php");
  require("foundation/aanti_refresh.php");
  require("foundation/aintegral.php");

	//引入语言包
	$b_langpackage=new bloglp;

	//变量取得
	$title=short_check(get_argp("title"));
	$detail=html_filter(get_argp("detail"));
	$type_id=intval(get_argp("type_id"));
	$type_name=short_check(get_argp("type_name"));
	$reward=intval(get_argp("reward"));

	$user_id=get_sess_userid();
	$user_name=get_sess_username();
	$uico_url=get_sess_userico();//用户头像

	//防止重复提交
	antiRePost($title);

	if($title==''){
		action_return(0,'',-1);exit;
	}

	//数据表定义区
	$t_ask=$tablePreStr."ask";
	$t_users=$tablePreStr."users";

	//读写分离定义函数
	$dbo = new dbex;
	dbtarget('w',$dbServs);

	//判断用户积分是否足够
	$sql="select integral from $t_users where user_id=$user_id ";
	$integral=$dbo->getRow($sql);
	if($integral['integral']<$reward){
		action_return(0,'您的悬赏积分不足',-1);exit;
	}
	//更新用户积分
	increase_integral($dbo,-$reward,$user_id);


	$sql="insert into $t_ask (user_id,user_name,title,detail,type_id,type_name,reward,add_time) values ($user_id,'$user_name','$title','$detail','$type_id','$type_name','$reward','".constant('NOWTIME')."')";
	if(!$dbo->exeUpdate($sql)){
		action_return(0,'发布失败',-1);exit;
	}
	$ask_id = mysql_insert_id();
	$title=$b_langpackage->b_write_new_ask."<a href=\"home.php?h=".$user_id."&app=ask_show&id=".$ask_id."\" target='_blank'>".$title."</a>";
	$content=get_lentxt($detail);
	$is_suc=api_proxy("message_set",0,$title,$content,0,12);
	action_return(1,'','modules.php?app=ask');
?>