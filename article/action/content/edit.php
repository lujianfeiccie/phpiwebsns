<?php
require('foundation/ftag.php');
$id=get_argg('id');
$tag=get_argp('tag');
if(!$id){
	action_return(1,'没有选择文章id',-1);
}

$thumbSrc=get_argp('upload_name');
$update_cols=array("updatetime","thumb","channel_name","channel_id","title","keywords","description","user_name","content","origin","resume","order_num","tag");

//取得频道名字
$channel_id=intval(get_argp('channel_id'));
$old_channel_id=intval(get_argp('old_channel_id'));
$channel_row=select_spell($ArticleTable['article_channel'],'name'," id=$channel_id ",'','','getRow');
$_REQUEST['channel_name']=$channel_row['name'];
$_REQUEST['user_name']=get_argp('user_name') ? get_argp('user_name'):get_sess_username();

//标签修改
$old_tag=get_tag_article($ArticleTable['article_news'],"id",$id);
auto_tag_article($tag,$old_tag,$id);

$update_values=array('"'.constant('NOWTIME').'"',"'$thumbSrc'");//初始化字段数组
$must_array=array("channel_id"=>"频道","channel_name"=>"频道","title"=>"标题","content"=>"内容");//必填参数
$update_values=request_array($update_cols,$update_values,$must_array);//赋值字段数组
$update_array=array_combine($update_cols,$update_values);//链接字段与数据
$is_success=update_spell($ArticleTable['article_news'],$update_array,"id=$id");

$is_success2='';
if($is_success && $channel_id!=$old_channel_id){
	$update_array=array("count"=>"count+1");
	$is_success2=update_spell('isns_cms_channel',$update_array,"id=$channel_id");
	$update_array=array("count"=>"count-1");
	$is_success2=update_spell('isns_cms_channel',$update_array,"id=$old_channel_id");
}

if($is_success2!==false){
	$key_mt='news/list/';
	updateCache($key_mt);
	header("Location:admin.php");
}else{
	action_return(1,'发生错误',-1);
}
?>