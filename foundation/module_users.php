<?php
	//引入语言包
	$user_langpackage=new userslp;
	$u_man=$user_langpackage->u_man;
	$u_wen=$user_langpackage->u_wen;
	$u_sec=$user_langpackage->u_sec;
	$lp_u_not=$user_langpackage->u_select;
	$lp_u_year=$user_langpackage->u_year;
	$lp_u_month=$user_langpackage->u_month;
	$lp_u_day=$user_langpackage->u_day;
	$u_b_can=$user_langpackage->u_b_can;
	$u_b_con=$user_langpackage->u_b_con;
	$u_choose_type=$user_langpackage->u_choose_type;
	$u_to_you=$user_langpackage->u_to_you;
//引入公共方法
require_once("foundation/fsqlseletiem_set.php");

function update_user_ico(&$dbo,$t_table,$field_ico,$field_id,$value_id,$ico_url){
   $sql="update $t_table set $field_ico='$ico_url' where $field_id='$value_id'";
   if($dbo->exeUpdate($sql)){
      return true;
   }else{
   	  return false;
   }
}

function get_user_info_item($dbo,$select_items,$t_user,$uid)
{
	   $si_item_sql=get_select_item_sql($select_items);
	   $sql="select $si_item_sql from $t_user where user_id=$uid";
		 return $dbo->getRow($sql);
}

//获取空间主人的姓名
function get_hodler_name($holder_id){
	$holder_name=get_session($holder_id.'_holder_name');
	if($holder_name==''){
		$user_info=api_proxy("user_self_by_uid","user_name",$holder_id);
		set_session($holder_id.'_holder_name',$user_info['user_name']);
		$holder_name=$user_info['user_name'];
	}
	return $holder_name;
}

function get_user_info(&$dbo,$table,$uid)
{
		return get_user_info_item($dbo,'*',$table,$uid);
}


function get_user_online_state(&$dbo,$t_online,$uid)
{
		$sql="select hidden from $t_online where user_id=$uid";
	  return $dbo->getRow($sql);
}

function get_user_sex($sexPara){
	global $u_man;
	global $u_wen;
   if($sexPara=='0'){
	 	   return $u_wen;
   }else if($sexPara=='1'){
	 	   return $u_man;
   }else {
   	   return '';
   }
}


function get_birth_date($b_year,$b_month,$b_day){
	global $setMinYear;
	global $setMaxYear;
	global $lp_u_not;
	global $lp_u_year;
	global $lp_u_month;
	global $lp_u_day;

	//出生年
	echo "
	<select id='birth_year' name='birth_year'>
		<option value=''>$lp_u_not</option>";
			for($i=$setMinYear; $i<=$setMaxYear; $i++){
				echo "<option value=\"$i\"";
				if($b_year==$i){
					echo "selected=selected";
					}
				echo ">$i</option>";
			}
	echo "</select>";
	echo $lp_u_year;

	//出生月
	echo "
	<select id='birth_month' name='birth_month'>
		<option value=''>--</option>";
			for($i=1; $i<=12; $i++){
				echo "<option value=\"$i\"";
				if($b_month==$i){
					echo "selected=selected";
				}
				echo ">$i</option>";
			}
	echo "</select>";
	echo $lp_u_month;

	//出生日
	echo "
	<select id='birth_day' name='birth_day'>
		<option value=''>--</option>";
			for($i=1; $i<=31; $i++){
				echo "<option value=\"$i\"";
				if($b_day==$i){
					echo "selected=selected";
					}
				echo ">$i</option>";
			}
	echo "</select>";
	echo $lp_u_day;
}

function pri_ques($acc_ques,$acc_answ,$holder_id){
	$q_arr=explode(',',$acc_ques);
	$an_array=explode(',',$acc_answ);
	echo '<select name="questions" size="1" style="width:165px">';
	set_session($holder_id.'homeAccessAnswers',$an_array);
	$i=0;
	foreach($q_arr as $key=>$qstr){
	if($qstr!=""){
	echo "<option value=$key>$qstr</option>";
	}
	$i++;
	}
echo '</select>';
}

function search_age_range(){
	echo '<select name="min_age">';
	for($i=10;$i<=65;$i++){
		if($i==18){
			echo "<option value=$i selected=seleced>$i</option>";
		}else{
			echo "<option value=$i>$i</option>";
			}
	}
	echo '</select>&nbsp至&nbsp';
	echo '<select name="max_age">';
	for($i=18;$i<=65;$i++){
		if($i==25){
			echo "<option value=$i selected=seleced>$i</option>";
		}else{
			echo "<option value=$i>$i</option>";
		}
	}
}

$hi_langpackage=new hilp;
$lp_hi_type_0=$hi_langpackage->hi_type_0;
$lp_hi_type_1=$hi_langpackage->hi_type_1;
$lp_hi_type_2=$hi_langpackage->hi_type_2;
$lp_hi_type_3=$hi_langpackage->hi_type_3;
$lp_hi_type_4=$hi_langpackage->hi_type_4;
$lp_hi_type_5=$hi_langpackage->hi_type_5;
$lp_hi_type_6=$hi_langpackage->hi_type_6;
$lp_hi_type_7=$hi_langpackage->hi_type_7;
$lp_hi_type_8=$hi_langpackage->hi_type_8;
$lp_hi_type_9=$hi_langpackage->hi_type_9;
$lp_hi_type_10=$hi_langpackage->hi_type_10;
$lp_hi_type_11=$hi_langpackage->hi_type_11;

function show_hi_type($hi){
	global $lp_hi_type_0;
	global $lp_hi_type_1;
	global $lp_hi_type_2;
	global $lp_hi_type_3;
	global $lp_hi_type_4;
	global $lp_hi_type_5;
	global $lp_hi_type_6;
	global $lp_hi_type_7;
	global $lp_hi_type_8;
	global $lp_hi_type_9;
	global $lp_hi_type_10;
	global $lp_hi_type_11;
	global $u_to_you;
	$hi_str=$u_to_you.${'lp_hi_type_'.$hi};
	return $hi_str;
}

function hi_window(){
	global $lp_hi_type_0;
	global $lp_hi_type_1;
	global $lp_hi_type_2;
	global $lp_hi_type_3;
	global $lp_hi_type_4;
	global $lp_hi_type_5;
	global $lp_hi_type_6;
	global $lp_hi_type_7;
	global $lp_hi_type_8;
	global $lp_hi_type_9;
	global $lp_hi_type_10;
	global $lp_hi_type_11;
	global $u_b_can;
	global $u_b_con;
	global $skinUrl;

	$str = '';
	$str .= '<div class="hi_list">';
	for($i=0; $i<12; $i++)
	{
		$str .= '<li><input type="radio" '.($i ? '' : 'checked="checked"').' name="hi_type" value="'.$i.'" /><img src="skin/'.$skinUrl.'/images/pokeact_'.$i.'.gif">'.${'lp_hi_type_'.$i}.'</li>';
	}
	$str .= '</div>';
	return $str;
}

function get_dressup($dbo,$table,$holder_id,$dress_type,$dress_name){
	global $skinUrl;
	$home_dress_url='';
	$tpl_array=explode("/",$skinUrl);
	$tpl_name=$tpl_array[0];
	$dress_url="skin/".$tpl_name."/home/";
	if(get_session($holder_id.'_dressup')==NULL){
		$sql="select dressup from $table where user_id=$holder_id";
		$user_array=$dbo->getRow($sql);
		set_session($holder_id.'_dressup',$user_array['dressup']);
	}
	if($dress_name!=NULL || get_session($holder_id.'_dressup')!='0'){
		require($dress_url."tip.php");
		if(isset($home_dressup_array[get_session($holder_id.'_dressup')][$dress_type])){
			$home_dress_url=$home_dressup_array[get_session($holder_id.'_dressup')][$dress_type];
		}
		if(isset($home_dressup_array[$dress_name][$dress_type])){
			$home_dress_url=$home_dressup_array[$dress_name][$dress_type];
			if($dress_type=='home'){
				echo '<script>dress_home("'.$dress_name.'");</script>';
			}
		}
		echo '<link rel="stylesheet" type="text/css" href="'.$dress_url.$home_dress_url.'">';
	}
}




/******************用户资料自定义*******************************/

/**
 * @desc       根据条件取得用户属性列表
 */
function userInfoGetList($dbo,$cols='*',$condition=false){
	global $tablePreStr;
	$t_user_info=$tablePreStr."user_info";
	$condition=$condition?"where $condition":"";
	
	$sql="select $cols from $t_user_info $condition order by id desc ";
	return $dbo->getAll($sql);
}
 

/**
 * @desc       自定义属性列表
 */
function userInformationGetList(&$dbo,$cols='*',$condition=false){
	global $tablePreStr;
	$t_user_information=$tablePreStr."user_information";
	$condition=$condition?"where $condition":"";
	
	$sql="select $cols from $t_user_information $condition order by sort asc ";
	return $dbo->getAll($sql);
}

/**
 * @desc       由user_id得到该用户的属性信息
 * @param      int $user_id 用户id
 * @return     array $array;
 */
function userInformationCombine(&$dbo,$user_id){
	global $tablePreStr;
	$t_user_information=$tablePreStr."user_information";
	$t_user_info=$tablePreStr."user_info";

	$sql="select $t_user_info.user_id,$t_user_info.info_value,$t_user_information.* from  $t_user_information , $t_user_info where $t_user_information.info_id=$t_user_info.info_id and $t_user_info.user_id=$user_id group by $t_user_info.info_id order by  $t_user_information.sort asc";		

	return $dbo->getAll($sql);
}

/**
 * @desc       根据user_id和info_id得到该用户的某属性
 * @param      int $user_id 用户id
 * @param      int $info_id 属性id
 * @param      int $type	属性类型;
 * @return     array $array;
 */
function getInfoById($dbo,$user_id,$info_id,$type){
	global $tablePreStr;
	$t_user_info=$tablePreStr."user_info";

	$sql="select * from $t_user_info where user_id=$user_id and info_id=$info_id order by id asc ";
	
	if($type==3){
		return $dbo->getAll($sql);
	}else{
		return $dbo->getRow($sql);
	}
}

/**
 * @desc       根据回车换行把字符串型的属性值转换成数组
 * @param      int $user_id 用户id
 * @param      int $info_id 属性id
 * @param      int $type	属性类型;
 * @return     array $array;
 */
function changeInformationArray($attr_string){
	$str_array=array();
	if($attr_string){
		$temp=preg_replace("/[\n]+/",'|',$attr_string);
    	$str_array=explode('|',$temp);
	}
	return $str_array;
}

/**
 * @desc       展示自定义化的属性信息
 * @param      int $type	属性类型;
 * @param      String $values 属性值
 * @param      int $info_id 属性id
 * @param      int $user_id 用户id
 * @return     array $array;
 */
function getInformationValue(&$dbo,$type,$values,$info_id,$user_id){
	//获取该用户关于此属性的信息
	$user_info=getInfoById($dbo,$user_id,$info_id,$type);
	//把属性值转换成数组
	$value_array=changeInformationArray($values);
	
	//文本类型
	if($type==0){
		echo "<input type='text' class='small-text' name='info[".$info_id."]' value='".$user_info['info_value']."' />";
	}
	//下拉列表类型
	else if($type==1){
		echo "<select name='info[".$info_id."]'>";
		foreach($value_array as $rs){
			$rs=trim($rs);
			$selected = $user_info['info_value']==$rs?"selected":"";
			echo "<option value='".$rs."' ".$selected.">".$rs."</option>";
		}			
	   echo "</select>";
	}
	//单选类型
	else if($type==2){
		foreach($value_array as $rs){
			$rs=trim($rs);
			$selected = $user_info['info_value']==$rs?"checked":"";
			echo "<input name='info[".$info_id."]' value='".$rs."' type='radio' ".$selected." />".$rs;
		}
	}
	//多选类型
	else if($type==3){
		$user_info_array=array();
		foreach($user_info as $val){
			$user_info_array[]=$val['info_value'];
		}
		foreach($value_array as $key => $rs){
			$rs=trim($rs);
			$selected = in_array($rs,$user_info_array)?"checked":"";
			echo "<input name='info[".$info_id.'|'.$key."]' value='".$rs."' ".$selected." type='checkbox' />".$rs;
		}
	}
}

/**
 * @desc       获取自定义属性 多选的值
 * @param      int $info_id 属性id
 * @param      int $user_id 用户id
 * @return     String $str;
 */
function getCheckBoxValue(&$dbo,$info_id,$user_id){
	$info_rs=array();
	$condition="user_id=$user_id and info_id=$info_id";
	$info_rs=userInfoGetList($dbo,'info_value',$condition);

	$str="";
	foreach($info_rs as $val){
		$str.=$val['info_value']."&nbsp;&nbsp;";
	}
	return $str;
}

function get_infor_type($infro_id,$talbe,&$dbo){
	$sql="select input_type from $talbe where  infor_id=$infro_id ";
	$infro_row=$dbo->getRow($sql);
	return $infro_row['input_type'];
}

/**
 * @desc       删除用户的自定义属性值
 * @param      int $user_id	用户id;
 */
function userInforDel(&$dbo,$user_id){
	global $tablePreStr;
	$t_user_info=$tablePreStr."user_info";
	
	$sql="delete from $t_user_info where user_id='$user_id'";
	return $dbo->exeUpdate($sql);
}
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


function getMatchActivation(&$dbo,$user_email){
		global $tablePreStr;
		$t_user_activation=$tablePreStr."user_activation";
		$t_user=$tablePreStr."users";

		$sql = "select $t_user_activation.*,$t_user.user_id,$t_user.user_name,$t_user.activation_id from $t_user,$t_user_activation where $t_user.user_email='$user_email' and $t_user.activation_id=$t_user_activation.id";
		return $dbo->getRow($sql);
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
	$sql = "SELECT * FROM $t_users WHERE ".$condition." order by $orderCol ASC";
	$dbo->setPages($num,$page_num);	
	$fir[0] =  $dbo->getRs($sql);	
	$fir[1]=$dbo->totalPage;//分页总数
	return $fir;
}

?>