<?php
include(dirname(__file__)."/../includes.php");
include(dirname(__file__)."/table_prefix.php");
$is_check=plugin_check_right(__FILE__,"article_admin");//cms插件的权限检测
$dbo=new dbex();
dbplugin('r');
$user_name=get_sess_username();
$user_id=get_sess_userid();
$admin_row=array();
$cms_pri='';
if($is_check){
	$cms_pri='superadmin';//超级管理员
}else{
	//检查权限
	$t_article_admin=$ArticleTable['article_admin'];
	$sql="select * from $t_article_admin where user_id=$user_id";
	$admin_row=$dbo->getRow($sql);
	if($admin_row){
		$t_article_group=$ArticleTable['article_group'];
		$sql="select rights from $t_article_group where id=$admin_row[gid]";
		$gRow=$dbo->getRow($sql);
		$cms_pri=$gRow['rights'];//普通管理员
	}
}

if($admin_row || $is_check){
	set_session('cms_pri',$cms_pri);
}
?>
<?php if($cms_pri){?>
<li class='app-left'>
  <img src="<?php echo self_url(__FILE__);?>img/article.gif" />
  <a href="article/admin.php" hidefocus="true" target="frame_content">文章管理</a>
</li>
<?php }?>

<li class='app-left'>
  <img src="<?php echo self_url(__FILE__);?>img/article.gif" />
  <a href="article/index.php" hidefocus="true" target="_blank">文章聚合</a>
</li>