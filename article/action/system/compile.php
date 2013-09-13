<?php
require("../foundation/fchange_exp.php");
require('../foundation/ftpl_compile.php');
$compType=get_argg('compType');
$config_url='config/config.php';
$content=file_get_contents($config_url);
$content=change_exp($content);
$f_ref=fopen($config_url,'w+');
$is_success=fwrite($f_ref,$content);
fclose($f_ref);
$ref_dir=opendir('templates/'.$tplName);
while($tpl_name=readdir($ref_dir)){
	tpl_engine($tplName,$tpl_name,1,$compType);
}
echo '编译完成';
?>