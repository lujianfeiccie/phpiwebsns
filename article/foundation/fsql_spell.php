<?php
//插入的公共方法
function insert_spell($table,$insert_array){
	$dbo=new dbex;
  dbplugin('w');
  $cols='';
  $values='';
  foreach($insert_array as $key => $rs){
  	if($cols!=''){
			$cols.=",";
			$values.=",";
  	}
		$cols.="`".$key."`";
		$values.=$rs;
  }
	$sql="insert into $table ($cols) values ($values)";
	if($dbo->exeUpdate($sql)){
		$last_id=mysql_insert_id();
		return $last_id ? $last_id : true;
	}else{
		return false;
	}
}

//更新的公共方法
function update_spell($table,$update_array,$condition){
	$dbo=new dbex;
  dbplugin('w');
  $update_str='';
  foreach($update_array as $key => $rs){
  	if($key!=''){
	  	if($update_str!=''){
				$update_str.=",";
	  	}
			$update_str.="`$key`=$rs";
		}
  }
  $sql="update $table set $update_str where $condition";
  return $dbo->exeUpdate($sql);
}

//删除的公共方法
function del_spell($table,$condition){
	$dbo=new dbex;
  dbplugin('w');
  $sql="delete from $table where $condition";
  return $dbo->exeUpdate($sql);
}

//读取的公共方法
function select_spell($table,$fields="*",$condition="1=1",$order_col='',$order='',$get_type="getRs",$cache=0,$cache_key='',$num=''){
	global $page_data_num;
	global $page_num;
	global $page_total;
	global $cachePages;
	$get_type=$get_type ? $get_type:'getRs';
	$page_data_num=$page_data_num ? $page_data_num : 20;
	$order_str='';
	$limit=$num ? " limit $num ":'';
	$result_rs=array();
	$is_perpage=0;
	$dbo=new dbex;
  dbplugin('r');
  $fields=filt_fields($fields);

  if($get_type=='getRs' && intval($num)==0){
  	$is_perpage=1;//判断是否有分页
  }
  
  if($order_col!=''){
	  if(strpos($order_col,',')){
	  	$order_col_array=explode(',',$order_col);
	  	$order_array=explode(',',$order);
	  	$order_str="order by $order_col_array[0] $order_array[0],$order_col_array[1] $order_array[1]";
	  }else{
	  	$order_str="order by $order_col $order";
	  }  	
  }

  $condition=$condition ? $condition:"1=1";
	$sql="select $fields from $table where $condition $order_str";
	
	if($cache==1&&$cache_key!=''&&0){//缓存处理
		if(strpos($order_col,',')){
			$corder_cache = isset($order_col_array[0]) ? '_c['.$order_col_array[0].'_'.$order_col_array[1].']' :'';
			$aorder_cache = isset($order_array[0]) ? '_a['.$order_array[0].'_'.$order_array[1].']':'';
		}else{
			$corder_cache = $order_col ? '_c['.$order_col.']' :'';
			$aorder_cache = $order ? '_a['.$order.']':'';
		}
		$key=$cache_key.$corder_cache.$aorder_cache;
		$key_mt=$cache_key.'_mt';
		
		if($is_perpage==1){//分页缓存处理
			$limit=$limit ? $limit : ' limit '.$cachePages*$page_data_num;
			$sql_limit=$sql.$limit;
			$result_rs_total=model_cache($key,$key_mt,$dbo,$sql_limit,$get_type);
			$page_num = $page_num ? $page_num-1:0;
			$key=$cache_key.$corder_cache.$aorder_cache.'_total';
			$sql_count="select count(*) as total_count ".strstr($sql,"from");//查询总数
			$total_row=model_cache($key,$key_mt,$dbo,$sql_count,"getRow");
			$result_rs_cut=array_chunk($result_rs_total,$page_data_num);
			$result_rs=$result_rs_cut[$page_num];
			$page_total=floor(($total_row['total_count']-1)/$page_data_num)+1;//总页数
		}else{//无分页缓存处理
			$sql_limit=$sql.$limit;
			$result_rs=model_cache($key,$key_mt,$dbo,$sql_limit,$get_type);
		}
	}else{//无缓存处理
		$sql.=$limit;
	}
	if(empty($result_rs)){
		if($is_perpage==1){
			$dbo->setPages($page_data_num,$page_num);
			$result_rs=$dbo->getRs($sql);
			$page_total=$dbo->totalPage;
		}else{
			$result_rs=$dbo->{$get_type}($sql);
		}
	}
	return $result_rs;
}

//post,get方式获取
function request_array($par_array,$spell_array,$must_array=array()){
	$par_array=array_slice($par_array,count($spell_array));
	foreach($par_array as $rs){
		if(array_key_exists($rs,$_REQUEST)){
			if(array_key_exists($rs,$must_array)){
				if($_REQUEST[$rs]===''){
					echo 'error:'.$must_array[$rs].'不能为空';exit;
				}
			}
			$spell_array[]="'".html_filter($_REQUEST[$rs])."'";
		}
	}
	return $spell_array;
}

//更新权限方法
function update_privacy_cache($table){
	$dbo=new dbex;
	dbplugin('w');
	$sql="select resource_id,modules_name from $table";
	$pri_rs=$dbo->getRs($sql);
	$pri_key=array();
	$pri_value=array();
	$pri_array=array();
	foreach($pri_rs as $val){
		$pri_key[]=$val['modules_name'];
		$pri_value[]=$val['resource_id'];
	}
  $pri_array=array_combine($pri_key,$pri_value);
  $rf=fopen('config/privacy_cache.php','w+');
  fwrite($rf,serialize($pri_array));
  fclose($rf);
}
?>