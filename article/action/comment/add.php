<?php
$id=get_argg('content_id');
$user_id=intval(get_sess_userid());
$user_ip="'".$_SERVER['REMOTE_ADDR']."'";

if($comType==1&&!get_sess_userid()){
	echo '<script type="text/javascript">alert("请先注册会员");window.history.go(-1);</script>';exit;
}
//插入数据
$insert_cols=array("addtime","user_ip","user_id","content_id","user_name","user_email","content");
$insert_values=array('"'.constant('NOWTIME').'"',"$user_ip",$user_id);//初始化字段数组
$must_array=array("user_name"=>"用户名","user_email"=>"邮箱","content"=>"内容");//必填参数
$insert_values=request_array($insert_cols,$insert_values,$must_array);//赋值字段数组

$channel_insert_cols=array_combine($insert_cols,$insert_values);//链接字段与数据
$is_success=insert_spell($ArticleTable['article_comment'],$channel_insert_cols);//执行插入操作,并且返回新节点的id值
$is_success2=false;
if($is_success){
	$update_array=array("comments" => "comments+1");
	$is_success2=update_spell($ArticleTable['article_news'],$update_array,"id=$id");
}

if(!$is_success2){
	action_return(1,'发生错误',-1);exit;
}

//comment/list/all/new_c[id]_a


//$key_array=array('comment/list/all/new','all/is_digg','all/display','all/menu');
//updateCache('channel/list/',$key_array);

echo '<script type="text/javascript">window.history.go(-1);</script>';
?>