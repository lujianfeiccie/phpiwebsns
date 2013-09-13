<?php
/**
* @copyright[Iweb SNS] (C)2009-2099 jooyea.net.
* @category foundation
* @package fcookie.php
* @author chendeshan 2010-11-18 17:27:21
* @version 1.5
*/

//根据key值获取$_COOKIE值
function getCookie($key){
	return isset($_COOKIE[$key]) ? $_COOKIE[$key]:NULL;
}

/*
	设置cookie值
	$key:cookie名字;
	$value:cookie值;
	$time:保存cookie的小时数;*/
function set_cookie($key,$value,$time=0){
	if($time=intval($time)){
		$time=time()+60*60*$time;
	}
	setcookie($key,$value,$time);
}

?>