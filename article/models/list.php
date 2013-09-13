<?php
require('../foundation/fpages_bar.php');
require('foundation/modules_channel.php');
$page_num=intval(get_argg('page'));
$mod=get_argg('mod');
$id=intval(get_argg('id'));
$result_rs=array();
$child_rs=array();
$guide_str='';
if($id){
	//取得频道列表信息
	$list_rs=array();
	$result_rs=select_spell($ArticleTable['article_news'],"*","channel_id=$id and status=1",'id','desc',"getRs",1,"art_news/show/channel_id/$id");
	$child_rs=select_spell($ArticleTable['article_channel'],"*","parentid=$id",'nodepath','',"getALL",1,"art_channel/list/parentid/$id");
	$guide_row =select_spell($ArticleTable['article_channel'],"*","id=$id ",'','',"getRow",1,"");
	$guide_str=get_parents($id);
}

	switch($mod){

		case "search":
		$search_str=get_argp('search');
		$result_rs=select_spell($ArticleTable['article_news'],"*","status=1 and title like '%$search_str%'","order_num","asc","getRs");
		$guide_str='搜索文章';
		break;

		case "recom":
		//取得推荐文章
		$result_rs=select_spell($ArticleTable['article_news'],"*","is_recom=1 and status=1","order_num","asc","getRs",1,"art_news/list/recom");
		$guide_str='推荐文章';
		break;

		case "new":
		//取得最新文章
		$result_rs=select_spell($ArticleTable['article_news'],"*","status=1","id","desc","getALL",1,"art_news/list/all/new",30);
		$guide_str='最新文章';
		break;

		case "tag":
		//取得标签文章
		$dbo=new dbex;
		dbplugin('r');
		$tag_id=intval(get_argg('tag_id'));
		$tag_name=urldecode(get_argg('tag_name'));

		$t_tag=$ArticleTable['article_tag'];
		$t_article=$ArticleTable['article_news'];
		$t_tag_relative=$ArticleTable['article_tag_relation'];

		if($tag_id){
			$sql="select name from $t_tag where id=$tag_id";
			$tag=$dbo->getRow($sql);
			$tag_name=$tag['name'];
		}
		else if($tag_name){
			$sql="select id from $t_tag where name='$tag_name'";
			$tag=$dbo->getRow($sql);
			$tag_id=intval($tag['id']);
		}

		$sql="select a.* from $t_article as a join $t_tag_relative as t on(a.id=t.content_id) where t.id=$tag_id and a.status=1";
		$dbo->setPages(20,$page_num);
		$result_rs=$dbo->getRs($sql);
		$page_total=$dbo->totalPage;
		$guide_str=$tag_name.' 标签 ';
		break;
	}

$isNull=0;
if(empty($result_rs)){
	$isNull=1;
}

//取得最新文章
$news_rs=array();
$news_rs=select_spell($ArticleTable['article_news'],"*","status=1","id","desc","getALL",1,"art_news/list/all/new",10);

//取得最新评论
$comment_rs=array();
$comment_rs=select_spell($ArticleTable['article_comment'],"*","",'id','desc',"getALL",1,'art_comment/list/all/new',10);

//取得推荐文章
$recom_rs=array();
$recom_rs=select_spell($ArticleTable['article_news'],"*","is_recom=1 and status=1","order_num","asc","getALL",1,"art_news/list/all/recom",10);

?>