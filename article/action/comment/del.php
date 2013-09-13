<?php
$item_array=empty($_POST['content_item'])?array():$_POST['content_item'];
if(empty($item_array)){
	echo 'error:评论id值为空';exit;
}

foreach($item_array as $rs){
	$data=explode('|',$rs);
	$id=intval($data[0]);
	$content_id=intval($data[1]);

	$is_success=del_spell($ArticleTable['article_comment'],"id=$id");
	$is_success2=0;
	if($is_success){
		$update_array=array("comments"=>"comments-1");
		$is_success2=update_spell($ArticleTable['article_news'],$update_array,"id=$content_id");
	}
	if(!$is_success2){
		echo 'error:';exit;
	}
}

//$key_array=array('all_c[nodepath]_mt','all/is_digg','all/display','all/menu');
//updateCache('channel/list/',$key_array);
header("Location:index.php?app=view&comment&manage");
?>