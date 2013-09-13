<?php
$id=intval(get_argg('id'));
if(!$id){
	echo 'error:请选择id值';exit;
}

$type_id=get_argp('type_id');
$width=intval(get_argp('width'));
$height=intval(get_argp('height'));
$link=short_check(get_argp('link'));
$descrip=short_check(get_argp('descrip'));
if($link){
	$link=(strtolower(substr($link,0,7))=='http://') ? $link:'http://'.$link;
}else{
	$link='javascript:void(0)';
}

//取得旧的ads信息
$ads_row=select_spell($ArticleTable['article_ads'],"title,type_id,re_src","id=$id",'','',"getRow");

$old_type_id=$ads_row['type_id'];
$old_src=$ads_row['re_src'];

//删除旧的图片图片
if($old_type_id==0 && $old_src!=get_session('ads_img_src')){
	@unlink('../'.$ads_row['re_src']);
}

switch($type_id){
	case 0:
	$res_src=short_check(get_argp('upload_name'));
	$ads_str="<img src='../../$res_src' width='$width' height='$height' alt='$descrip' />";
	$ads_str=($link=='') ? $ads_str:"<a href='$link' target='_blank'>".$ads_str."</a>";
	break;

	case 1:
	$res_src=short_check(get_argp('upload_name'));
	$ads_str="<embed width='$width' height='$height' wmode='transparent' allowscriptaccess='always' quality='high' src='$re_src' type='application/x-shockwave-flash' title='$descrip'></embed>";
	$ads_str=($link=='') ? $ads_str:"<a href='$link' target='_blank'>".$ads_str."</a>";
	break;

	case 2:
	$res_src=short_check(get_argp('txt_src'));

	$ads_str="<div style='width:$width;height:$height'>".$res_src."</div>";
	break;

	case 3:
	$res_src=short_check(get_argp('link_src'));
	$ads_str="<div style='width:$width;height:$height'><a href='$link' target='_blank'>".$res_src."</div>";
	break;
}

if($res_src==''){
	echo 'error:信息不能为空';exit;
}

$par_array=array("re_src","descrip","title","type_id","link","width","height");
$spell_array=array("'".$res_src."'");
$must_array=array("title"=>"名称","type_id"=>"类别","width"=>"宽度","height"=>"高度");
$update_value=request_array($par_array,$spell_array,$must_array);
$update_array=array_combine($par_array,$update_value);
$is_success=update_spell($ArticleTable['article_ads'],$update_array,"id=$id");

if($is_success){
	$ads_str=str_replace(array("'",'"'),"\\'",$ads_str);
	$ads_str="window.document.write(\"".$ads_str."\")";
	$ref_ads=fopen('ads/ad_'.$id.'.js',"w+");
	fwrite($ref_ads,$ads_str);
	fclose($ref_ads);
}

if($is_success!==false){
	header("Location:index.php?app=view&ads&manage");
}else{
	echo 'error:修改广告失败';
}
?>