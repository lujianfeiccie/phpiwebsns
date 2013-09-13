<?php
	require("foundation/module_users.php");
	require("foundation/module_mypals.php");
	require("api/base_support.php");
		
	//引入语言包
	$ef_langpackage=new event_frontlp;
	$a_langpackage=new albumlp;
	
	//变量取得
	$photo_id = intval(get_argg('photo_id'));
	$event_id=intval(get_argg('event_id'));
	$prev_next = get_argg('prev_next');
	$url_uid= intval(get_argg('user_id'));
	$ses_uid=get_sess_userid();

	//引入模块公共权限过程文件
	$is_login_mode='';
	$is_self_mode='partLimit';
	require("foundation/auser_validate.php");

	//数据显示控制
	$show_data="";
	$show_error="content_none";
	$show_content="content_none";

	//数据表定义区
	$t_event_photo = $tablePreStr."event_photo";
	$t_users=$tablePreStr."users";
	$t_event=$tablePreStr."event";

	$album_info=array();
	$photo_row=array();
	
	$dbo=new dbex;
	dbtarget('r',$dbServs);	
	
	$sql="select title from $t_event where event_id=$event_id";
	$event_info=$dbo->getRow($sql);
	
	$a_who=($is_self=='Y') ? $ef_langpackage->ef_my_albums:str_replace('{holder}',filt_word(get_hodler_name($url_uid)),$ef_langpackage->ef_Holder_album);
		
	if($event_info){
		//查找相册信息
		$event_title=$event_info['title'];
		
		//权限判断
		$status=api_proxy("event_member_by_uid","status",$event_id,$ses_uid);
		$status=intval($status['status']);
		
		if($prev_next){
			$dbo = new dbex;
			//读写分离定义函数
			dbtarget('r',$dbServs);
			$sql = 'SELECT photo_id FROM '.$t_event_photo.' WHERE event_id = '.$event_id.' ORDER BY photo_id DESC';
			$photo_rs = $dbo->getRs($sql);
			$num = count($photo_rs);
			foreach($photo_rs AS $key=>$val)
			{
				if($val['photo_id'] == $photo_id)
				{
					$photo_id = $photo_rs[$prev_next === 'next' ? ($key == ($num - 1) ? 0 : $key + 1) : ($prev_next === 'prev' ? ($key == 0 ? $num - 1 : $key - 1) : 0)]['photo_id'];
					break;
				}
			}
		}
		$sql="select * from $t_event_photo where photo_id=$photo_id";
		$photo_row=$dbo->getRow($sql);
				
		//查找照片信息
		if($photo_row['photo_src']){
			$img_info=getimagesize($photo_row['photo_src']);
		}
		$photo_inf=$photo_row['photo_information'] ? $photo_row['photo_information']:$ef_langpackage->ef_update_photo_info;
	}else{
		$show_data="content_none";
		$show_error="";
	}
?>