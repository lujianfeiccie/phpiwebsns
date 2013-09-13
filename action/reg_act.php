<?php
//引入公共模块
require("foundation/module_users.php");
require("foundation/aintegral.php");
require("api/base_support.php");
require("foundation/csmtp.class.php");
require("foundation/asmtp_info.php");

//引入语言包
$re_langpackage=new reglp;

//数据表定义区
$t_users=$tablePreStr."users";
$t_online=$tablePreStr."online";
$t_pals_def_sort=$tablePreStr."pals_def_sort";
$t_pals_sort=$tablePreStr."pals_sort";
$t_mypals=$tablePreStr."pals_mine";
$t_invite_code=$tablePreStr."invite_code";
$t_user_activation=$tablePreStr."user_activation";

$dbo=new dbex;
dbtarget('r',$dbServs);

//ajax校验email和验证码
if(get_argg('ajax')==1){
	$user_email=short_check(get_argg("user_email"));
	$user_vericode=get_argg("veriCode");
	if($user_email){
		$sql="select user_id from $t_users where user_email='$user_email'";
		$user_info=$dbo->getRow($sql);
		if($user_info){
			echo $re_langpackage->re_rep_mail;exit;
		}
	}

	if($user_vericode){
		if(strtolower($_SESSION['verifyCode'])!=strtolower($user_vericode)){
			echo $re_langpackage->re_wrong_val;exit;
		}
	}
	exit;
}

function checkmail($user_email){   //验证电子邮件地址
	if(preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$user_email))
		return true;
	else
		return false;
}

if(strlen(get_argp("user_name"))<4){
   action_return(0,$re_langpackage->re_right_name,"-1");
}

if(!checkmail(get_argp("user_email"))) {
    action_return(0,$re_langpackage->re_right_email,"-1");
}

if(strlen(get_argp("user_repassword"))<6){
	  action_return(0,$re_langpackage->re_pass_limit,"-1");
}

$user_name=short_check(get_argp("user_name"));
$user_pws=md5(get_argp("user_password"));
$user_sex=intval(get_argp("user_sex"));
$user_email=short_check(get_argp("user_email"));
$is_pass=1;
$user_vericode=get_argp("veriCode");
$invite_fromuid=0;
if(get_session('InviteFromUid')){
	  $invite_fromuid=get_session('InviteFromUid');
}

if(strtolower($_SESSION['verifyCode'])!=strtolower($user_vericode)){
	action_return(0,$re_langpackage->re_wrong_val,"-1");
}

unset($_SESSION['verifyCode']);

//读取数据
$sql="select user_id from $t_users where user_email='$user_email'";
$user_info=$dbo->getRow($sql);
$sort_rs = api_proxy("pals_sort_def");

if($user_info){
	action_return(0,$re_langpackage->re_rep_mail,"-1");
}else{
//检测邀请码
	if($inviteCode){
		$is_check=array();
		$invite_code=short_check(get_argp('invite_code'));
		if($invite_code==''){
			action_return(0,'请填写邀请码',"-1");exit;
		}
		$sql="select id from $t_invite_code where code_txt='$invite_code'";
		$is_check=$dbo->getRow($sql);
		if(empty($is_check)){
			action_return(0,'邀请码不正确或已经失效',"-1");exit;
		}
		$sql="delete from $t_invite_code where code_txt='$invite_code'";
		$dbo->exeUpdate($sql);
	}

	//写入数据
	$user_ico=($user_sex==0)?"skin/$skinUrl/images/d_ico_0_small.gif":"skin/$skinUrl/images/d_ico_1_small.gif";
	dbtarget('w',$dbServs);
	$sql="insert into $t_users (user_name,user_pws,user_sex,user_email,user_add_time,user_ico,invite_from_uid,is_pass,lastlogin_datetime,birth_year , birth_month , birth_day ,login_ip )"
					." values('$user_name','$user_pws',$user_sex,'$user_email','".constant('NOWTIME')."','$user_ico',$invite_fromuid,$is_pass,'".constant('NOWTIME')."','','','','$_SERVER[REMOTE_ADDR]')";

	if(!$dbo->exeUpdate($sql)){
		action_return(0,$re_langpackage->re_reg_false,"-1");
	}

	$user_id=mysql_insert_id();
	$now_time=time();

	$sql="insert into $t_online (user_id,user_name,user_sex,user_ico,active_time,hidden) values ($user_id,'$user_name',$user_sex,'$user_ico','$now_time',0)";
	$dbo->exeUpdate($sql);

	foreach($sort_rs as $rs){
		$sort_id=$rs['id'];
		$sort_name=$rs['name'];
		$sql="insert into $t_pals_sort ( name , user_id ) values ( '$sort_name' , $user_id )";
		$dbo->exeUpdate($sql);
	}

	if($invite_fromuid){
		increase_integral($dbo,$int_invited,$invite_fromuid);
		//取得介绍人的资料信息
		$user_row = api_proxy("user_self_by_uid","user_id,user_name,user_sex,user_ico,palsreq_limit",$invite_fromuid);
		if($user_row){
			$touser_id=$user_row['user_id'];
			$touser_name=$user_row['user_name'];
			$touser_sex=$user_row['user_sex'];
			$touser_ico=$user_row['user_ico'];
			$touser_pals_limit=$user_row['palsreq_limit'];
		}
		if($touser_pals_limit==0){
			$sql="insert into $t_mypals (user_id,pals_id,pals_name,pals_sex,add_time,pals_ico,accepted) values ($user_id,$invite_fromuid,'$touser_name','$touser_sex','".constant('NOWTIME')."','$touser_ico',1)";
			$dbo->exeUpdate($sql);
			set_sess_mypals($invite_fromuid);
		}
	}



	//不需要激活时直接添加session
	if($mailActivation == 0){
		set_sess_userid($user_id);
		set_sess_usersex($user_sex);
		set_sess_username($user_name);
		set_sess_userico($user_ico);
		set_sess_online('0');
?>
		<script type='text/javascript'>
		var login_time=new Date();
		login_time.setTime(login_time.getTime() +3600*250 );
		document.cookie="IsReged=Y;expires="+ login_time.toGMTString();
		location.href='./main.php';
	  </script>
<?php
	//需要激活时的操作
	}else{
		//邮箱配置信息检测
		if(!$smtpAddress || !$smtpPort || !$smtpEmail || !$smtpUser || !$smtpPassword){
			action_return(1,'邮箱信息配置不正确,请联系管理员','index.php');
		}

		//生成MD5加密后的激活码
		$activation_code = md5($user_email.time());

		//在激活码表中压入新数据
		$sql="insert into $t_user_activation (time,activation_code) values ('".constant('NOWTIME')."','$activation_code')";
		$dbo->exeUpdate($sql);

		//获取激活码的id
		$new_activation_id = mysql_insert_id();

		//查询此注册用户的user_id
		$sql="select user_id from $t_users where user_email='$user_email'";
		$new_user=$dbo->getRow($sql);
		$new_user_id = $new_user['user_id'];

		//将此注册用户的激活码表id关联到用户表
		$sql="update $t_users set activation_id='$new_activation_id' where user_id=$new_user_id ";
		$dbo->exeUpdate($sql);

		//激活邮件的title和body信息
		$mailtitle = $siteName."新用户激活";
		$mailbody = "尊敬的".$user_name."：<br />您好：".'<br />'."您在".$siteName."上注册了新用户，请点击下面的链接激活您的账户<br /><a href='".$siteDomain."modules.php?app=user_activation&user_email=".$user_email."&activation_code=".$activation_code."'>href='".$siteDomain."modules.php?app=user_activation&user_email=".$user_email."&activation_code=".$activation_code."</a>";
		$email_array=explode('@',$user_email);
		$email_site=strtolower($email_array[1]);

		//为hotmail和gmail邮箱单独设置字符集
		$utf8_site=array("hotmail.com","gmail.com");
		if(!in_array($email_site,$utf8_site)){
			$mailbody = iconv('UTF-8','GBK',$mailbody);
			$mailtitle = iconv('UTF-8','GBK',$mailtitle);
		}

		//发送邮件
		$smtp = new smtp($smtpAddress,$smtpPort,true,$smtpUser,$smtpPassword);
		$result=$smtp->sendmail($user_email,$smtpEmail,$mailtitle,$mailbody,'HTML');

?>
	<script type='text/javascript'>
		location.href='modules.php?app=user_activate_succ&user_email=<?php echo $user_email ?>';
	</script>
<?php
	}
}
?>