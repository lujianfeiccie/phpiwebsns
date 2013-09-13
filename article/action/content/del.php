<?php
require('foundation/ftag.php');
$item_array=empty($_POST['content_item'])?array():$_POST['content_item'];
$t_uploadfile=$tablePreStr."uploadfile";
if(empty($item_array)){
	echo 'error:文章id值为空';exit;
}
foreach($item_array as $rs){
	$data=explode('|',$rs);
	$id=intval($data[0]);
	$channel_id=intval($data[1]);
	
	//标签删除
	$tag=get_tag_article($ArticleTable['article_news'],"id",$id);
	tag_del_article($tag,$id);
	
	$new_info=select_spell($ArticleTable['article_news'],'content,thumb',"id=$id",'','',"getRow");
	$new_content=$new_info['content'];
	preg_match_all("/classId=\"\d+\"/",$new_content,$match);
  $match=preg_replace("/[classId=,\"]/",'',$match[0]);
  
  $file_src=array();
	if(!empty($match)){
		$match=join(",",$match);
		$sql="select file_src from $t_uploadfile where id in ($match)";
		$file_src=$dbo->getRs($sql);
		
		foreach($file_src as $rs){
			unlink('../'.$rs['file_src']);
		}
	}
	
	if($new_info['thumb']){
		unlink('../'.$new_info['thumb']);
	}
	
  if(!empty($file_src)){
    $sql="delete from $t_uploadfile where id in ($match)";
    $dbo->exeUpdate($sql);
	}
	
	$is_success=del_spell($ArticleTable['article_news'],"id=$id");
	$is_success2=0;
	if($is_success){
		del_spell($ArticleTable['article_comment'],"content_id=$id");
		$update_array=array("count"=>"count-1");
		$is_success2=update_spell($ArticleTable['article_channel'],$update_array,"id=$channel_id");
	}
	if(!$is_success2){
		echo 'error:';exit;
	}
}
$key_mt='news/list/';
updateCache($key_mt);
header("Location:index.php?app=view&content&manage");
?>