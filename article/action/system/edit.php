<?php
require("../foundation/fchange_exp.php");
$config_url='config/config.php';
$content=file_get_contents($config_url);
$content=change_exp($content);
$f_ref=fopen($config_url,'w+');
$is_success=fwrite($f_ref,$content);
fclose($f_ref);
if($is_success){
	header("Location:index.php?app=view&system&manage");
}else{
	echo 'error:修改错误';
}
?>