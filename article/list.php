<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/list.html
 * 如果您的模型要进行修改，请修改 models/list.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如有您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
/*
 * 此段代码由debug模式下生成运行，请勿改动！
 * 如果debug模式下出错不能再次自动编译时，请进入后台手动编译！
 */
/* debug模式运行生成代码 开始 */
if(!function_exists("tpl_engine")) {
	require("foundation/ftpl_compile.php");
}
if(filemtime("templates/default/list.html") > filemtime(__file__) || (file_exists("models/list.php") && filemtime("models/list.php") > filemtime(__file__)) ) {
	tpl_engine("default","list.html",1);
	include(__file__);
}else {
/* debug模式运行生成代码 结束 */
?><?php
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
		$result_rs=select_spell($ArticleTable['article_news'],"*","status=1","id","desc","getALL",1,"art_news/list/all/new",20);
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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webTitle;?></title>
<meta name="Description" content="<?php echo $webDesc;?>" />
<meta name="Keywords" content="<?php echo $webKeys;?>" />
<meta name="author" content="<?php echo $webAuthor;?>" />
<meta name="robots" content="all" />
<link rel="stylesheet" type="text/css" href="theme/css/common.css"/>
<link rel="stylesheet" type="text/css" href="theme/css/layout.css"/>
</head>

<body id='cms_content'>
	
<!--head_start!-->
<?php require('header.php');?>
<!--head_end!-->

<div class="site_map">
	当前位置:<a href="index.php?app=view&index">首页</a> &gt; <?php echo $guide_str;?> &gt; 信息列表
</div>
<div class="main_body">
	<?php if($isNull==1){?>
	<div class="error_box">
		<img src="theme/img/error.png"  />指定的页面信息不存在，<a href="javascript:history.back(-1)">点击这里返回</a>。
	</div>
	<?php }?>	
	
	<?php if($isNull==0){?>
	<div class="main_left_list">
	
		<div class="cont">
			<div class="cont_title">
				<?php echo $guide_str;?>
			</div>
			<div class="cont_body">
				<ul>
					<?php foreach($result_rs as $val){?>
					<li class="log_li_1">
						<span class="author"><img src="../<?php echo $val['thumb'] ? $val['thumb']:$val['user_ico'];?>" /></span>
						<span class="log_title">
						<b><a href="index.php?app=view&show&id=<?php echo $val['id'];?>"><?php echo $val['title'];?></a></b><br />
						作者：<a href="../home.php?h=<?php echo $val['user_id'];?>"><?php echo $val['user_name'];?></a>  日期：<?php echo $val['addtime'];?>  
						<font class="bottom_info">阅读[<?php echo $val['hits'];?>] 评论[<?php echo $val['comments'];?>]</font>
						</span>
						<div class="clear"></div>
					</li>
					<?php }?>
					<?php if($mod!='new'){?>
					<li class="log_li_2">
						<?php echo page_show($isNull,$page_num,$page_total);?>
					</li>
					<?php }?>
				</ul>
			</div>
		</div> 
	
	</div>
	
	<div class="main_right" id="log_list">
		
		<?php if($child_rs){?>
		<div class="cont">
			<div class="cont_title">
			子频道
			</div>
			<div class="cont_body news">
				<ul>
					<?php foreach($child_rs as $val){?>
					<li><a href="index.php?app=view&list&id=<?php echo $val['id'];?>"><nobr><?php echo $val['name'];?></nobr></a></li>
					<?php }?>
				</ul>
			</div>
		</div>
		<?php }?>
	
		<div class="cont">
			<div class="cont_title">
			<span><a href="index.php?app=view&list&mod=new">更多..</a></span>
			最新文章
			</div>
			<div class="cont_body news">
				<ul>
					<?php foreach($news_rs as $val){?>
					<li><a href="index.php?app=view&show&id=<?php echo $val['id'];?>"><nobr><?php echo sub_str($val['title'],30);?></nobr></a></li>
					<?php }?>
				</ul>
			</div>
		</div> 
	
		<div class="cont">
			<div class="cont_title">
			最新评论
			</div>
			<div class="cont_body news">
				<ul>
					<?php foreach($comment_rs as $val){?>
					<li><a href="index.php?app=view&show&id=<?php echo $val['content_id'];?>"><nobr><?php echo sub_str($val['content'],30);?></nobr></a></li>
					<?php }?>
				</ul>
			</div>
		</div> 
	
		<div class="cont">
			<div class="cont_title">
				<span><a href="index.php?app=view&list&mod=recom">更多..</a></span>
				推荐文章
			</div>
			<div class="cont_body news">
				<ul>
					<?php foreach($recom_rs as $val){?>
					<li><a href="index.php?app=view&show&id=<?php echo $val['id'];?>"><nobr><?php echo sub_str($val['title'],30);?></nobr></a></li>
					<?php }?>
				</ul>
			</div>
		</div>
		
		<div class="right_banner">
			<a href="#"><img src="images/expo24060.jpg" /></a>
		</div>
		
	</div>
	
	<div class="clear"></div>
	<?php }?>
</div>

<!--head_start!-->
<?php require('footer.php');?>
<!--head_end!-->

</body>
</html>
<?php } ?>