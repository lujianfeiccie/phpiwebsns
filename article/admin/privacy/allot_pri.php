<?php
$id=get_argg('id');
$group_resource=select_spell($ArticleTable['article_group'],'rights',"id=$id",'','','getRow');
$resource_rs=select_spell($ArticleTable['article_resource']);
?>
<form id='allot_resource' action='index.php?app=act&privacy&allot_pri&id=<?php echo $id;?>' name='ajax' target='act_result'>
	<table class="msg_inbox" style="margin:15px auto;">
		<tr>
			<td>
				<ul>
				<?php foreach($resource_rs as $rs){
					$checked = strpos(','.$group_resource['rights'],$rs['resource_id'])?'checked':'';
					?>
					<li><input type='checkbox' value='<?php echo $rs["resource_id"];?>' name='resource_name[]' <?php echo $checked;?> /><?php echo $rs['name'];?></li>
				<?php }?>
				</ul>
			</td>
		</tr>
		<tr>
			<td>
				<input type='button' class="regular-btn" value='全选/反选' onclick="form_select('allot_resource',2);" />
				<input type='submit' class="regular-btn" value='分配' />
			</td>
		</tr>
	</table>
</form>