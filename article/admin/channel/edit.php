<?php
require("foundation/modules_channel.php");
$channel_id=get_argg('channel_id');
$channel_rs=select_spell($ArticleTable['article_channel'],'*','','nodepath','','getALL',1,'cms_channel/list/all');
if($channel_id){
	$channel_row=select_spell($ArticleTable['article_channel'],'*',"id=$channel_id",'','','getRow');
	$name=$channel_row['name'];
	$is_menu=$channel_row['is_menu'];
	$is_digg=$channel_row['is_digg'];
	$is_show=$channel_row['is_show'];
	$p_id=$channel_row['parentid'];
	$order=$channel_row['order_num'];
	$action='index.php?app=act&channel&edit&id='.$channel_row['id'];
	$submit_str='修改';
	$old_nodepath=$channel_row['nodepath'];
	$type_id=$channel_row['type_id'];
	$out_link=$channel_row['out_link'];
	$meta_descrip=$channel_row['meta_descrip'];
	$meta_title=$channel_row['meta_title'];
	$meta_key=$channel_row['meta_key'];
}else{
	$name='';
	$is_digg='';
	$is_menu=0;
	$is_show=0;
	$out_link='';
	$p_id='';
	$order='';
	$action='index.php?app=act&channel&add';
	$submit_str='添加';
	$old_nodepath='';
	$type_id=0;
	$meta_descrip='';
	$meta_title='';
	$meta_key='';
}
?>
<form action='<?php echo $action;?>' name='ajax' target='act_result'>
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
		<tr>
			<td width='90px'>上级菜单：</td>
			<td>
				<input type='hidden' value='<?php echo $old_nodepath;?>' name='old_nodepath' />
				<input type='hidden' value='<?php echo $p_id;?>' name='old_pid' />
				<select name='parentid'>
					<option value='0'> 无（顶级）</option>
					<?php
					foreach($channel_rs as $rs){
						if($p_id===$rs['id'])$selected='selected';else $selected='';
						echo "<option value=".$rs['id']." ".$selected.">".get_channel_tip($rs['nodepath']).$rs['name']."</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>频道名称：</td><td><input class="med-text" type='text' name='name' value='<?php echo $name;?>' /></td>
		</tr>
		<tr>
			<td>频道排列：</td>
			<td><input class="small-text" type='text' name='order_num' value='<?php echo $order;?>' /></td>
		</tr>
		<tr>
			<td>栏目类型：</td>
			<td>
				<input type='radio' name='type_id' value='0' <?php echo $channel_id ? 'disabled':'';?> <?php echo $type_id==0 ? 'checked':'';?> onclick="channel_div(0);" />频道列表
				<input type='radio' name='type_id' value='1' <?php echo $channel_id ? 'disabled':'';?> <?php echo $type_id==1 ? 'checked':'';?> onclick="channel_div(1);" />单网页
				<input type='radio' name='type_id' value='2' <?php echo $channel_id ? 'disabled':'';?> <?php echo $type_id==2 ? 'checked':'';?> onclick="channel_div(2);" />外部链接
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<div id='single_page' <?php echo $type_id==1 ? '':'style=display:none';?>>
					<input class="med-text" type='text' name='outlink_single' value='<?php echo $out_link;?>' />(英文网页文件名)<br/>
					<input class="med-text" type='text' name='meta_title' value='<?php echo $meta_title;?>' />(title信息)<br/>
					<input class="med-text" type='text' name='meta_key' value='<?php echo $meta_key;?>' />(key信息)<br/>
					<input class="med-text" type='text' name='meta_descrip' value='<?php echo $meta_descrip;?>' />(description信息)<br/>
				</div>
				
				<div id='outer_link' <?php echo $type_id==2 ? '':'style=display:none';?>>
					<input class="med-text" type='text' name='outlink' value='<?php echo $out_link;?>' />(外部链接地址)
				</div>
			</td>
		</tr>
		<tr>
			<td>导航显示：</td>
			<td><input type='radio' name='is_menu' value='0' <?php echo $is_menu==0 ? 'checked':'';?> />否<input type='radio' name='is_menu' value='1' <?php echo $is_menu==1 ? 'checked':'';?> />是</td>
		</tr>
		<tr>
			<td>首页展示：</td>
			<td><input type='radio' name='is_show' value='0' <?php echo $is_show==0 ? 'checked':'';?> />否<input type='radio' name='is_show' value='1' <?php echo $is_show==1 ? 'checked':'';?> />是</td>
		</tr>
		<tr>
			<td>允许投稿：</td>
			<td><input type='radio' name='is_digg' value='0' <?php echo $is_digg==0 ? 'checked':'';?> />否<input type='radio' name='is_digg' value='1' <?php echo $is_digg==1 ? 'checked':'';?> />是</td>
		</tr>
		<tr><td></td><td><input class="regular-btn" type='submit' value='<?php echo $submit_str;?>' /></td></tr>
	</table>
</form>
<script type="text/javascript">channel_div(<?php echo $type_id;?>);</script>