<?php
//引入公共文件
require("session_check.php");
require("../api/base_support.php");
require("../foundation/fsafe.php");

//变量区
$dir=array();
$dir=get_argp('dir');
$safe=get_argp('safe');

//扫描的文件信息
defined('FILEINFO') or define('FILEINFO','temp/safe_fileinfo.php');

//扫描的结果信息
defined('RESULTINFO') or define('RESULTINFO','temp/safe_resultinfo.php');

if(empty($dir)){
	echo "<script type='text/javascript'>alert('请选择扫描范围')</script>";exit;
}

//记录临时文件
array_write(constant('FILEINFO'), $dir);

//清空之前扫描的结果数据
array_write(constant('RESULTINFO'), array());

if($safe=='scan'){//开始扫描

	if(!get_argp('filename')){
		echo "<script type='text/javascript'>alert('请选择文件MD5校验镜像')</script>";exit;
	}
	echo "<script type='text/javascript'>parent.start_scan();</script>";exit;

}else if($safe=='md5'){//生成文件md5镜像码
	echo "<script type='text/javascript'>parent.start_make_md5();</script>";exit;
}
?>
