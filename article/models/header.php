<?php
//取得热门标签
$tag_rs=select_spell($ArticleTable['article_tag'],"*","","hot,count",'desc,desc',"getALL",1,'art_tag/list/all/hot',10);

//导航频道
$channel_rs=select_spell($ArticleTable['article_channel'],'*','is_menu=1','order_num','asc','getALL',1,'art_channel/list/all/menu');
?>