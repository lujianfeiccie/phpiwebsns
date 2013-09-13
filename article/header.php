<?php
/*
 * 注意：此文件由tpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/header.html
 * 如果您的模型要进行修改，请修改 models/header.php
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
if(filemtime("templates/default/header.html") > filemtime(__file__) || (file_exists("models/header.php") && filemtime("models/header.php") > filemtime(__file__)) ) {
	tpl_engine("default","header.html",1);
	include(__file__);
}else {
/* debug模式运行生成代码 结束 */
?><?php
//取得热门标签
$tag_rs=select_spell($ArticleTable['article_tag'],"*","","hot,count",'desc,desc',"getALL",1,'art_tag/list/all/hot',10);

//导航频道
$channel_rs=select_spell($ArticleTable['article_channel'],'*','is_menu=1','order_num','asc','getALL',1,'art_channel/list/all/menu');
?><!--顶部login_bar开始-->
	<div class="login_bar">
		<div class="login_tools">
			<a onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('<?php echo $siteDomain;?>')" href="javascript:">·设为主页</a>
			<a href="http://tech.jooyea.com/php_web/contact/" target="_blank" >·联系我们</a>
			<a href="http://tech.jooyea.com/aboutus.php" target="_blank" >·关于我们</a>
			<a href="<?php echo $siteDomain;?>">·返回SNS</a>
		</div>
	</div>
<!--顶部login_bar结束-->

<!--header头部LOGO开始-->
	<div class="header">
		<span class="top_banner">
		</span>
		<a href="#"><img src="theme/img/logo.gif" alt="iWebCMS产品展示模块" /></a>
		<div class="clear"></div>
	</div>
<!--header头部LOGO结束-->

<!--导航开始-->
	<div class="nav_box">
		<div class="nav_de">
			<ul class="nav">
				<li><a href="index.php?app=view&index">首页</a></li>
				<?php foreach($channel_rs as $val){?>
				<?php echo channel_type_rewrite($val['id'],$val['name'],$val['type_id'],$val['out_link']);?>
				<?php }?>
			</ul>
			<span class="search">
				<form action='index.php?app=view&list&mod=search' method='post'>
				<input type="text" id="text" name="search"  />
				<input type="submit" value="搜&nbsp;&nbsp;索" id="search_btn"  />
				</form>
			</span>
		</div>
	</div>
    </div>
<!--导航结束-->
	
<!--TAG标签开始-->
	<div class="tag_box">
		<ul>
			<li><img src="theme/img/tag_ico.gif" id="tag_ico"  /></li>
			<li><b>热门标签:</b></li>
			<?php foreach($tag_rs as $val){?>
			<li><a href="index.php?app=view&list&mod=tag&tag_id=<?php echo $val['id'];?>"><?php echo $val['name'];?></a></li>
			<?php }?>
		</ul>
		<div class="clear"></div>
	</div>
<!--TAG标签结束--><?php } ?>