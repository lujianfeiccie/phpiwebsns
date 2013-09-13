<?php
//引用插件表前缀
include(dirname(__file__)."/config.php");

//数据表定义
global $ArticleTable;
$ArticleTable=array(
	'article_admin' => $table_prefix.'article_admin',
	'article_group' => $table_prefix.'article_group',
	'article_ads' => $table_prefix.'article_ads',
	'article_channel' => $table_prefix.'article_channel',
	'article_comment' => $table_prefix.'article_comment',
	'article_news' => $table_prefix.'article_news',
	'article_resource' => $table_prefix.'article_resource',
	'article_slide' => $table_prefix.'article_slide',
	'article_tag' => $table_prefix.'article_tag',
	'article_tag_relation' => $table_prefix.'article_tag_relation',
);
?>