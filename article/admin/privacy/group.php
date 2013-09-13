<?php
$group_list=intval(get_argg('group_list'));
$group_rs=select_spell($ArticleTable['article_group']);
?>
<?php if($group_list==''){?>
<table class="msg_inbox" style="margin:15px auto">
	<tr>
		<th>管理组名</th><th>操作</th>
	</tr>
	<?php foreach($group_rs as $rs){?>
	<tr>
		<td><?php echo $rs['name'];?></td>
		<td>
			<a href='javascript:edit("edit_form","id=<?php echo $rs['id'];?>","<?php echo $rs['name'];?>")' onclick=show_hidden('','add_group');show_hidden('','add_resource');parent.scrollTo(0,parent.document.body.scrollHeight);>修改</a>|
			<a href='index.php?app=view&privacy&allot_pri&id=<?php echo $rs['id'];?>' name='ajax' target='act_result'>分配权限</a>|
			<a href='index.php?app=act&privacy&del&type=group&id=<?php echo $rs["id"];?>' name='ajax' target='act_result'>删除</a>
		</td>
	</tr>
	<?php }?>
</table>

<div class="rs_head">
	<span>
		<a href=javascript:show_hidden('add_group','add_resource');show_hidden('','edit_form');>添加组</a> -
    <a href=javascript:show_hidden('add_resource','add_group');show_hidden('','edit_form');>添加权限</a> -
    <a href='index.php?app=view&privacy&resource' name='ajax' target='act_result'>权限列表</a> -
    <a href='index.php?app=view&privacy&person' name='ajax' target='cms_content'>角色管理</a>
  </span>
</div>

<form id='add_group' action='index.php?app=act&privacy&add&type=group' name='ajax' target='act_result' style='display:none'>
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
		<tr>
			<th style="width:120px">管理组名称：</th>
			<td><input class="small-text" type='text' name='name' /></td>
		</tr>
		<tr>
			<th></th>
			<td><input class="regular-btn" type='submit' value='添加' /></td>
		</tr>
	</table>
</form>

<form id='add_resource' action='index.php?app=act&privacy&add&type=resource' name='ajax' target='act_result' style='display:none'>
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
    <tr><th>资源ID：</th><td><input class="small-text" type='text' name='resource_id' /></td></tr>
    <tr><th>模块名称：</th><td><input class="small-text" type='text' name='modules_name' /></td></tr>
    <tr><th>资源名称：</th><td><input class="small-text" type='text' name='name' /></td></tr>
    <tr><td></td><td><input class="regular-btn" type='submit' value='添加' /></td></tr>
  </table>
</form>

<form id='edit_form' action='index.php?app=act&privacy&edit&type=group' name='ajax' target='act_result' style='display:none'>
	管理组名：<input class="small-text" id='edit_value' type='text' name='name' value='' />
	<input class="regular-btn" type='submit' value='修改' />
</form>
<?php }else{
	$id=get_argg('id');
	$aid=get_argg('aid');
	echo '<select onchange=ajax_send("index.php?app=act&privacy&edit&type=person&aid='.$aid.'&id="+this.value,"name="+this.options[selectedIndex].text,"group_list_'.$aid.'");>';
	echo '<option value=0>请选择</option>';
	foreach($group_rs as $rs){
		if($id==$rs['id']){
			$selected="selected";
		}else{
			$selected="";
		}
		echo '<option value='.$rs['id'].' '.$selected.'>'.$rs['name'].'</option>';
	}
	echo '</select>';
}?>