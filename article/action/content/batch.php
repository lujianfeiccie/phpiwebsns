<?php
$method=intval(get_argg('method'));
$mod=get_argg('mod');
$item_array=empty($_POST['content_item'])?array():$_POST['content_item'];
if(empty($item_array)){
	echo 'error:文章id值为空';exit;
}

switch($mod){
	case "auditing":
	$cols_name="status";
	break;
	case "recom":
	$cols_name="is_recom";
	break;
}

if($method==1 && $mod=='recom'){
	$update_array=array("is_recom" => 1,"status" => 1);
}else{
	$update_array=array($cols_name => $method);
}

foreach($item_array as $rs){
	$data=explode('|',$rs);
	$id=intval($data[0]);
	$is_success=update_spell($ArticleTable['article_news'],$update_array,"id=$id");
	if($is_success===false){
		echo 'error:';
	}
}
//$key_mt='news/list/';
//updateCache($key_mt);
header("Location:index.php?app=view&content&manage");
?>