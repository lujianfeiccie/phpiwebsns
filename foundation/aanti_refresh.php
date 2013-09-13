<?php
//防刷新控制代码
$ref_langpackage=new gobacklp;

if(getCookie("post_sep_time")){
	if(time() - getCookie("post_sep_time") < $allowRefreshTime){
		if(isset($RefreshType)=='ajax'){
			action_return(2,'error:'.$ref_langpackage->ref_delay_time,"-1");
		}else{
			action_return(1,$ref_langpackage->ref_delay_time,"-1");
		}
		sleep($delayTime);
	}else{
		set_cookie("post_sep_time",time());
	}
}else{
	set_cookie("post_sep_time",time());
}

//防刷新和防止输入空数据
$anit_refresh=$ref_langpackage->ref_anit_refresh;
$anit_empty=$ref_langpackage->ref_anit_empty;
function antiRePost($sendStr){
	global $RefreshType;
	global $anit_empty;
	global $anit_refresh;
	$message='';//提示信息
	if($sendStr==''){
		$message=$anit_empty;
	}else if($sendStr==getCookie('PostSendStr')){
		if(isset($RefreshType)=='ajax'){
			action_return(2,'error:'.$anit_refresh,"-1");
		}else{
			action_return(1,$anit_refresh,"-1");
		}
	}
	set_cookie('PostSendStr',$sendStr);
}
?>