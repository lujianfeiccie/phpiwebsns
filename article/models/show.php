<?php
require('../foundation/fpages_bar.php');
require('foundation/modules_channel.php');
require('foundation/ftag.php');
$page_num=intval(get_argg('page'));
$content_id=intval(get_argg('id'));
$guide_str='';
$news_row=array();

if($content_id){
	//取得本资源文章
	$news_row=select_spell($ArticleTable['article_news'],"*","id=$content_id and status=1","","","getRow",1,"art_news/show/content_id/$content_id");
	
	//取得本资源的评论
	$comment_row=array();
	$comment_row=select_spell($ArticleTable['article_comment'],"*","content_id=$content_id",'id','desc',"getRs",1,"art_comment/list/content_id/$content_id");	
	if(!empty($news_row)){
		$guide_str=get_parents($news_row['channel_id']);	
	}
	
	//获取标签
	$news_tag_rs=format_tag_article($news_row['tag']);
	
	//增加点击量
	$is_hits=get_session('hits_'.$content_id);
	if(!$is_hits){
		$dbo=new dbex;
		dbtarget('w',$dbServs);
		$t_article_news=$ArticleTable['article_news'];
		$sql="update $t_article_news set hits=hits+1 where id=$content_id";
		$dbo->exeUpdate($sql);
		set_session('hits_'.$content_id,1);
	}
}
$channel_id=$news_row['channel_id'];



//取得最新文章
$news_rs=array();
$news_rs=select_spell($ArticleTable['article_news'],"*","status=1","id","desc","getALL",1,"art_news/list/all/new",10);

//取得最新评论
$comment_rs=array();
$comment_rs=select_spell($ArticleTable['article_comment'],"*","",'id','desc',"getALL",1,'art_comment/list/all/new',10);

//取得推荐文章
$recom_rs=array();
$recom_rs=select_spell($ArticleTable['article_news'],"*","is_recom=1 and status=1","order_num","asc","getALL",1,"art_news/list/all/recom",10);

$isNull=0;
if(empty($comment_row)){
	$isNull=1;
}
?>