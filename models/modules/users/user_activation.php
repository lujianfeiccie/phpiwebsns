<?php
//引入公共模块
require("foundation/module_users.php");
require("api/base_support.php");

//数据表定义区
$t_users=$tablePreStr."users";
$t_user_activation=$tablePreStr."user_activation";

//获取参数
$user_email = "";
$activation_code = short_check(get_argg('activation_code'));
if($activation_code){
	$user_email = short_check(get_argg('user_email'));
}else{
	$user_email = get_session('email');
}


$dbo=new dbex;
dbtarget('w',$dbServs);

//查询匹配的激活码信息
$this_code = getMatchActivation($dbo,$user_email);
$code = $this_code['activation_code'];
$time = strtotime($this_code['time']);
$user_id = $this_code['user_id'];
$activation_id = $this_code['activation_id'];

$code_mark = false;

//计算邮箱激活码有效时间
$day = $mailCodeLifeDay*24;
$hour = $mailCodeLifeHour;
$activation_time = ($day+$hour)*60*60;

if(($time+$activation_time)<=time()){
	$code_mark = "time";
}
elseif(!$activation_code){
	$code_mark="toEmail";
}

//匹配激活码
elseif($activation_code && $activation_code == $code){

	//删除激活码
	$sql="delete from $t_user_activation where id='$activation_id'";
	$dbo->exeUpdate($sql);

	//修改用户信息
	$sql="update $t_users set activation_id='-1' where user_id=$user_id ";
	if($dbo->exeUpdate($sql)){
		$code_mark = "ok";
	}
}
else{
	$code_mark="not_match";
}
?>