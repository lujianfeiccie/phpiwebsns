<?php
include(dirname(__file__)."/../includes.php");

//活动基础api函数
function event_member_base($fields="*",$condition="",$get_type="",$num="",$by_col="user_id",$order="asc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_event_member=$tablePreStr."event_members";
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
  	$condition = $condition ? " $condition " : " 1 ";
	$by_col = $by_col ? " $by_col " : " user_id ";
	$order = $order ? $order:"asc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$num = $num ? $num : 20;
 	$sql=" select $fields from $t_event_member where $condition order by $by_col $order ";
	if(empty($result_rs)){
  	$dbo->setPages($num,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

//获取正式成员
function event_member_by_eid($fields="*",$eid='',$num="",$status=""){
	global $tablePreStr;
	$fields=filt_fields($fields);
	$eid=intval($eid);
	$status = $status ? $status : 2;
	$condition=" event_id=$eid and status>=$status ";
	return event_member_base($fields,$condition,'',$num);
}

//获取所有成员
function event_member_by_status($fields="*",$eid="",$status="",$num=""){
	global $tablePreStr;
	$fields=filt_fields($fields);
	$eid=intval($eid);
	$condition=" event_id=$eid ";
	if($status !== '')
	{
		$status=intval($status);
		$condition .= " and status=$status ";
	}
	return event_member_base($fields,$condition,'',$num);
}
//获取会员的信息
function event_member_by_uid($fields="*",$eid="",$uid="")
{
	global $tablePreStr;
	$eid=intval($eid);
	$uid=intval($uid);
	$condition=" event_id=$eid and user_id=$uid ";
	return event_member_base($fields,$condition,"getRow");
}

?>