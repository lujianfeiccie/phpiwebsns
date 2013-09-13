<?php
/*
mod值分配:
0:日志;
1:相册;
2:群组;
3:分享;
4:话题;
5:投票;
*/
//标签添加
function tag_add($tag_data,$resourse_id,$mod_id){
	if($tag_data!==''){
		global $tablePreStr;
		global $dbo;
		$t_tag=$tablePreStr."tag";
		$t_tag_relation=$tablePreStr."tag_relation";
		$dbo=new dbex;
		dbplugin('w');
		if(!is_array($tag_data)){
			$tag_array=format_tag($tag_data);
		}else{
			$tag_array=$tag_data;
		}
		foreach($tag_array as $rs){
			if($rs!==''){
				$sql="insert into $t_tag (`name`,`count`) values ('$rs',1)";
				if($dbo->exeUpdate($sql)){
					$tag_id=mysql_insert_id();
				}else{
					$sql="select id from $t_tag where name='$rs'";
					$tag_info=$dbo->getRow($sql);
					$tag_id=$tag_info['id'];
					$sql="update $t_tag set count=count+1 where id=$tag_id";
					$dbo->exeUpdate($sql);
				}
				$sql="insert into $t_tag_relation (`id`,`mod_id`,`content_id`) values ($tag_id,$mod_id,$resourse_id)";
				$dbo->exeUpdate($sql);
			}
		}
	}
}

//标签删除
function tag_del($tag_data,$resourse_id,$mod_id){
	if($tag_data!==''){
		global $tablePreStr;
		global $dbo;
		$t_tag=$tablePreStr."tag";
		$t_tag_relation=$tablePreStr."tag_relation";
		$dbo=new dbex;
		dbplugin('w');
		if(!is_array($tag_data)){
			$tag_array=format_tag($tag_data);
		}else{
			$tag_array=$tag_data;
		}
		foreach($tag_array as $rs){
			if($rs!==''){
				$sql="select id,count from $t_tag where name='$rs'";
				$tag_info=$dbo->getRow($sql);
				if($tag_info['count']<=1){
					$sql="delete from $t_tag where name='$rs'";
					$dbo->exeUpdate($sql);
				}else{
					$sql="update $t_tag set count=count-1 where name='$rs'";
					$dbo->exeUpdate($sql);
				}
				$tag_id=$tag_info['id'];
				$sql="delete from $t_tag_relation where content_id='$resourse_id' and id='$tag_id' and mod_id='$mod_id'";
				$dbo->exeUpdate($sql);
			}
		}
	}
}

//取得标签
function get_tag($table,$cols_name,$cols_id){
	global $dbo;
	$dbo=new dbex;
	dbplugin('r');
	$resoure_info=array();
	$sql="select `tag` from $table where $cols_name=$cols_id";
	$resoure_info=$dbo->getRow($sql);
	if(isset($resoure_info['tag'])!=''){
		return $resoure_info['tag'];
	}else{
		return '';
	}
}

//自动计算标签增减
function auto_tag($new_tag,$old_tag,$resourse_id,$mod_id){
	$new_tag=format_tag($new_tag);
	$old_tag=format_tag($old_tag);
	
	$add_tag=array_diff($new_tag,$old_tag);
	$del_tag=array_diff($old_tag,$new_tag);
	
	if($add_tag){
		tag_add($add_tag,$resourse_id,$mod_id);
	}
	
	if($del_tag){
		tag_del($del_tag,$resourse_id,$mod_id);
	}
}

//格式化标签
function format_tag($tag){
	$tag=preg_replace(array('/\|/','/，/','/\s+/'),',',trim($tag));
	$tag_array=explode(',',$tag);
	return $tag_array;
}
?>