<?php
$src=short_check(get_argg('src'));
$src_del=str_replace("../","",$src);
if($src){
	$t_uploadfile=$tablePreStr."uploadfile";
	del_spell($t_uploadfile,"file_src=$src_del");
	@unlink($src);
	echo 'success';
}else{
	echo '删除失败';
}
?>