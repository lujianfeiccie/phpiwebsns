<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/show.html
 * 如果您的模型要进行修改，请修改 models/show.php
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
if(filemtime("templates/default/show.html") > filemtime(__file__) || (file_exists("models/show.php") && filemtime("models/show.php") > filemtime(__file__)) ) {
	tpl_engine("default","show.html",1);
	include(__file__);
}else {
/* debug模式运行生成代码 结束 */
?><?php
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
?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $news_row['title'];?>-<?php echo $webTitle;?></title>
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript' src='../servtools/imgfix.js'></script>
<link rel="stylesheet" type="text/css" href="theme/css/common.css"/>
<link rel="stylesheet" type="text/css" href="theme/css/layout.css"/>
<meta name="Description" content="<?php echo $news_row['description'];?>" />
<meta name="Keywords" content="<?php echo $news_row['keywords'];?>" />
<meta name="author" content="<?php echo $news_row['user_name'];?>" />
<meta name="robots" content="all" />
<script type='text/javascript'>
	function support(content){
		if(content=='success'){
			var obj_value=document.getElementById('sup_num').innerHTML;
			document.getElementById('sup_num').innerHTML=parseInt(obj_value)+1;
		}else{
			alert(content);
		}
	}
	
	function against(content){
		if(content=='success'){
			var obj_value=document.getElementById('aga_num').innerHTML;
			document.getElementById('aga_num').innerHTML=parseInt(obj_value)+1;
		}else{
			alert(content);
		}
	}
	
	function check_form(){
		var user_name=document.getElementById('user_name').value;
		var user_email=document.getElementById('user_email').value;
		var text_cont=document.getElementById('text_cont').value;
		if(user_name==''){
			alert('昵称不能为空');return false;
		}
		if(user_email==''){
			alert('邮箱不能为空');return false;
		}
		if(text_cont==''){
			alert('内容不能为空');return false;
		}
	}
</script>
</head>
<body>

<!--head_start!-->
<?php require('header.php');?>
<!--head_end!-->

<div class="site_map">
	当前位置:<a href="index.php?app=view&index">首页</a> &gt; <?php echo $guide_str;?> &gt; 文章内容
</div>

<div class="main_body">
	<?php if(empty($news_row)){?>
	<div class="error_box">
		<img src="theme/img/error.png"  />指定的页面信息不存在，<a href="javascript:history.back(-1)">点击这里返回</a>。
	</div>
	<?php }?>
	
	<?php if($news_row){?>
	<div class="main_left_list">
		<div class="cont">
			<div class="cont_title">
				<?php echo $news_row['channel_name'];?>
			</div>
			<div class="cont_body article">
				<div class="article_top">
				<h3><?php echo $news_row['title'];?></h3>
				作者：<?php echo $news_row['user_name'];?> 日期：<?php echo $news_row['addtime'];?>  阅读[<?php echo $news_row['hits'];?>] 评论[<?php echo $news_row['comments'];?>]
				</div>
            <!-- tags start -->
            <div class="tags">
                <span>标签：</span>
				<?php foreach($news_tag_rs as $val){?>
					<a href="index.php?app=view&list&mod=tag&tag_name=<?php echo urlencode($val);?>"><?php echo $val;?></a>
				<?php }?>
            </div>
            <!-- tags end -->
				<div id="textcontent">
				<?php echo $news_row['content'];?>
				</div>
			<p>（来源：<?php echo $news_row['origin'];?>）</p>
            
			
			<?php if($diggType!=2){?>
			<div class="digg">
				<table border="0" width="100%">
					<tr class="number">
						<td><span id='sup_num'><?php echo $news_row['support'];?></span></td>
						<td><span id='aga_num'><?php echo $news_row['against'];?></span></td>
					</tr>
					<tr>
						<td class="sup"><a href="index.php?app=act&content&attach&mod=support&id=<?php echo $news_row['id'];?>" name="ajax" target="support">支持</a></td>
						<td class="opp"><a href="index.php?app=act&content&attach&mod=against&id=<?php echo $news_row['id'];?>" name="ajax" target="against">反对</a></td>
					</tr>
				</table>
			</div>
			<?php }?>
			</div>
		</div>
		<?php if($comment_row){?>
		<div class="cont" style="margin-top:10px;">
			<div class="cont_title" id="com" name="com">
				<span>共有<?php echo $news_row['comments'];?>位网友发表了评论！</span>
				网友评论
			</div>
			<div class="cont_body" id="comment">
				<ul>
					<?php foreach($comment_row as $val){?>
					<li>
						<span>IP：<?php echo $val['user_ip'];?></span>
						<strong style="color:#900;"><?php echo $val['user_name'];?></strong>&nbsp; 发表于：<?php echo $val['addtime'];?><br/>
						<?php echo $val['content'];?>
					</li>
					<?php }?>
					<li><?php echo page_show($isNull,$page_num,$page_total);?></li>
				</ul>
			</div>
		</div>
		<?php }?>
		<?php if($comType!=2){?>
		<div class="cont" style="margin-top:10px;">
			<div class="cont_title">我要评论</div>
			<div class="cont_body">
				<form action='index.php?app=act&comment&add&content_id=<?php echo $news_row['id'];?>' method='post' onsubmit="return check_form();">
				<table width="100%" border="0" class="comm_input">
					<tr>
							<td width="50px" align="center">昵称：</td>
							<td><input type="text" class="comm_text" name="user_name" id="user_name" /><span style="font-size:12px">*请输入您的昵称。</span></td>
					</tr>
					<tr>
						<td width="50px" align="center">邮箱：</td>
						<td><input type="text" class="comm_text" name="user_email" id="user_email" /><span style="font-size:12px">*请输入您的邮箱地址。</span></td>
					</tr>
					<tr>
						<td></td>
						<td><textarea id="text_cont" name="content"></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="提交评论" id="submit_btn"  /><span style="font-size:12px">网友评论仅代表网友本人的看法，并不表明本站同意同意其观点或证实其描述！</span></td>
					</tr>
				</table>
				</form>
			</div> 
		</div>
		<?php }?>

	</div>
	
	<div class="main_right" id="log_list">
    	<!-- 相关文章 start -->
		<div class="cont">
			<div class="cont_title">
			<span><a href="index.php?app=view&list&mod=new">更多..</a></span>
			相关文章
			</div>
			<div class="cont_body news">
				<ul>
					<?php foreach($news_rs as $val){?>
					<li><a href="index.php?app=view&show&id=<?php echo $val['id'];?>"><nobr><?php echo sub_str($val['title'],30);?></nobr></a></li>
					<?php }?>
				</ul>
			</div>
		</div>
    	<!-- 相关文章 end -->
	
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
	
		<!--右侧广告-->
		<div class="right_banner">
			
		</div>
		<!--右侧广告-->
	
	</div>

	<div class="clear"></div>
	<?php }?>
</div>
<script type='text/javascript' src='../servtools/ajax_client/auto_ajax.js'></script>
<script type='text/javascript'>
	if(<?php echo $page_num;?>){
		window.scrollTo(0,window.document.body.scrollHeight);
	}
</script>
<!--head_start!-->
<?php require('footer.php');?>
<!--head_end!-->

</body>
</html><?php } ?>