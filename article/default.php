<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/default.html
 * 如果您的模型要进行修改，请修改 models/default.php
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
if(filemtime("templates/default/default.html") > filemtime(__file__) || (file_exists("models/default.php") && filemtime("models/default.php") > filemtime(__file__)) ) {
	tpl_engine("default","default.html",1);
	include(__file__);
}else {
/* debug模式运行生成代码 结束 */
?><?php
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
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<script src="theme/js/slide.js" type="text/javascript"></script>
</head>

<body>
<!--head_start!-->
<?php require('header.php');?>
<!--head_end!-->

<div class="main_body">
	<div class="main_left">
		<div class="slide_container" id="idTransformView" style="width:330px; margin-right:13px; float:left;">
			  <ul class="slider" id="idSlider">
				<?php if(!empty($slide_rs)){?>
				<?php foreach($slide_rs as $val){?>
				<li><a href="<?php echo $val['link'];?>" target="_blank"><img src="../<?php echo $val['photo_src'];?>" alt="<?php echo $val['title'];?>" /></a></li>
				<?php }?>
				<?php }?>
				<?php if(empty($slide_rs)){?>
				<?php $slide_rs=array(0);?>
				<li><a href="#"><img src="theme/img/def.jpg" alt="聚易" /></a></li>
				<?php }?>
			 </ul>
			 <ul class="slide_num" id="idNum">
				 <?php foreach($slide_rs as $key => $val){?>
				 <li><?php echo intval($key+1);?></li>
				 <?php }?>
			 </ul>
		</div>
	  	<script type="text/javascript">
			window.onload=slide(280);
		</script>
			<!--首页头条开始-->
		<div class="top_list" style=" width:335px; overflow:hidden; float:left;">
			<div class="cont_title" id="rec">
				<span><a href="index.php?app=view&list&mod=recom">更多..</a></span>
				今日推荐
			</div>
			<?php $index_recom_num=0;?>
			<?php foreach($recom_rs as $val){?>
			<?php if($index_recom_num>=2){?>
			<?php break;?>
			<?php }?>
			<div class="rec_list">
				<h3><a href="index.php?app=view&show&id=<?php echo $val['id'];?>"><nobr><?php echo sub_str($val['title'],30);?></nobr></a></h3>
				<p><?php echo $val['resume'];?></p>
				<a class="read_more" href="index.php?app=view&show&id=<?php echo $val['id'];?>">阅读全文..</a>
			</div>
			<hr />
			<?php $index_recom_num++;?>
			<?php }?>
			<!--首页头条结束-->
     </div>
     <div class="clear"></div>
     </div>

		<div class="main_right">
			<!--最新文章开始-->
			<div class="cont">
				<div class="cont_title">
				<span><a href="index.php?app=view&list&mod=new">更多..</a></span>
				最新文章
				</div>
				<div class="cont_body" id="new_log">
					<ul>
						<?php foreach($news_rs as $val){?>
						<li><a href="index.php?app=view&show&id=<?php echo $val['id'];?>"><nobr><?php echo sub_str($val['title'],20);?></nobr></a></li>
						<?php }?>
					</ul>
					<!--广告!-->
					<a href="#"><img src="theme/img/banner_03.gif" width="235px" /></a>
				</div>
			</div>
			<!--最新文章结束-->
		</div>

		<div class="clear"></div><!--清除幻灯片、首页头条、最新文章的浮动-->

		</div>


	<!--通栏广告 开始-->
	<div class="main_body">
		<a href="#"><img src="theme/img/bannera01.gif" width="960px"  height="90px" /></a>
	</div>
	<!--通栏广告 结束-->

	<div class="main_body">
    <div class="main_left" style="width:700px; float:left;">

		<?php foreach($show_rs as $val){?>
		<?php $show_top_content=select_spell($ArticleTable['article_news'],'id,title,user_id,user_ico,thumb,hits,comments,user_name',"channel_id = $val[id] and status=1",'order_num','asc','getRow',1,"news/show/index/content_id/$val[id]",1);?>
		<?php $show_content=select_spell($ArticleTable['article_news'],'id,title',"channel_id = $val[id] and status=1",'order_num','asc','getRs',1,"news/list/all/index/content_id/$val[id]",'1,5');?>

			<div class="cont">

				<div class="cont_title">
					<span><a href="index.php?app=view&list&id=<?php echo $val['id'];?>">更多..</a></span>
					<?php echo sub_str($val['name'],30);?>
				</div>

				<div class="cont_body">

					<!--栏内顶部分类头条和作者头像 开始-->
					<?php if($show_top_content){?>
					<div class="log_list_top">
						<table border="0" width="100%">
							<tr>
								<td width="30%" align="center">
									<span class="list_top_pic">
										<a href="index.php?app=view&show&id=<?php echo $show_top_content['id'];?>"><img src="../<?php echo $show_top_content['thumb'] ? $show_top_content['thumb']:$show_top_content['user_ico'];?>" /></a>
									</span>
								</td>
								<td valign="top">
									<h5><a href="index.php?app=view&show&id=<?php echo $show_top_content['id'];?>"><?php echo sub_str($show_top_content['title'],30);?></a></h5>
									<span>
										作者：<a href="../home.php?h=<?php echo $show_top_content['user_id'];?>"><?php echo $show_top_content['user_name'];?></a>
										阅读[<?php echo $show_top_content['hits'];?>]
										回复[<?php echo $show_top_content['comments'];?>]
									</span>
								</td>
							</tr>
						</table>
					</div>
					<!--栏内顶部分类头条和作者头像 结束-->
					<?php }?>
					<ul class="log_list_1">
						<?php foreach($show_content as $val){?>
						<li><a href="index.php?app=view&show&id=<?php echo $val['id'];?>"><nobr>·<?php echo sub_str($val['title'],30);?></nobr></a></li>
						<?php }?>
					</ul>

				</div>

			</div>

		<?php }?>
        </div>

	<div class="main_right" style="width:260px;float:right;">

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

	</div>

	<div class="clear"></div>

	</div>

	<script type='text/javascript' src='../servtools/ajax_client/auto_ajax.js'></script>

<!--head_start!-->
<?php require('footer.php');?>
<!--head_end!-->

</body>
</html>
<?php } ?>