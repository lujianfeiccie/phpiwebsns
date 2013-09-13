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
  <style type="text/css">.tabs li a{padding:6px 13px 0;_width:48px;_padding:7px 9px 0 13px;}</style>
  <link rel="stylesheet" href="theme/css/admin.css" />
</head>
<body id="iframecontent" style='margin-left:20px'>
    <h2><img src='theme/img/logo.gif' /></h2>
    <div class="tabs">
      <ul class="menu">
        <li class='active'><a href='index.php?app=view&system&manage' name='ajax' hidefocus="true" target='cms_content'>系统设置</a></li>
        <li><a href='index.php?app=view&ads&manage' name='ajax' hidefocus="true" target='cms_content'>广告管理</a></li>
        <li><a href='index.php?app=view&slide&manage' name='ajax' hidefocus="true" target='cms_content'>幻灯管理</a></li>
        <li><a href='index.php?app=view&tag&manage' name='ajax' hidefocus="true" target='cms_content'>标签管理</a></li>
      </ul>
    </div>
    <div id='cms_content'></div>
    <script type='text/javascript' src='../servtools/ajax_client/auto_ajax.js'></script>
		<script type='text/javascript'>
			var def_ajax=new Ajax();
			def_ajax.getInfo("index.php?app=view&system&manage","GET","app","",function(c){act_result(c);});
		</script>
</body>
</html>