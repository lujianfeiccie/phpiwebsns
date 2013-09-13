<?php
//引入公共类
require("api/base_support.php");
require("foundation/module_users.php");
require("foundation/csmtp.class.php");
require("foundation/asmtp_info.php");

//数据表定义区
$t_users=$tablePreStr."users";
$t_user_activation=$tablePreStr."user_activation";

//定义数据库读写操作
$dbo=new dbex;
dbtarget('r',$dbServs);

//获取系统时间
$now_time = date('Y-m-d H:i:s',time());

//获取参数
$user_email = get_argg('user_email');

//生成MD5加密后的激活码
$activation_code = md5($user_email.time());

//在激活码表中插入新数据
$sql="insert into $t_user_activation (time,activation_code) values ('$now_time','$activation_code')";
$dbo->exeUpdate($sql);

//获取激活码id
$new_activation_id = mysql_insert_id();

//匹配激活码
$this_activation = getMatchActivation($dbo,$user_email);
$old_activation_id = $this_activation['id'];
$user_name=$this_activation['user_name'];

//删除过期的激活码数据
$sql="delete from $t_user_activation where id='$old_activation_id'";
$dbo->exeUpdate($sql);

//更新用户表中邮箱激活注册关联id
$sql="update $t_users set activation_id='$new_activation_id' where user_email='$user_email' ";
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
$smtp->sendmail($user_email,$smtpEmail,$mailtitle,$mailbody,'HTML');
?>
<script type='text/javascript'>
	location.href='modules.php?app=user_activate_succ&user_email=<?php echo $user_email ?>';
</script>