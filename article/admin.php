<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title></title>
  <script type='text/javascript' src='../skin/default/js/jy.js'></script>
  <script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
  <script type='text/javascript' src='theme/js/cms.js'></script>
  <script type='text/javascript' src='../servtools/imgfix.js'></script>
  <script type='text/javascript' src='../servtools/kindeditor/kindeditor.js'></script>
  <script type='text/javascript' src='../servtools/calendar.js'></script>
  <script type='text/javascript'>parent.hiddenDiv();</script>
  <link rel="stylesheet" href="theme/css/admin.css" />
</head>
<body id="iframecontent">
    <h2><img src='theme/img/logo.gif' /></h2>
    <div class="tabs">
      <ul class="menu">
      	<li class='active'><a href='index.php?app=view&content&manage' name='ajax' hidefocus="true" target='cms_content'>文章管理</a></li>
        <li><a href='index.php?app=view&content&edit' name='ajax' hidefocus="true" target='act_result'>发布文章</a></li>
        <li><a href='index.php?app=view&channel&manage' name='ajax' hidefocus="true" target='act_result'>频道管理</a></li>
        <li><a href='index.php?app=view&comment&manage' name='ajax' hidefocus="true" target='cms_content'>评论管理</a></li>
        <li><a href='index.php?app=view&privacy&group' name='ajax' hidefocus="true" target='cms_content'>权限管理</a></li>
      </ul>
    </div>
    <div id='cms_content'></div>
    <script type='text/javascript' src='../servtools/ajax_client/auto_ajax.js'></script>
		<script type='text/javascript'>
			var def_ajax=new Ajax();
			def_ajax.getInfo(" index.php?app=view&content&manage","GET","app","",function(c){act_result(c);});
		</script>
</body>
</html>