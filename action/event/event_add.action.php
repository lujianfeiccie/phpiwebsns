<?php
//引入语言包
$ea_langpackage=new event_actionlp;

//引入模块公共方法文件
require("foundation/fcontent_format.php");
require("api/base_support.php");
require("foundation/aanti_refresh.php");
require("foundation/aintegral.php");
require("foundation/ftag.php");

//变量取得
$user_id = get_sess_userid();
$user_name = get_sess_username();
$user_sex =get_sess_usersex();
$user_ico = get_sess_userico();
$reside_province=get_session('reside_province');
$reside_city=get_session('reside_city');
$title = short_check(get_argp("title"));
$type_id = intval(get_argp("type_id"));$type_id = $type_id ? $type_id : 0;
$province = short_check(get_argp("province"));
$city = short_check(get_argp("city"));
$location = short_check(get_argp("location"));
$start_time = strtotime(get_argp("start_time"));$start_time = $start_time ? $start_time : 0;
$end_time = strtotime(get_argp("end_time"));$end_time = $end_time ? $end_time : 0;
$deadline = strtotime(get_argp("deadline"));$deadline = $deadline ? $deadline : 0;
$detail = big_check(get_argp("detail"));
$limit_num = intval(get_argp("limit_num"));$limit_num = $limit_num ? ($limit_num>2000 ? 2000:$limit_num) : 0;
$public = intval(get_argp("public"));$public = $public ? $public : 0;
$template = short_check(get_argp("template"));
$verify = get_argp("verify");$verify = $verify ? 1 : 0;
$allow_pic = get_argp("allow_pic");$allow_pic = $allow_pic ? 1 : 0;
$allow_post = get_argp("allow_post");$allow_post = $allow_post ? 1 : 0;
$allow_invite = get_argp("allow_invite");$allow_invite = $allow_invite ? 1 : 0;
$allow_fellow = get_argp("allow_fellow");$allow_fellow = $allow_fellow ? 1 : 0;
$grade=1;
$member_num=1;
$time=time();

//数据表定义区
$t_event = $tablePreStr . "event";
$t_event_members = $tablePreStr . "event_members";
$t_event_type = $tablePreStr . "event_type";

//定义写操作
	dbtarget('r',$dbServs);
	$dbo=new dbex();

//默认图片
$poster="uploadfiles/event/default_event_poster.jpg";
$poster_thumb="uploadfiles/event/default_event_poster.jpg";
$sql="select poster,poster_thumb from $t_event_type where type_id=$type_id";
$is_poster=$dbo->getRow($sql);
if($is_poster['poster'] && $is_poster['poster_thumb']){
	$poster=$is_poster['poster'];
	$poster_thumb=$is_poster['poster_thumb'];
}

//判定是否有图片
if($_FILES['attach']['name'][0]!=''){
  $base_dir="uploadfiles/event/";
  $up = new upload();
  $up->set_dir($base_dir,'{y}/{m}/{d}');//目录设置
  $up->set_thumb(150,150); //缩略图设置
  $fs = $up->execute();
  if($fs[0]['flag']==-1){
  	action_return(0,$ea_langpackage->ea_upload_exceed_limit,"-1");
  }
	$poster=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['name'];
	$poster_thumb=str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['thumb'];
}

//防止重复提交
antiRePost($title);

//读写分离定义函数
dbtarget('w',$dbServs);
$sql="insert into $t_event (user_id,user_name,title,type_id,province,city,location,start_time,end_time,deadline,detail,limit_num,public,member_num,grade,update_time,template,verify,allow_pic,allow_post,allow_invite,allow_fellow,poster,poster_thumb) values ($user_id,'$user_name','$title',$type_id,'$province','$city','$location',$start_time,$end_time,$deadline,'$detail',$limit_num,$public,'$member_num','$grade','$time','$template',$verify,$allow_pic,$allow_post,$allow_invite,$allow_fellow,'$poster','$poster_thumb')";

if($dbo->exeUpdate($sql)){
	$event_id = mysql_insert_id();
	$sql = 'INSERT INTO '.$t_event_members.' (event_id,user_id,user_name,user_sex,user_ico,reside_province,reside_city,dateline,status) VALUES '."('$event_id','$user_id','$user_name','$user_sex','$user_ico','$reside_province','$reside_city',".$time.",4)";
	$dbo->exeUpdate($sql);
	if($public!=2){
		$title="发起了活动<a href='home.php?h=".$user_id."&app=event_space&event_id=".$event_id."' target='_blank'>".$title."</a>";
		$content="<img class='photo_frame' src='".$poster_thumb."' />";
		$is_suc=api_proxy("message_set",0,$title,$content,0,11);
	}
	action_return(1,"","modules.php?app=event_space&event_id=$event_id");
}else{
	action_return(0,$ea_langpackage->ea_launch_failure_resubmit,'-1');exit;
}



?>