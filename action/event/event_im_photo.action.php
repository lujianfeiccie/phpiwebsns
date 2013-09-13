<?php
	//引入语言包
	$ea_langpackage=new event_actionlp;

	//变量取得
	$photo_id = short_check(get_args('photo_id'));
	$information=short_check(get_argp('information_value'));
	$user_id=get_sess_userid();

	//数据表定义区
	$t_event_photo = $tablePreStr."event_photo";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//更改图片信息
	$sql = "update $t_event_photo set photo_information='$information' where photo_id=$photo_id";
	$dbo->exeUpdate($sql);
	echo filt_word($information);
?>
