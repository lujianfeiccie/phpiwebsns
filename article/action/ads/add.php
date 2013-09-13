<?php
$type_id=get_argp('type_id');
$width=intval(get_argp('width'));
$height=intval(get_argp('height'));
$link=short_check(get_argp('link'));
$descrip=short_check(get_argp('descrip'));
if($link){
	$link=(strtolower(substr($link,0,7))=='http://') ? $link:'http://'.$link;
}

switch($type_id){
	case 0:
	$res_src=short_check(get_argp('upload_name'));
	$ads_str="<img src='".$siteDomain.$res_src."' width='$width' height='$height' alt='$descrip' />";
	$ads_str=($link=='') ? $ads_str:"<a href='$link' target='_blank'>".$ads_str."</a>";
	break;

	case 1:
	$res_src=short_check(get_argp('upload_name'));
	$ads_str="<embed width='$width' height='$height' wmode='transparent' allowscriptaccess='always' quality='high' src='".$siteDomain.$res_src."' type='application/x-shockwave-flash' title='$descrip'></embed>";
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

//插入数据
$insert_cols=array("re_src","descrip","title","link","type_id","width","height");//频道插入字段
$insert_values=array("'".$res_src."'");//初始化字段数组
$must_array=array("title"=>"名称","type_id"=>"类别","width"=>"宽度","height"=>"高度");
$insert_values=request_array($insert_cols,$insert_values,$must_array);//赋值字段数组
$ads_insert_cols=array_combine($insert_cols,$insert_values);//链接字段与数据
$is_success=insert_spell($ArticleTable['article_ads'],$ads_insert_cols);//执行插入操作,并且返回新节点的id值

//处理返回
if($is_success){
	$ads_str=str_replace(array("'",'"'),"\\'",$ads_str);
	$ads_str="window.document.write(\"".$ads_str."\")";
	$ref_ads=fopen('ads/ad_'.$is_success.'.js',"w+");
	fwrite($ref_ads,$ads_str);
	fclose($ref_ads);
	header("Location:index.php?app=view&ads&manage");
}else{
	echo 'error:';
}
?>