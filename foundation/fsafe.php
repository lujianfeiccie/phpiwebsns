<?php
//获取目录下文件夹
function dir_list($path){
	$dir=array();
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($path.$file)) {
					$dir[$file]=$path.$file;
				}
			}
		}
	}
	return $dir;
}

//获取md5文件
function get_md5_file_list(){
	$path='md5_file/';
	$md5_file_list=array();
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (!is_dir($path.$file)) {
					$md5_file_list[]=$file;
				}
			}
		}
	}
	return $md5_file_list;
}

//获取文件后缀名
function get_extend($file_name){
	$extend =explode("." , $file_name);
	$key=count($extend)-1;
	return $extend[$key];
}

//扫描文件类型
function scan_file_type($path,$file_type){
	global $scan_files;
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($path."/".$file)) {
					scan_file_type($path."/".$file,$file_type);
				}
				else {
					$file_extend=get_extend($file);
					if(in_array($file_extend,$file_type)){
						$scan_files[$path."/".$file]=md5_file($path."/".$file);
					}
				}
			}
		}
	}
}

//写入数组文件
function array_write($file, $array){
	if(!is_array($array)) return false;
	$array = "<?php\nreturn ".var_export($array, true).";\n?>";
	$cachefile = $file;
	$strlen = file_put_contents($cachefile, $array);
	@chmod($cachefile, 0777);
	return $strlen;
}

//读取数组文件
function array_read($file){
	$array=include($file);
	return $array;
}

//生成文件md5码存储文件
function file_md5_write($array){
	$data='';
	if(!is_array($array)) return false;
	foreach($array as $key=>$val){
			$data .= $val.' '.$key."\r\n";
	}
	return file_put_contents("md5_file/".date('Y-m-d'), $data);
}

//扫描目录所有文件并生成文件md5码
function scan_file_make_md5($path,$file_type){
	$data="";
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($path."/".$file)) {
					scan_file_type($path."/".$file,$file_type);
				}
				else {
					$file_extend=get_extend($file);
					if(in_array($file_extend,$file_type)){ 
						$key=$path."/".$file;
						$val=md5_file($path."/".$file);
						$data .= $val.' '.$key."\r\n";
					}
				}
			}
		}
	}
	return file_put_contents("../sysadmin/md5_file/".date('Y-m-d'), $data);
}
?>