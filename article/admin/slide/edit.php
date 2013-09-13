<?php
$id=get_argg('id');
if($id){
	$slide_row=select_spell($ArticleTable['article_slide'],'*',"id=$id",'','','getRow');
	$submit_str='修改';
	$title=$slide_row['title'];
	$link=$slide_row['link'];
	$order_num=$slide_row['order_num'];
	$slide=$slide_row['photo_src'];
	$form_action="index.php?app=act&slide&edit&id=".$id;
}else{
	$submit_str='添加';
	$title='';
	$link='';
	$order_num='';
	$slide='';
	$form_action="index.php?app=act&slide&add";
}
?>

<form action='<?php echo $form_action;?>' name='ajax' target='act_result' >
	<input type='hidden' name='upload_name' id='upload_name' value='<?php echo $slide;?>' />
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
		<tr><th>标题</th><td><input class="regular-text" type='text' name='title' value='<?php echo $title;?>' /></td></tr>
		<tr>
		  <th>链接</th><td><input class="regular-text" type='text' name='link' value='<?php echo $link;?>' /></td></tr>
		<tr>
		  <th>排序</th><td><input class="small-text" type='text' name='order_num' value='<?php echo $order_num;?>' /></td></tr>
		<tr>
			<th>缩略图</th>
			<td>
        <div id='img_priview' <?php echo $slide ? '':'style=display:none';?>>
        	<img id='priview_img' src='<?php echo '../'.$slide;?>' width='150px' /><br/><a id='priview_img_act' href="index.php?app=act&upload&del&src=<?php echo '../'.$thumb;?>" name="ajax" target="img_def">删除</a>
        </div>
        <iframe id='thumb_iframe' <?php echo $slide ? 'style=display:none':'';?> width="100%" height=30 align=top allowTransparency="true" scrolling=no src='index.php?app=view&upload&upload&mod=slide' frameborder=0></iframe>
      </td>
		</tr>
    <tr>
    	<td></td>
			<td><input class="regular-btn" type='submit' value='<?php echo $submit_str;?>'/></td>
		</tr>
	</table>
</form>
