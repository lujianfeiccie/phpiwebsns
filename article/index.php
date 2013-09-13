<?php
header("content-type:text/html;charset=utf-8");
$IWEB_article_IN=true;
$iweb_power=true;
$getpost_power=true;
require(dirname(__FILE__)."/config/config.php");
include(dirname(__file__)."/../plugins/article_widget/table_prefix.php");
require(dirname(__FILE__)."/../api/base_support.php");
require(dirname(__FILE__)."/foundation/fmain_target.php");
require(dirname(__FILE__)."/foundation/fsql_spell.php");
require(dirname(__FILE__)."/../foundation/fcheck_rights.php");

//语言包路径
$langPackageBasePath="langpackage/".$langPackagePara."/";
require_once($webRoot.$langPackageBasePath."public.php");
require_once("../api/Check_MC.php");

$guest_array=array();
if(get_argg('app')=='view'){	//显示
	$guest_array=array(
		"default"=>"default.php",
		"list"=>"list.php",
		"show"=>"show.php",
		"system/manage"=>"admin/system/manage.php",
		"channel/edit"=>"admin/channel/edit.php",
		"channel/manage"=>"admin/channel/manage.php",
		"channel/tree"=>"admin/channel/tree.php",
		"comment/manage"=>"admin/comment/manage.php",
		"privacy/add_person"=>"admin/privacy/add_person.php",
		"privacy/group"=>"admin/privacy/group.php",
		"privacy/person"=>"admin/privacy/person.php",
		"privacy/resource"=>"admin/privacy/resource.php",
		"privacy/allot_pri"=>"admin/privacy/allot_pri.php",
		"content/check"=>"admin/content/check.php",
		"content/edit"=>"admin/content/edit.php",
		"content/manage"=>"admin/content/manage.php",
		"upload/upload"=>"admin/upload/upload.php",
		"ads/manage"=>"admin/ads/manage.php",
		"ads/edit"=>"admin/ads/edit.php",
		"slide/manage"=>"admin/slide/manage.php",
		"slide/edit"=>"admin/slide/edit.php",
		"tag/manage"=>"admin/tag/manage.php",
	);
}else if(get_argg('app')=='act'){	//控制
	$guest_array=array(
		"system/edit"=>"action/system/edit.php",
		"system/compile"=>"action/system/compile.php",
		"system/static"=>"action/system/static.php",
		"channel/add"=>"action/channel/add.php",
		"channel/del"=>"action/channel/del.php",
		"channel/edit"=>"action/channel/edit.php",
		"content/add"=>"action/content/add.php",
		"content/edit"=>"action/content/edit.php",
		"content/del"=>"action/content/del.php",
		"content/batch"=>"action/content/batch.php",
		"content/attach"=>"action/content/attach.php",
		"comment/add"=>"action/comment/add.php",
		"comment/del"=>"action/comment/del.php",
		"privacy/add"=>"action/privacy/add.php",
		"privacy/del"=>"action/privacy/del.php",
		"privacy/edit"=>"action/privacy/edit.php",
		"privacy/allot_pri"=>"action/privacy/allot_pri.php",
		"upload/upload"=>"action/upload/upload.php",
		"upload/del"=>"action/upload/del.php",
		"ads/add"=>"action/ads/add.php",
		"ads/edit"=>"action/ads/edit.php",
		"ads/del"=>"action/ads/del.php",
		"slide/add"=>"action/slide/add.php",
		"slide/edit"=>"action/slide/edit.php",
		"slide/del"=>"action/slide/del.php",
		"tag/action"=>"action/tag/action.php",
	);
}

//后台管理操作app参数及权限码
$article_back=array(
	'system'=>'article_01',
	'ads'=>'article_02',
	'slide'=>'article_03',
	'tag'=>'article_04',
);

//无权限的action操作
$article_attach=array(
	'comment/add',
	'content/attach',
);

$URL_KEY='';
$URL_STR=$_SERVER['QUERY_STRING'];
$URL_ARRAY=explode("&",$URL_STR);

if(isset($URL_ARRAY[1])){//非管理类操作
	if(array_key_exists($URL_ARRAY[1],$guest_array)){
		$URL_KEY=$URL_ARRAY[1];
	}
}

if(isset($URL_ARRAY[2])){//管理类操作
	if(array_key_exists($URL_ARRAY[1].'/'.$URL_ARRAY[2],$guest_array)){
		$URL_KEY=$URL_ARRAY[1].'/'.$URL_ARRAY[2];
	}
}



//权限判断
if(array_key_exists($URL_KEY,$guest_array)){
	//前台管理操作app参数及权限码
	$privacy_cache_str='';
	if(!in_array($URL_KEY,$article_attach) && !array_key_exists($URL_ARRAY[1],$article_back)){
		if(!file_exists('config/privacy_cache.php')){
			update_privacy_cache($ArticleTable['article_resource']);
		}
		$privacy_cache_str=file_get_contents('config/privacy_cache.php');
		$article_front=unserialize($privacy_cache_str);
	}

	if(array_key_exists($URL_ARRAY[1],$article_back) && !check_rights('article_widget_'.$article_back[$URL_ARRAY[1]])){//插件后台管理操作
		echo 'no permission';exit;
	}else if($privacy_cache_str!='' && array_key_exists($URL_ARRAY[1],$article_front) && !(get_session('cms_pri')==$article_front[$URL_ARRAY[1]]||get_session('cms_pri')=='superadmin')){//插件前台管理操作
		echo 'no permission';exit;
	}else{
		require($guest_array[$URL_KEY]);
	}
}else{
	if(!file_exists(dirname(__FILE__).'/index.html')){
		$index_content=file_get_contents($siteDomain.dirname(__FILE__).'/index.php');
		$ref=fopen(dirname(__FILE__).'/index.html','w+');
		fwrite($ref,$index_content);
		fclose($ref);
	}
	//require(dirname(__FILE__).'/index.html');
	require(dirname(__FILE__).'/default.php');
}

//action动作成功控制函数
function action_return($state=1,$retrun_mess="",$activeUrl=""){
		if($state==2){echo $retrun_mess;exit;}
	  echo "<script language='javascript'>";
	  if(trim($retrun_mess)!=''){
	  	 echo "alert('".$retrun_mess."');";
	  }
		if($activeUrl=='-1'){
			echo "history.go(-1);";
		}else if($activeUrl=='0'){
			echo "window.close();";
		}else{
			echo "location.href='".$activeUrl."';";
		}
			echo "</script>";exit();
}
?>