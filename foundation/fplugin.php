<?php
function create_plugin($plugin_id,$url="",$name="",$plugin_layout)
{
	$perfix_path="plugins/$name/";
	if(!strpos($plugin_layout,'MENU')){
		echo "<div id='plugin_$plugin_id'>";
	}
	/*,$css="",$js="",$call=""
	if(!is_null($css) && ''!=$css)echo "<link href='$perfix_path$css' rel='stylesheet' type='text/css'>";
	if(!is_null($js)&& ''!=$js) echo "<script type='text/javascript' src='$perfix_path$js'></script>";
	if(!is_null($call)&& ''!=$call) echo "<script type='text/javascript'>$call</script>";
	*/
	if(!is_null($url)&& ''!=$url)
	{
		if("php"==substr(file_ext($url),0,3))
		{
			include_safe("$perfix_path".$url);
		}
		else
		{
			include_once("$perfix_path".$url);
		}
	}
	if(!strpos($plugin_layout,'MENU')){
		echo "</div>";
	}
}
function show_plugins($plugins)
{
	if($plugins)
	{
		foreach($plugins as $rs)
		{
			create_plugin($rs['id'],$rs['url'],$rs['name'],$rs['layout_id']);
		}
	}
}
function file_ext($url)
{
	return preg_replace("/.*\.(\w+){1}$/","$1",$url);
}
function getRootUrl()
{
	return preg_replace("/^\/([^\/]*).*/i","/$1/",$_SERVER["PHP_SELF"]);
}function include_safe($name)
{
	include "$name";
}
function get_user_apps()
{
    global $webRoot;
    require_once($webRoot.'api/plugins/plugins_get.php');
    return plugins_get_mine();
}
?>