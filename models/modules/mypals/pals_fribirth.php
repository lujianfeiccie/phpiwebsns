<?php	
	$mp_langpackage=new mypalslp;
	$f_langpackage=new friendlp;
	$send_hi="hi_action";
	$user_id=get_sess_userid();	
	$fir_list = pals_birth($user_id,5);	
?>