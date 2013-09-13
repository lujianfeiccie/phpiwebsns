<?php

//引入模块公共方法文件
require("foundation/fcontent_format.php");
require("api/base_support.php");
require("foundation/aanti_refresh.php");
require("foundation/aintegral.php");
require("foundation/fplugin_form.php");
require("foundation/ftag.php");

//引入语言包
$ea_langpackage=new event_actionlp;

//变量取得
$event_id = intval(get_argp("event_id"));
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
$time=time();

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
	$poster = str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['name'];
	$poster_thumb = str_replace(dirname(__FILE__),"",$fs[0]['dir']).$fs[0]['thumb'];
}
$poster = $poster ? ",poster='$poster'" : '';
$poster_thumb = $poster_thumb ? ",poster_thumb='$poster_thumb'" : '';

//数据表定义区
$t_event = $tablePreStr . "event";

$dbo = new dbex;
//读写分离定义函数
dbtarget('w',$dbServs);

$sql= "update $t_event set title='$title',type_id='$type_id',province='$province',city='$city',location='$location',start_time='$start_time',end_time='$end_time',deadline='$deadline',detail='$detail',limit_num='$limit_num',public='$public',update_time='$time',template='$template',verify='$verify',allow_pic='$allow_pic',allow_post='$allow_post',allow_invite='$allow_invite',allow_fellow='$allow_fellow' $poster $poster_thumb where event_id=$event_id";

if($dbo->exeUpdate($sql)!==false){
	$event_row=api_proxy("event_self_by_eid","grade",$event_id);
	if($event_row['grade']==-1){
		$sql= "update $t_event set grade='0' where event_id=$event_id";
		$dbo->exeUpdate($sql);
	}
	action_return(1,"","modules.php?app=event_space&event_id=$event_id");
}else{
	action_return(0,'error','-1');
}

?>
