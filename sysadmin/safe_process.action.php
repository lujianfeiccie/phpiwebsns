<?php
//引入公共文件
require("session_check.php");
require("../foundation/fsqlseletiem_set.php");
require("../api/base_support.php");
require("../foundation/fsafe.php");

//设置超时时间
@set_time_limit(3600);

//扫描的文件信息
defined('FILEINFO') or define('FILEINFO','temp/safe_fileinfo.php');

//扫描的结果信息
defined('RESULTINFO') or define('RESULTINFO','temp/safe_resultinfo.php');

//变量区
$action=short_check(get_argp('action'));
$scan_files=array();

//各个功能
switch($action){
	//检索文件类型
	case "file_type":
		$file_type=short_check(get_argp('file_type'));
		$file_type = explode('|', $file_type);

		//获取选取的文件夹数组
		$dir=array_read(constant('FILEINFO'));
		foreach ($dir as $val){
			scan_file_type($val,$file_type);
		}

		if(get_argp('make_md5')){//是生成文件镜像，否扫描
			file_md5_write($scan_files);
		}else{
			array_write(constant('FILEINFO'), $scan_files);
		}
		unset($scan_files);
		echo 1;
	break;

	//检索文件md5码
	case 'file_md5':
		$filename=short_check(get_argp('filename'));
		$scan_files=array_read(constant('FILEINFO'));
		$md5_files = file("md5_file/".$filename);

		foreach($md5_files as $row){
			$row = trim($row);
			$val = substr($row, 0, 32);
			$key = substr($row, 33);
			if(isset($scan_files[$key])){
				if($scan_files[$key] == $val)
				unset($scan_files[$key]);
			}
		}
		array_write(constant('FILEINFO'), $scan_files);
		echo 1;
	break;

	//扫描特征函数
	case "func":
		$func=short_check(get_argp('func'));
		if($func){
			$scan_files=array_read(constant('FILEINFO'));
			$scan_result=array();

			foreach ($scan_files as $key=>$val){
				$state='';
				$html = file_get_contents($key);
				if(stristr($key,'.php.') != false || preg_match_all('/[^a-z]?('.$func.')\s*\(/i', $html, $state, PREG_SET_ORDER)){
					$scan_result[$key]['func']=$state;
				}
			}
			array_write(constant('RESULTINFO'), $scan_result);
		}
		echo 1;
	break;

	//扫描特征代码
	case "code":
		$code=short_check(get_argp('code'));
		if($code){
			$scan_files=array_read(constant('FILEINFO'));
			$scan_result=array_read(constant('RESULTINFO'));

			foreach ($scan_files as $key=>$val){
				$state='';
				$html = file_get_contents($key);
				if(stristr($key,'.php.') != false || preg_match_all('/[^a-z]?('.$code.')/i', $html, $state, PREG_SET_ORDER)){
					$scan_result[$key]['code']=$state;
				}
			}
			array_write(constant('RESULTINFO'),$scan_result);
		}
		echo 1;
	break;
}

?>
