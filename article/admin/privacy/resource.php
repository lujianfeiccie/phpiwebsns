<?php
$resource_rs=select_spell($ArticleTable['article_resource']);
?>
<table class="msg_inbox" style="margin:15px auto;">
	<tr>
		<th>权限ID</th><th>模块名称</th><th>权限名称</th><th>操作</th>
	</tr>
	<?php foreach($resource_rs as $rs){?>
	<tr>
		<td><?php echo $rs['resource_id'];?></td>
		<td><?php echo $rs['modules_name'];?></td>
		<td><?php echo $rs['name'];?></td>
		<td>
			<a href='javascript:edit("edit_form","id=<?php echo $rs['resource_id']?>","<?php echo $rs['resource_id'];?>","<?php echo $rs['modules_name'];?>","<?php echo $rs['name'];?>")'>修改</a>|
			<a href='index.php?app=act&privacy&del&type=resource&id=<?php echo $rs["resource_id"];?>' name='ajax' target='act_result'>删除</a>
		</td>
	</tr>
	<?php }?>
</table>
<div class="rs_head"><span><a href=javascript:show_hidden("add_resource","")>添加权限</a> -
        <a href='index.php?app=view&privacy&group' name='ajax' target='act_result'>组列表</a></span></div>
<form id='add_resource' action='index.php?app=act&privacy&add&type=resource' name='ajax' target='act_result' style='display:none'>
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
  <tr><th>资源ID：</th><td><input class="small-text" type='text' name='resource_id' /></td></tr>
  <tr><th>模块名称：</th><td><input class="small-text" type='text' name='modules_name' /></td></tr>
	<tr><th>资源名称：</th><td><input class="small-text" type='text' name='name' /></td></tr>
	<tr><td></td><td><input class="regular-btn" type='submit' value='添加' /></td></tr></table>
</form>

<form id='edit_form' action='index.php?app=act&privacy&edit&type=resource' name='ajax' target='act_result' style='display:none'>
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
	<tr><th>资源ID：</th><td><input class="small-text" id='edit_value' type='text' name='nid' /></td></tr>
	<tr><th>模块名称：</th><td><input class="small-text" type='text' name='modules_name' /></td></tr>
	<tr><th>资源名称：</th><td><input class="small-text" type='text' name='name' /></td></tr>
	<tr><td></td><td><input class="regular-btn" type='submit' value='修改' /></td></tr></table>
</form>