<?php
//激活码发送成功后跳转至此页面
$mail =array(
	'qq.com'=>"http://mail.qq.com",
	'163.com'=>"http://mail.163.com",
	'126.com'=>"http://mail.126.com",
	'188.com'=>"http://mail.188.com",
	'139.com'=>"http://mail.139.com",
	'sohu.com'=>"http://mail.sohu.com",
	'sina.com'=>"http://mail.sina.com",
	'sina.com.cn'=>"http://mail.sina.com.cn",
	'gmail.com'=>"http://mail.gmail.com"
);
$user_email = short_check(get_argg('user_email'));
preg_match("/(.*?)@(.*)/",$user_email,$mail_add);

?>