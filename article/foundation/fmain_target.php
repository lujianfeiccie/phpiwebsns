<?php
function getUrlPara(){
  $urlTmp=$_SERVER["REQUEST_URI"];
  if(substr_count($urlTmp,'?')==1){
	   $urlArr=explode("?",$urlTmp);
	   return '?'.$urlArr[1];
  }else{
  	 return '';
  }
}

function channel_type_rewrite($id,$name,$type_id,$out_link){
	switch($type_id){
		case 0:
		return '<li><a href="index.php?app=view&list&id='.$id.'">'.$name.'</a></li>';
		break;
		
		case 1:
		return '<li><a href="'.$out_link.'">'.$name.'</a></li>';
		break;
		
		case 2:
		return '<li><a href="http://'.str_replace("http://","",$out_link).'" target="_blank">'.$name.'</a></li>';
		break;
		
		default:
		return 'error';
		break;
	}
}
?>
