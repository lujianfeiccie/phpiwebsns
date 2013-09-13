<?php
//必须登录才能浏览该页面
require("foundation/auser_mustlogin.php");
require("api/base_support.php");
require("foundation/fcontent_format.php");
require("foundation/module_mypals.php");
require("foundation/module_users.php");
require("foundation/fpages_bar.php");
//引入语言包
$mp_langpackage=new mypalslp;
$f_langpackage=new friendlp;
$hi_langpackage=new hilp;
$u_langpackage=new userslp;
$send_hi="hi_action";
$t_users=$tablePreStr."users";
$userId = get_sess_userid();
$condition = "";
$pals = get_sess_mypals();
$send_script_js="location.href='modules.php?app=msg_creator&2id={uid}&nw=1';";
$send_join_js="mypals_add({uid});";
if (!empty($pals)){
	$condition .=" and user_id not in ({$pals},$userId)";
} else {
	$condition .= " and user_id <> $userId";
}
$sql=" select * from $t_users where is_pass = 1  $condition ";
$dbo=new dbex;
dbplugin('r');
$page_num=intval(get_argg('page'));
$dbo->setPages(20,$page_num);
$rec = $dbo->getRs($sql);
$page_total= $dbo->totalPage;//分页总数
$isNull=0;
if(empty($rec)){
	$isNull=1;
	$recommend =array();
} else {
	$recommend =$rec;
}
?>