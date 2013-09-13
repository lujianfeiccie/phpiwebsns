<?php

require("foundation/module_mypals.php");
//引入语言包
$mp_langpackage=new mypalslp;
$f_langpackage=new friendlp;
$send_hi="hi_action";
$t_users=$tablePreStr."users";
$userId = get_sess_userid();
$send_script_js="location.href='modules.php?app=msg_creator&2id={uid}&nw=1';";
$send_join_js="mypals_add({uid});";
$condition = "";
$pals = get_sess_mypals();
if (!empty($pals)){
	$condition .=" and user_id not in ({$pals},$userId)";
} else {
	$condition .= " and user_id <> $userId";
}
$sql=" select * from $t_users where is_pass = 1  $condition limit 3";
$dbo=new dbex;
dbplugin('r');
$recommend = $dbo->getALL($sql);
?>