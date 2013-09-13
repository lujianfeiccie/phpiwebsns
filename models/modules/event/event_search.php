<?php
	//引入公共模块
	require("foundation/module_event.php");
	require("api/base_support.php");
	
	//引入语言包
	$ef_langpackage=new event_frontlp;
	
	//缓存功能区
	$event_sort_rs = api_proxy("event_sort_by_self");
	$event_type = event_sort_list($event_sort_rs, '');

?>