<?php
function pals_birth($userId,$num,$cols="*"){
	global $tablePreStr;
	$t_users=$tablePreStr."users";
	$dbo=new dbex;
  	dbplugin('r');	
	if (empty($userId)){
		$userId = get_sess_userid();
	}
	
	
	//未来三个月月份
	$this_month = date("n");	
	$month[0] = $this_month+1>12? 1:$this_month+1;
	$month[1] = $month[0]+1>12? 1:$month[0]+1;
	$day = date("j");
	$pals = get_sess_mypals();	
	if (empty($pals)){
		return false;
	}	
	$condition = " user_id in (".$pals.") and (birth_month in ($month[0],$month[1]) or (birth_month=$this_month and birth_day>=$day))";
	$orderCol = "ABS(birth_day)"; 	
	$sql = "SELECT * FROM $t_users WHERE ".$condition." order by $orderCol ASC limit $num";
	$fir =  $dbo->getALL($sql);
	if (!empty($fir)){	
		$fir_1 = array();
		$fir_2 = array();
		$fir_3 = array();		
		foreach ($fir as $val){					
			if ($val['birth_month']==$this_month){
				$fir_1[] = $val;
			}
			if ($val['birth_month']==$month[0]){
				$fir_2[] = $val;
			}
			if ($val['birth_month']==$month[1]){
				$fir_3[] = $val;
			}
		}		
	
		$firList = array_merge($fir_1,$fir_2,$fir_3);			
		return $firList;	
	} else {
		return false;
	}	
}

function pals_friBirthMon($m,$userId,$page_num,$num=20,$cols="*"){
	global $tablePreStr;
	$t_users=$tablePreStr."users";
	$dbo=new dbex;
  	dbplugin('r');	
	$pals = get_sess_mypals();
	
	if (empty($pals)){
		return false;
	}	
	$condition = "user_id in(".$pals.") and birth_month=$m";
	$orderCol = "ABS(birth_day)"; 
	$sql = "SELECT * FROM $t_users WHERE ".$condition." order by $orderCol ASC limit $num";
	$dbo->setPages($num,$page_num);
	$fir[0] =  $dbo->getRs($sql);
	$fir[1]=$dbo->totalPage;//分页总数
	return $fir;
}