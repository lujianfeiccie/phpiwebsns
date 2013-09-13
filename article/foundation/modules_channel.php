<?php
//更新频道节点
function update_node($parentid){
	global $ArticleTable;
	$t_article_channel=$ArticleTable['article_channel'];
	if($parentid){//添加非根节点
		$parent_row=select_spell($t_article_channel,"nodepath","id=$parentid","","","getRow");//取得父节点的层次深度
		$parent_node=$parent_row['nodepath'];
		$node_num=strlen($parent_node)+3;
		$child_max_row=select_spell($t_article_channel,"max(nodepath)","nodepath like '$parent_node%' and length(nodepath)=$node_num","","","getRow");//取得父节点中最大的nodepath
		if($child_max_row[0]){
			$update_node=pad_str($child_max_row[0]);//层级修正
		}else{
			$update_node=$parent_node.'001';//同类节点第一个时
		}
	}else{//添加根节点
		$child_max_row=select_spell($t_article_channel,"max(nodepath)","length(nodepath)=3","","","getRow");
		if($child_max_row[0]){
			$update_node=pad_str($child_max_row[0]);//层级修正
		}else{
			$update_node="001";
		}
	}
	return $update_node;
}

//取得类别节点缩进
function get_channel_tip($nodepath){
	$nodenum = (strlen($nodepath)/3)-1;
	$node = "";
	for($i=0;$i<$nodenum;$i++){
		$node .='&nbsp;&nbsp;';
	}
	return $node;
}

//频道层级修正
function pad_str($bit_num){
	$update_node=$bit_num+1;
	$pad_num=strlen($bit_num);
	return str_pad($update_node,$pad_num,'0',STR_PAD_LEFT);
}

//取得频道的所有父节点
function get_parents($channel_id){
	global $ArticleTable;
	global $guide_str;
	$t_article_channel=$ArticleTable['article_channel'];
	$channel=select_spell($t_article_channel,'name,parentid',"id=$channel_id",'','','getRow');
	if($guide_str!=''){
		$guide_str=' &gt; '.$guide_str;
	}
	$guide_str="<a href='index.php?app=view&list&mod=channel&id=$channel_id'>".$channel['name']."</a>".$guide_str;

	if($channel['parentid']!=0){
		get_parents($channel['parentid']);
	}
	return $guide_str;
}

//由id得到name的值
function get_channel_name($channel_id){
	global $ArticleTable;
	$t_article_channel=$ArticleTable['article_channel'];
	$channel_row=select_spell($t_article_channel,'*',"id=$channel_id",'','','getRow');
	if(!empty($channel_row)){
		return $channel_row['name'];
	}else{
		return false;
	}
}
//的到parende的id值
function get_paren_id($channel_id){
	global $ArticleTable;
	$t_article_channel=$ArticleTable['article_channel'];	
	$dbo=new dbex;
	dbplugin('r');
	$sql="select * from $t_article_channel where id=$channel_id";
	$channel_row=$dbo->getRow($sql);
	if(!empty($channel_row)){
		return $channel_row['parentid'];
	}else{
		return false;
	}
}

?>