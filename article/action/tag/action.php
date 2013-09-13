<?php
require('foundation/ftag.php');
$id=intval(get_argg('id'));
$type=short_check(get_argg('type'));

$key_mt='tag/list/';

switch($type){
	case "add":
		$name = str_filter(get_argp('add_tag'),30);
		$name = str_replace(" ","",$name);
		if(strpos($name,',')||strpos($name,'|')||$name==''){
			echo 'error:标签的输入格式不符合规范';exit;
		}
		$insert_array = array('name'=>"'$name'");
		$is_success = insert_spell($ArticleTable['article_tag'],$insert_array);
	break;

	case "del":
		$is_success=false;
		$is_success1 = del_spell($ArticleTable['article_tag'],"id=$id");
		if($is_success1!==false){
			$is_success = del_spell('isns_article_tag',"id=$id");
		}
	break;

	case "edit":
		$name = str_filter(get_argp('edit_name'),30);
		$name = str_replace(" ","",$name);
		if(strpos($name,',')||strpos($name,'|')||$name==''){
			echo 'error:标签的输入格式不符合规范';exit;
		}
		$update_array = array('name'=>"'$name'");
		$is_success = update_spell($ArticleTable['article_tag'],$update_array,"id=$id");
	break;
	
	case "recommend":
		$hot=intval(get_argg('hot'));
		$update_array = array('hot' => $hot);
		$is_success = update_spell($ArticleTable['article_tag'],$update_array,"id=$id");
	break;
}

	if($is_success!==false){
		header("Location:index.php?app=view&tag&manage");
	}else{
		echo 'error:添加错误';
	}

updateCache($key_mt);
?>
