<?php
require("foundation/modules_channel.php");
$channel_rs=select_spell($ArticleTable['article_channel'],'*','type_id=0','nodepath','','getALL',1,'channel/list/all/type_id/0');
$id=get_argg('id');
if($id){
	$content_row=select_spell($ArticleTable['article_news'],'*',"id=$id",'','','getRow');
	$submit_str='修改';
	$title=$content_row['title'];
	$channel_id=$content_row['channel_id'];
	$thumb=$content_row['thumb'];
	$user_name=$content_row['user_name'];
	$keywords=$content_row['keywords'];
	$description=$content_row['description'];
	$content=$content_row['content'];
	$origin=$content_row['origin'];
	$order=$content_row['order_num'];
	$resume=$content_row['resume'];
	$tag=$content_row['tag'];
	$form_action="index.php?app=act&content&edit&id=".$id;
}else{
	$submit_str='发布';
	$channel_id='';
	$title='';
	$thumb='';
	$user_name='';
	$keywords='';
	$description='';
	$content='';
	$origin='';
	$order='';
	$resume='';
	$tag='';
	$form_action="index.php?app=act&content&add";
}
?>
<script type="text/javascript">
	parent.hiddenDiv();
</script>
<form action='<?php echo $form_action;?>' method='POST'>
	<input type='hidden' name='upload_name' id='upload_name' value='<?php echo $thumb;?>' />
	<input type='hidden' name='old_channel_id' value='<?php echo $channel_id;?>' />
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
		<tr><th>*频道</th>
			<td>
				<select name='channel_id'>
					<?php
					if($channel_rs){
						echo '<option value="">请选择</option>';
						foreach($channel_rs as $rs){
							if($content_row['channel_id']==$rs['id']){
								$selected='selected';
							}else{
								$selected='';
							}
							echo '<option value='.$rs['id'].' '.$selected.'>'.get_channel_tip($rs['nodepath']).$rs['name'].'</option>';
						}
					}else{
						echo '<option value="">无频道</option>';
					}
					?>
				</select>
			</td></tr>
		<tr><th>*标题</th><td><input class="regular-text" type='text' name='title' value='<?php echo $title;?>' /></td></tr>
		<tr><th>关键字</th><td><input class="regular-text" type='text' name='keywords' value='<?php echo $keywords;?>' /></td></tr>
		<tr><th>描述</th><td><input class="regular-text" type='text' name='description' value='<?php echo $description;?>' /></td></tr>
		<tr><th>作者</th><td><input class="regular-text" type='text' name='user_name' value='<?php echo $user_name;?>' /></td></tr>
		<tr><th>来源</th><td><input class="regular-text" name='origin' type='text' value='<?php echo $origin;?>' /></td></tr>
		<tr><th>排序</th><td><input class="regular-text" name='order_num' type='text' value='<?php echo $order;?>' /></td></tr>
		<tr><th>标签</th><td><input class="regular-text" type='text' value='<?php echo $tag;?>' name='tag' /></td></tr>
		<tr><th valign="top">摘要</th><td><textarea class="textarea" style='width:480px;height:100px;margin-bottom:10px' name='resume'><?php echo $resume;?></textarea></td></tr>
		<tr><th valign="top">*内容</th><td style="line-height:1.5"><textarea style='width:630px;height:450px' name='content' id='content'><?php echo $content;?></textarea></td></tr>
		<tr>
			<th>缩略图</th>
			<td>
				<div id='img_priview' <?php echo $thumb ? '':'style=display:none';?>>
					<img id='priview_img' src='<?php echo '../'.$thumb;?>' /><br/><a id='priview_img_act' href="index.php?app=act&upload&del&src=<?php echo '../'.$thumb;?>" name="ajax" target="img_def">删除</a>
				</div>
				<iframe id='thumb_iframe' <?php echo $thumb ? 'style=display:none':'';?> width="100%" height=30 align=top allowTransparency="true" scrolling=no src='index.php?app=view&upload&upload&mod=thumb' frameborder=0></iframe>
			</td>
		</tr>
		<tr>
			<th></th>
			<td><input class="regular-btn" type='submit' value='<?php echo $submit_str;?>' /><input class="regular-btn" type='reset' value='重设' /></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
KE.show({id:'content',resizeMode:0});
</script>
