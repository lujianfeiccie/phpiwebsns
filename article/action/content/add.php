<?php
require('foundation/ftag.php');
$tag=short_check(get_argp('tag'));
$user_id=get_sess_userid();
$cms_pri=get_session('cms_pri');
$user_ico=get_sess_userico();
$status=$cms_pri=='superadmin' ? 1:0;
$thumbSrc=get_argp('upload_name');
$channel_id=get_argp('channel_id');
if (empty($channel_id)){
	action_return(1,'channel_id 不能 为空',-1);
}
$channel_row=select_spell($ArticleTable['article_channel'],'name'," id=$channel_id ",'','','getRow');
$_REQUEST['channel_name']=$channel_row['name'];
$_REQUEST['user_name']=get_argp('user_name') ? get_argp('user_name'):get_sess_username();

//插入数据
$insert_cols=array("thumb","user_ico","status","addtime","updatetime","user_id","channel_id","title","keywords","description","user_name","content","origin","resume","order_num","tag","channel_name");
$insert_values=array("'$thumbSrc'","'$user_ico'",$status,'"'.constant('NOWTIME').'"','"'.constant('NOWTIME').'"',$user_id);//初始化字段数组
$must_array=array("channel_id"=>"频道","channel_name"=>"频道","title"=>"标题","content"=>"内容");//必填参数
$insert_values=request_array($insert_cols,$insert_values,$must_array);//赋值字段数组

$channel_insert_cols=array_combine($insert_cols,$insert_values);//链接字段与数据

$is_success=insert_spell($ArticleTable['article_news'],$channel_insert_cols);//执行插入操作,并且返回新节点的id值
$is_success2='';
if($is_success){
	$news_id=$is_success;
	//标签功能
	tag_add_article($tag,$news_id);
	$id=get_argp('channel_id');
	$update_array=array("count"=>"count+1");
	$is_success2=update_spell($ArticleTable['article_channel'],$update_array,"id=$id");
}

if($is_success2){
	$key_mt='news/list/all/new/10/';
	updateCache($key_mt);
	header("Location:admin.php");
}else{
	action_return(1,'错误',-1);
}
?>