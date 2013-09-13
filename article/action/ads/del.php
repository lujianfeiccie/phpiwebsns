<?php
$id=intval($_GET['id']);
if(!$id){
	echo 'error:id值不能为空';exit;
}
$ads_row=select_spell($ArticleTable['article_ads'],"type_id,re_src","id=$id",'','',"getRow");
if($ads_row['type_id']==0){
	$img_src=$ads_row['re_src'];
	@unlink($webRoot.$img_src);
	$t_uploadfile=$tablePreStr."uploadfile";
	del_spell($t_uploadfile,"file_src=$img_src");
}
$is_success=del_spell($ArticleTable['article_ads'],"id=$id");

//返回值
if($is_success){
	@unlink("ads/ad_".$id.".js");
	header("Location:index.php?app=view&ads&manage");
}else{
	echo 'error:';
}
?>