<?php
include(dirname(__file__)."/../includes.php");

//活动基础api函数
function event_read_base($fields="*",$condition="",$get_type="",$num="",$by_col="event_id",$order="desc",$cache="",$cache_key=""){
	global $tablePreStr;
	global $page_num;
	global $page_total;
	$t_event=$tablePreStr."event";
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
  	$condition = $condition ?  $condition  : " 1 ";
	$by_col = $by_col ? " $by_col " : " event_id ";
	$order = $order ? $order:"desc";
	$get_type = $get_type=='getRow' ? "getRow":"getRs";
	$num = $num ? $num : 20;
  	$sql=" select $fields from $t_event where $condition order by $by_col $order ";
	if(empty($result_rs)){
  	$dbo->setPages($num,$page_num);
		$result_rs=$dbo->{$get_type}($sql);
		$page_total=$dbo->totalPage;
	}
	return $result_rs;
}

//获取活动信息
function event_self_by_eid($fields="*",$id,$get_type=''){
	$fields=filt_fields($fields);
	$id_str=filt_num_array($id);
	$condition = "";
	if(strpos($id_str,",")){
		$condition.=" event_id in ($id_str) ";
	}else{
		$condition.=" event_id = $id_str ";
		$get_type=($get_type=='getRs')?"getRs":"getRow";
	}
	return event_read_base($fields,$condition,$get_type);
}

//获取该会员参加的活动的信息
function event_self_by_uid($fields="*",$id='',$get_type=''){
	global $tablePreStr;
	$id=intval($id) ? $id : get_sess_userid();
	$t_event_members=$tablePreStr."event_members";
	$eid_array=array();
	$dbo=new dbex;
  dbplugin('r');
	$sql="select event_id from $t_event_members where user_id='$id' and status>=1 ";
	$eid_array=$dbo->getRs($sql);
	if($eid_array){
		$eid_str='';
		foreach($eid_array as $rs){
			if($eid_str!=''){
				$eid_str.=',';
			}
			$eid_str.=$rs['event_id'];
		}
		$fields=filt_fields($fields);
		return event_self_by_eid($fields,$eid_str,$get_type);
	}else{
		return array();
	}
}

//获取所活动 即最新活动  end=0活动结束 1未结束 2不限
function event_self_get_all($fields="*",$end='2',$num=10){
	$num=intval($num);
	$end=intval($end);
	$condition=" grade>=1 ";
	$time=time();
	if($end==='0')
		$condition.=" and end_time <= '$time' ";
	elseif($end==1)
		$condition.=" and end_time > '$time' ";

	return event_read_base($fields,$condition,'',$num,'','',1,"new_");
}

//根据状态获取活动
function event_self_by_grade($fields="*",$grade=1,$num=10,$by_col=''){
	$num=intval($num);
	$grade=intval($grade);
	$condition = " grade = $grade ";
	return event_read_base($fields,$condition,'',$num,$by_col);
}

//获取朋友参与的活动
function event_self_by_pals($fields="*"){
	$fields=filt_fields($fields);
	$event_id_str='';
	global $tablePreStr;
	$t_event_members=$tablePreStr."event_members";
	$result_rs=array();
	$dbo=new dbex;
  	dbplugin('r');
	$pals_id=get_sess_mypals();
	$sql=" select event_id from $t_event_members where user_id in ($pals_id) and status>=2 ";
	$event_data=$dbo->getRs($sql);
	foreach($event_data as $rs){
		$event_id_str.=$rs['event_id'].",";
	}
	$event_id_str=preg_replace("/,$/","",$event_id_str);
	return event_self_by_eid($fields,$event_id_str);
}

//根据城市获取活动
function event_self_by_city($fields="*",$city="",$num=10){
	$num=intval($num);
	$condition=" grade>=1 ";
	$condition .= ($city=="") ? "":" and city = '$city' ";
	return event_read_base($fields,$condition,'',$num);
}

//获取与用户有关的活动的ID
function event_self_get_id($uid,$status=2){
	global $tablePreStr;
	$uid=intval($uid);
	$status=filt_num_array($status);
	$t_event_members=$tablePreStr."event_members";
	$eid_array=array();
	if(strpos($status,","))
		$condition=" and status in ($status) ";
	else
		$condition=" and status=$status ";
	$dbo=new dbex;
  	dbplugin('r');
	$sql="select event_id from $t_event_members where user_id='$uid' $condition ";
	$eid_array=$dbo->getRs($sql);
	if($eid_array){
		$eid_str=',';
		foreach($eid_array as $rs){
			$eid_str.=$rs['event_id'].",";
		}
		return $eid_str;
	}else{
		return '';
	}
}
?>