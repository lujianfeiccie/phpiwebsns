<?php
require(dirname(__file__)."/../includes.php");
require(dirname(__file__)."/table_prefix.php");
require(dirname(__file__)."/../../article/foundation/ftag.php");
$user_id=get_sess_userid();
$channel_id=get_argp('article_channel_id');
$pri=get_argp('privacy');
$tag=get_argp('tag');

if($channel_id && !$pri){
	//插入数据
	$dbo=new dbex();
	dbplugin('r');
	$sql='SELECT `name` FROM '.$ArticleTable['article_channel'].' WHERE id='.$channel_id;
	$channel_row=$dbo->getRow($sql);
	$channel_name=$channel_row['name'];
	$content=html_filter(get_argp('CONTENT'));
	$title=str_filter(get_argp('blog_title'));
	$user_name=get_sess_username();
	$user_ico=get_sess_userico();
	$origin="来源于".$user_name."的日志";
	$t_article_news=$ArticleTable['article_news'];
	$sql="insert into $t_article_news (user_ico,user_id,channel_name,channel_id,title,keywords,description,user_name,is_digg,origin,updatetime,addtime,content,tag) values ('$user_ico',$user_id,'$channel_name',$channel_id,'$title','$title','$title','$user_name',1,'$origin','".constant('NOWTIME')."','".constant('NOWTIME')."','$content','$tag')";
	if($dbo->exeUpdate($sql)){
		$news_id=mysql_insert_id();
		tag_add_article($tag,$news_id);
	}else{
		echo '<script>alert("投稿失败");history.go(-1);</script>';exit;
	}
}
?>