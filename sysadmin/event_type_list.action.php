<?php
require("session_check.php");
require("../foundation/fpages_bar.php");
require("../foundation/fsqlseletiem_set.php");

//语言包引入
$eb_langpackage = new event_backstagelp;

require("../foundation/fback_search.php");
require("../api/base_support.php");

//读写初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
$t_event_type = $tablePreStr."event_type";
//单条删除
$op = get_argg('op');
if($op=="del"){
	$typeid = get_argg('typeid');
	$sql = "delete from $t_event_type where type_id=$typeid";
	$dbo->exeupdate($sql);
	echo "<script language='javascript' type='text/javascript'>window.location.href='event_type_list.php';</script>";
}
?>