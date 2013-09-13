<?php
/**
 * KindEditor PHP
 *
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 *
 */
require_once dirname(__FILE__).'/../../../configuration.php';

//文件上传函数
require_once($webRoot."foundation/cupload.class.php");
require_once 'JSON.php';

//配置信息
$fieldName='imgFile';

//实例化上传类
$up = new upload();

//设置上传控件名
$up->set_field($fieldName);

//设置缩略图
$up->set_thumb(500,500);

//设置目录
$up->set_dir($webRoot.'uploadfiles/photo_store/','{y}/{m}/{d}');

//开始上传
$fs=$up->single_exe();

if(isset($fs['thumb'])){
	//生成缩略图后删除原图
	$fileName=$fs['thumb'];
	@unlink($fs['dir'].$fs['name']);
}else{
	$fileName=$fs['name'];
}

$file_url=str_replace($webRoot,$siteDomain,$fs['dir']).$fileName;

header('Content-type: text/html; charset=UTF-8');
$json = new Services_JSON();
echo $json->encode(array('error' => 0, 'url' => $file_url));
exit;

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}

function set_dir($basedir,$filedir = '') {
  $dir = $basedir;
  if(!file_exists($dir)){
  	mkdir($dir,0777);
  }

  if(!empty($filedir)){
      $filedir = str_replace(array('{y}','{m}','{d}'),array(date('Y',time()),date('m',time()),date('d',time())),strtolower($filedir));
      $dirs = explode('/',$filedir);
      foreach ($dirs as $d){
        !empty($d) && $dir .= $d.'/';
        !is_dir($dir) && @mkdir($dir,0777);
      }
  }
  return $dir;
}
?>