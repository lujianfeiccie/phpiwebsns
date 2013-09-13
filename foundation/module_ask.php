<?php

//获取用户回答过的问题的id
function get_reply_askid($dbo,$user_id){
	global $tablePreStr;
	$t_ask_reply=$tablePreStr."ask_reply";

	$sql="select ask_id from $t_ask_reply where user_id=$user_id group by ask_id DESC ";
	$ask_id_rs=$dbo->getALL($sql);
	$ask_id_str='';
	foreach($ask_id_rs as $val){
		if($ask_id_str!=''){
			$ask_id_str.=",";
		}
		$ask_id_str.=$val['ask_id'];
	}
	return $ask_id_str;
}

//ubb上传图片
function ubbImage($ubbStr){
	global $siteDomain;
	global $webRoot;
	$pic_dir='uploadfiles/photo_store/';

	//图片最大宽度
	$imgWithMax='400px';

	preg_match_all('/\[img\]([^\[]*?)\[\/img\]/',$ubbStr,$match);

	if(isset($match[1])){
		foreach($match[1] as $rs){

			//路径设置
			$urlFile=$siteDomain.$pic_dir.$rs;//url路径
			$fileRoot=$webRoot.$pic_dir.$rs;//真正物理路径

			if(!file_exists($fileRoot)){
				continue;
			}
			$imgInfo=getimagesize($fileRoot);
			if(($imgInfo[0] > $imgInfo[1]*4) || ($imgInfo[1] > $imgInfo[0]*4)){
				@unlink($fileRoot);
				continue;
			}
			$width=$imgInfo[0]>400 ? 400:$imgInfo[0];

			$ubbStr=str_replace('[img]'.$rs.'[/img]','<img src="'.$urlFile.'" width="'.$width.'px" />',$ubbStr);
		}
	}
	return $ubbStr;
}
?>