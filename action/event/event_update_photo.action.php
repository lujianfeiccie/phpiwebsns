<?php
	//引入语言包
	$ea_langpackage=new event_actionlp;

	//变量取得
	$event_id=intval(get_argg('id'));
	$photo_id=array();
 	$photo_information=array();
 	$photo_id=get_argp('photo_id');
 	$photo_information=get_argp('photo_information');
 	$photo_name=get_argp('photo_name');
	$user_id=get_sess_userid();

	//变量定义区
	$t_event = $tablePreStr."event";
	$t_event_photo = $tablePreStr."event_photo";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);

	//添加图片信息
  foreach($photo_id as $id){
  	$information=each($photo_information);
  	$name=each($photo_name);
  	$id = intval($id);
  	$information = short_check($information['value']);
  	$name = short_check($name['value']);
  	$sql = "update $t_event_photo set photo_information = '$information',photo_name='$name' where photo_id=$id";
		$dbo -> exeUpdate($sql);
	}
		
	//回应信息
	action_return(1,"","modules.php?app=event_list_photo&event_id=".$event_id);
?>
