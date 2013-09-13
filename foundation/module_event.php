<?php
//引入语言包

function event_sort_list($event_sort_rs,$selectedId)
{
	global $lp_cho;
    $type_list="<select name='type_id' id='type_id' >";
         $seleStr='';
         if($selectedId==""){ $seleStr='selected'; }
         $type_list=$type_list.'<option '.$seleStr.' value="">请选择</option>';
         foreach($event_sort_rs as $rs){
           if($selectedId==$rs['type_id']){$seleStr='selected';}else{$seleStr='';}
            $type_list=$type_list.'<option '.$seleStr.' value="'.$rs['type_id'].'">'.$rs['type_name'].'</option>';
         }
   	$type_list=$type_list.'</select>';
   	return $type_list;
}

//我的活动
function show_action($creator,$user_id,$event_id){
	$exit_action="content_none";
	$drop_action="content_none";
	$manage_action="content_none";
	$follow_action="content_none";
	$status='';
	$user_id= $user_id ? $user_id:get_sess_userid();
	if($user_id==$creator){
		$drop_action="";
		$manage_action="";
		$status="发起人";
	}
	else{
		$user_status=api_proxy("event_member_by_uid","status",$event_id,$user_id);
		if($user_status['status']==1){
			$follow_action="";
			$status="关注者";
		}else if($user_status['status']==2){
			$exit_action="";
			$status="成员";
		}else if($user_status['status']==3){
			$manage_action="";
			$status="组织人";
		}
	}
	return $action=array("drop"=>$drop_action,"manage"=>$manage_action,"exit"=>$exit_action,"follow"=>$follow_action,"status"=>$status);
}

//活动类型
function event_type($dbo,$type_id){
	global $tablePreStr;
	$t_event_type=$tablePreStr."event_type";
	$sql="select `type_name` from $t_event_type where type_id=$type_id ";
	$type=$dbo->getRow($sql);
	return $type['type_name'];
}
//活动人数
function event_limit_num($limit_num,$member_num){
	if($limit_num=='0'){
		return "不限";
	}else{
		return $limit_num." （还剩 ".($limit_num-$member_num)." 个名额）";
	}
}
//成员身份
function get_member_status($status){
	if($status=="0"){ return "待审核"; }
	if($status=="1"){ return "关注者"; }
	if($status=="2"){ return "成员"; }
	if($status=="3"){ return "组织人"; }
	if($status=="4"){ return "发起人"; }
}

//成员管理操作显示
function show_manage_act($event_id,$user_status,$member_status){
	$b_del="content_none";
	$b_app="content_none";
	$b_rev="content_none";
	if($user_status>$member_status){
		$b_del="";
		if($user_status==4){
			if($member_status==3){	$b_rev="";}
			else{	$b_app="";}
		}
	}
	return array("b_del" => $b_del,"b_app" => $b_app,"b_rev" => $b_rev);
}


//获取有权看到的活动的ID
function get_show_event_id($dbo,$ses_uid){
	global $tablePreStr;
	$t_event_invite=$tablePreStr."event_invite";
	$t_event_members=$tablePreStr."event_members";
	
	$sql="select event_id from $t_event_members where user_id=$ses_uid union select event_id from $t_event_invite where to_user_id=$ses_uid";
	$event_id_rs=$dbo->getALL($sql);
	$event_id_str='';
	foreach($event_id_rs as $val){
		if($event_id_str!=''){
			$event_id_str.=",";
		}
		$event_id_str.=$val['event_id'];
	}
	return $event_id_str;
}

//地址判断
function get_manage_reside($reside_province,$reside_city){
	$reside_str="未设置";
	if($reside_province){
		$reside_str=$reside_province==$reside_city?$reside_province:$reside_province." ".$reside_city;
	}
	return $reside_str;
}
?>