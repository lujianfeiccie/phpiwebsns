<?php
require("foundation/modules_channel.php");
$id=intval(get_argg('id'));
$p_id=intval(get_argp('parentid'));
$old_pid=intval(get_argp('old_pid'));
$old_nodepath=get_argp('old_nodepath');
$outlink=get_argp('outlink_single') ? short_check(get_argp('outlink_single')):short_check(get_argp('outlink'));

//获取子频道id结果集
$child_node_rs=select_spell($ArticleTable['article_channel'],"id","nodepath like '$old_nodepath%'","","","getALL");
if(in_array(array(0=>$p_id,"id"=>$p_id),$child_node_rs)){
	echo 'error:禁止上级频道为此频道本身或其子频道';exit;
}

$type_id=intval(get_argp('type_id'));
if($type_id){
	$_REQUEST['is_show']=0;
	$_REQUEST['is_digg']=0;
}
$node_col_name="";
$update_node="";
if($p_id!=$old_pid){
	$update_node=update_node($p_id);//取得节点的层次
	$node_col_name="nodepath";
	//更新其子节点
	$child_array=array("nodepath"=>"replace(nodepath,'$old_nodepath','$update_node')");
	$is_success1=update_spell($ArticleTable['article_channel'],$child_array,"nodepath like '$old_nodepath%'");
}
//更新本身
$par_array=array($node_col_name,"out_link","is_digg","is_show","parentid","name","order_num","is_menu","type_id","meta_key","meta_descrip","meta_title");
$spell_array=array("'".$update_node."'","'".$outlink."'");
$update_value=request_array($par_array,$spell_array);
$update_array=array_combine($par_array,$update_value);
$is_success2=update_spell($ArticleTable['article_channel'],$update_array,"id=$id");
$is_success3=0;
if($is_success2){
	$name=get_argp('name');
	$update_channel=array("channel_id"=>"$id","channel_name"=>"'$name'");
	$is_success3=update_spell($ArticleTable['article_news'],$update_channel,"channel_id=$id");
}
if($is_success2!==false&&$is_success3!==false){
	header("Location:index.php?app=view&channel&manage");
}else{
	echo 'error:频道更新失败';
}
?>