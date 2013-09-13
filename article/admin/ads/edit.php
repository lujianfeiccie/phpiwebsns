<?php
$id=get_argg('id');
if($id){
	$ads_row=select_spell($ArticleTable['article_ads'],'*',"id=$id",'','','getRow');
	$title=$ads_row['title'];
	$link=$ads_row['link'];
	$action="index.php?app=act&ads&edit&id=".$id;
	$submit_str='修改';
	$type_id=$ads_row['type_id'];
	$width=$ads_row['width'];
	$height=$ads_row['height'];
	$re_src=$ads_row['re_src'];
	$width=$ads_row['width'];
	$height=$ads_row['height'];
	$descrip=$ads_row['descrip'];
}else{
	$descrip='';
	$width='';
	$height='';
	$type_id=0;
	$title='';
	$link='';
	$action='index.php?app=act&ads&add';
	$submit_str='添加';
	$re_src='';
}
?>
<form action='<?php echo $action;?>' name='ajax' target='act_result'>
	<input type='hidden' name='upload_name' id='upload_name' value='<?php echo $re_src;?>' />
	<table cellpadding="2" cellspacing="1" class="form_table">
		<tr>
			<th>名称：</th><td><input class="med-text" type='text' name='title' value='<?php echo $title;?>' /></td>
		</tr>
		<tr>
			<th>类型：</th>
			<td>
				<input type='radio' value='0' name='type_id' <?php echo $re_src&&($type_id==0||$type_id==1) ? 'disabled=false':'';?> <?php echo $type_id==0 ? 'checked':'';?> onclick=ads_div(0); />图片
				<input type='radio' value='1' name='type_id' <?php echo $re_src&&($type_id==0||$type_id==1) ? 'disabled=false':'';?> <?php echo $type_id==1 ? 'checked':'';?> onclick=ads_div(1); />flash
				<input type='radio' value='2' name='type_id' <?php echo $re_src&&($type_id==0||$type_id==1) ? 'disabled=false':'';?> <?php echo $type_id==2 ? 'checked':'';?> onclick=ads_div(2); />文字
				<input type='radio' value='3' name='type_id' <?php echo $re_src&&($type_id==0||$type_id==1) ? 'disabled=false':'';?> <?php echo $type_id==3 ? 'checked':'';?> onclick=ads_div(3); />文字链接
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<div id='img_priview' <?php echo ($re_src && $type_id==0) ? '':'style="display:none"';?>>
					<img id='priview_img' src='../<?php echo $re_src;?>' width=120px /><br/>
					<a id='priview_img_act' href="index.php?app=act&upload&del&src=<?php echo '../'.$re_src;?>" name="ajax" target="img_def">删除</a>
				</div>
				<iframe id='thumb_iframe' width="100%" height=30 <?php echo ($re_src=='' && $type_id==0) ? '':'style="display:none"';?> align=top allowTransparency="true" scrolling=no src='index.php?app=view&upload&upload&mod=ads' frameborder=0></iframe>

				<div id='flash_priview' <?php echo ($re_src && $type_id==1) ? '':'style="display:none"';?>>
					<embed id='priview_flash' src='<?php echo $siteDomain.$re_src;?>' width="420px" height="120px" type='application/x-shockwave-flash' wmode='transparent' allowscriptaccess='always' quality='high'></embed><br/>
					<a id='priview_flash_act' href="index.php?app=act&upload&del&src=<?php echo '../'.$re_src;?>" name="ajax" target="flash_def">删除</a>
				</div>
				<iframe id='flash_iframe' width="100%" height=30 align=top allowTransparency="true" <?php echo ($re_src && $type_id==1) ? '':'style="display:none"';?> scrolling=no src='index.php?app=view&upload&upload&mod=flash' frameborder=0></iframe>

				<div id='txt_type' <?php echo $type_id==2 ? '':'style="display:none"';?>>
					<textarea name='txt_src' id='txt_src' class="textarea" style='width:480px;height:100px;margin-bottom:10px'><?php echo $re_src;?></textarea>(广告文字)
				</div>

				<div id='link_type' <?php echo $type_id==3 ? '':'style="display:none"';?>>
					<input name='link_src' id='link_src' class="med-text" value="<?php echo $re_src;?>" />(链接内容)
				</div>

			</td>
		</tr>
		<tr>
			<th>链接地址：</th>
			<td><input type='text' class="med-text" id="link" name="link" value="<?php echo $link;?>" /></td>
		</tr>
		<tr>
			<th>描述：</th><td><input class="med-text" type='text' name='descrip' value='<?php echo $descrip;?>' /></td>
		</tr>
		<tr>
			<th>宽高值：</th>
			<td>
				<input type='text' class="small-text" name="width" style="width:45px" value="<?php echo $width;?>" />×
				<input type='text' class="small-text" name="height" style="width:45px" value="<?php echo $height;?>" />(单位：像素px)
			</td>
		</tr>
		<tr><td></td><td colspan=3><input class="regular-btn" type='submit' value='<?php echo $submit_str;?>' /></td></tr>
	</table>
</form>
