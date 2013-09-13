<?php
$mod=short_check(get_argg('mod'));
require('../foundation/cupload.class.php');

$config=array(
	"flash" => array(
		"file_type"=>"flv|swf",
	),
	"thumb" => array(
		"set_thumb" => array('100','100'),
		"file_type"=>"jpg|jpeg|png|gif",
	),
	"ads" => array(
		"file_type"=>"jpg|jpeg|png|gif",
	),
	"slide" => array(
		"set_thumb" => array('330','300'),
		"file_type"=>"jpg|jpeg|png|gif",
	),
);

$file_type='jpg|jpeg|png|gif';
if(array_key_exists($mod,$config)){
	$file_type=$config[$mod]['file_type'];
}

$up = new upload($file_type);
$up->set_dir($webRoot.'uploadfiles/cms/','{y}/{m}/{d}');
if(isset($config[$mod]['set_thumb'])){
	$up->set_thumb($config[$mod]['set_thumb'][0],$config[$mod]['set_thumb'][1]);
}
$fs=$up->execute();
$realtxt=$fs[0];

if($realtxt['flag']==1){
	$user_id=get_sess_userid();
	$fileSrcStr=str_replace($webRoot,"",$realtxt['dir']).$realtxt['name'];
	if(isset($config[$mod]['set_thumb'])){
		$thumb_src=str_replace($webRoot,"",$realtxt['dir']).$realtxt['thumb'];
	}
	$fileName=$realtxt['initname'];
	$insert_array=array("file_name" => "'$fileName'","file_src" => "'$fileSrcStr'","user_id" => $user_id,"add_time" => '"'.constant('NOWTIME')."'");
	$t_uploadfile=$tablePreStr."uploadfile";
	$last_id=insert_spell($t_uploadfile,$insert_array);

	switch($mod){
		case "thumb":
		case "slide":
		$do_act="parent.img_priview('../".$thumb_src."');parent.document.getElementById('upload_name').value='$thumb_src';";
		if($fileSrcStr!=$thumb_src) unlink('../'.$fileSrcStr);
		break;

		case "ads":
		$do_act="parent.img_priview('../".$fileSrcStr."');parent.document.getElementById('upload_name').value='$fileSrcStr';";
		break;

		case "flash":
		$do_act="parent.flash_priview('../".$fileSrcStr."');parent.document.getElementById('upload_name').value='$fileSrcStr';";
		break;

		default:
		$do_act="parent.AddContentImg('$fileSrcStr','$last_id');";
		break;
	}
	echo "<script type='text/javascript'>".$do_act." window.location.href='index.php?app=view&upload&upload&mod=$mod';</script>";
}else{
	echo '<script type="text/javascript">alert("上传失败");history.go(-1);</script>';
}
?>