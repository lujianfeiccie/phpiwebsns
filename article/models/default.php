<?php
//取得推荐文章
$recom_rs=array();
$recom_rs=select_spell($ArticleTable['article_news'],"*","is_recom=1 and status=1","order_num","asc","getRs",1,"art_news/list/all/recom",10);

//取得最新评论
$comment_rs=array();
$comment_rs=select_spell($ArticleTable['article_comment'],"*","",'id','desc',"getRs",1,'art_comment/list/all/new',10);

//取得最新文章
$news_rs=array();
$news_rs=select_spell($ArticleTable['article_news'],"*","status=1","id","desc","getRs",1,"art_news/list/all/new",10);

//展示频道
$show_rs=array();
$show_rs=select_spell($ArticleTable['article_channel'],'*','is_show=1','order_num','asc','getRs',1,'art_channel/list/all/display');

//幻灯片展示
$slide_rs=array();
$slide_rs=select_spell($ArticleTable['article_slide'],'*','','order_num','asc','getRs',1,'art_slide/list/all');
?>