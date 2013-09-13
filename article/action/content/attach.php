<?php
$mod=get_argg('mod');
$id=get_argg('id');

if(empty($id)){
	echo '文章id值为空';exit;
}

if($diggType==1&&!get_sess_userid()){
	echo '请先注册会员';exit;
}

//增加点击量
$is_attach=get_session('attach_'.$id);
if($is_attach){
	echo '您已经参与过了';exit;
}

switch($mod){
	case "support":
	$col="support";
	break;
	
	case "against":
	$col="against";
	break;
	
	default:
	echo 'error';
	break;
}

$update_array=array($col => "$col+1");
$is_success=update_spell($ArticleTable['article_news'],$update_array,"id=$id");
if(!$is_success){
	echo 'error:';
}else{
	set_session('attach_'.$id,1);//放灌水
	echo 'success';
}
?>