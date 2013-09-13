<?php
$curPstyle='';
$page_up='上一页';
$page_down='下一页';
function page_show($mod,$page_num,$page_total){
	if($mod==0){
		pagesbar($page_num,$page_total,'page');
	}
}

function page_show_parameter($mod,$page_num,$page_total,$page){
	if($mod==0){
		pagesbar($page_num,$page_total,$page);
	}
}
//分页显示函数
function pagesbar($page_num,$page_total,$page){
	global $PAGE_CONTENT_ID;
	global $page_up;
	global $page_down;
	$PAGE_CONTENT_ID=$PAGE_CONTENT_ID ? $PAGE_CONTENT_ID:'cms_content';
	$get_para=getUrlPara();
	$para_arr=explode('&'.$page,$get_para);
	$get_para=$para_arr[0];
	if(empty($page_num)){ $page_num=1; }
	if($page_num>1){
		$prePageUrl="index.php".$get_para.'&'.$page.'='.($page_num-1);
		$pajax_str="name='ajax' target='$PAGE_CONTENT_ID'";
	}else{
		$prePageUrl='javascript:void(0)" style="color:#838383';
		$pajax_str="";
	}

	if($page_total>$page_num){
		$nextPageUrl="index.php".$get_para.'&'.$page.'='.($page_num+1);
		$najax_str="name='ajax' target='$PAGE_CONTENT_ID'";
	}else{
		$nextPageUrl='javascript:void(0)" style="color:#838383';
		$najax_str="";
	}
	?>

	<div class="pages_bar">
	<a <?php echo $pajax_str;?> href="<?php echo $prePageUrl;?>" style="margin-left:3px;right:3px">
	<?php echo $page_up;?></a>

	<?php
	if($page_total>10){

		if($page_num<=5){
			for($i=1;$i<=10;$i++){
				if($page_num==$i){
						$curPstyle=' font-weight:bold;text-decoration:underline';
				}else{
						$curPstyle='';
				}
				echo '<a name="ajax" target="cms_content" href="index.php'.$get_para.'&'.$page.'='.$i.'" style="margin-left:1px;right:1px;'.$curPstyle.'">&nbsp;'.$i.'&nbsp;</a>';
			}
		}
		else if($page_num>=6&&$page_num+5<$page_total){

				for($b=$page_num-4;$b<$page_num;$b++){
						echo '<a name="ajax" target="'.$PAGE_CONTENT_ID.'" href="index.php'.$get_para.'&'.$page.'='.$b.'" style="margin-left:1px;right:1px;">&nbsp;'.$b.'&nbsp;</a>';
				}

				echo '<a name="ajax" target="'.$PAGE_CONTENT_ID.'" href="index.php'.$get_para.'&'.$page.'='.$page_num.'" style="margin-left:1px;right:1px;color:#333">&nbsp;'.$page_num.'&nbsp;</a>';

				for($h=$page_num+1;$h <= $page_num+5 ;$h++){
						echo '<a name="ajax" target="'.$PAGE_CONTENT_ID.'" href="index.php'.$get_para.'&'.$page.'='.$h.'" style="margin-left:1px;right:1px;">&nbsp;'.$h.'&nbsp;</a>';
				}
		}
		else if($page_num+5>=$page_total){
				for($i=$page_total-9;$i<=$page_total;$i++){
					if($page_num==$i){
						$curPstyle=' font-weight:bold;text-decoration:underline';
					}else{
						$curPstyle='';
					}
					echo '<a name="ajax" target="'.$PAGE_CONTENT_ID.'" href="index.php'.$get_para.'&'.$page.'='.$i.'" style="margin-left:1px;right:1px;'.$curPstyle.'">&nbsp;'.$i.'&nbsp;</a>';
				}
		}
	}else{
		for($i=1;$i<=$page_total;$i++){
			if($page_num==$i){
				$curPstyle=' font-weight:bold;text-decoration:underline';
			}else{
				$curPstyle='';
			}
			echo '<a name="ajax" target="'.$PAGE_CONTENT_ID.'" href="index.php'.$get_para.'&'.$page.'='.$i.'" style="margin-left:1px;right:1px;'.$curPstyle.'">&nbsp;'.$i.'&nbsp;</a>';
		}
	}
	?>
	<a <?php echo $najax_str;?> href="<?php echo $nextPageUrl;?>" style="margin-left:3px;right:3px;">
	<?php echo $page_down;?></a>
	</div>
<?php
}
?>