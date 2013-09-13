<?php
	require("foundation/module_users.php");

	//引入语言包
	$u_langpackage=new userslp;

	//变量获得
	$user_id =get_sess_userid();
	$model = short_check(get_argg('model'));
	$birth_year = short_check(get_argp('birth_year'));
	$birth_month = short_check(get_argp('birth_month'));
	$birth_day = short_check(get_argp('birth_day'));
	$reside_city = short_check(get_argp('reside_city'));
	$reside_province = short_check(get_argp('reside_province'));
	$birth_city = short_check(get_argp('birth_city'));
	$birth_province = short_check(get_argp('birth_province'));
	$is_finish=intval(get_argg('is_finish'));
	$info = get_argp('info');
	
	//表声明区
	$t_users = $tablePreStr."users";
	$t_online=$tablePreStr."online";
	$t_user_info=$tablePreStr."user_info";

	$dbo = new dbex;
	//读写分离定义函数
	dbtarget('w',$dbServs);
	
	//执行删除旧的自定义信息
	userInforDel($dbo,$user_id);
	
	//更新自定义信息表
	if(!empty($info)){
		foreach($info as $key => $value){
			if($value!==''){
				$key=explode('|',$key);
				$sql="insert into $t_user_info (user_id,info_id,info_value) values ($user_id,'".$key[0]."','$value')";
				$dbo -> exeUpdate($sql);
			}
		}
	}
	
	
	
	

//更新users表
	$sql = "update $t_users set birth_province='$birth_province',birth_city='$birth_city',reside_province='$reside_province',reside_city='$reside_city',birth_year='$birth_year',birth_month='$birth_month',birth_day='$birth_day'
			where user_id = $user_id;";
	$dbo -> exeUpdate($sql);

//更新online表
	$sql = "update $t_online set birth_province='$birth_province',birth_city='$birth_city',reside_province='$reside_province',reside_city='$reside_city',birth_year='$birth_year' where user_id = $user_id;";
	$dbo -> exeUpdate($sql);

	//回应信息
	action_return(1,"","modules.php?app=user_info&single=$is_finish&user_id=$user_id");
?>
