<?php
include(dirname(__file__)."/../includes.php");

//群组基础api函数
function event_com_read_base($fields="*",$condition="1",$get_type="",$num="",$by_col="comment_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_event_comment=$tablePreStr."event_comment";
	$result_rs=array();
	$dbo=new dbex;
  dbplugin('r');
	$by_col = $by_col ? " $by_col " : " comment_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type ? "getRow":"getRs";
  $sql=" select $fields from $t_group_comment where $condition order by $by_col $order ";
	if(empty($result_rs)){
  	$dbo->setPages(20,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

//获取留言信息
function event_com_by_cid($fields="*",$id){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$get_type="";
	if(strpos($id_str,",")){
		$condition=" comment_id in ($id_str) ";
	}else{
		$condition=" comment_id = $id_str ";
		$get_type="getRow";
	}
	return event_com_read_base($fields,$condition,$get_type);
}

//获取该活动的留言
function event_com_by_eid($fields="*",$id,$num=10){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " event_id in ($id_str) ":" event_id=$id_str ";
	return event_com_read_base($fields,$condition,'',$num);
}

//获取该用户的留言
function event_com_by_uid($fields="*",$id,$num=10){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition=strpos($id_str,',') ? " user_id in ($id_str) ":" user_id=$id_str ";
	return event_com_read_base($fields,$condition,'',$num);
}


?>