<?php
$manager_rs=select_spell($ArticleTable['article_admin']);
?>

<table class="msg_inbox" style="margin:15px auto;">
	<tr>
		<th>头像</th><th>名字</th><th>性别</th><th>管理组名</th><th>操作</th>
	</tr>
	<?php foreach($manager_rs as $rs){?>
	<tr>
		<td><div class="avatar"><img src="../<?php echo $rs['user_ico'];?>" /></div></td>
		<td><?php echo $rs['user_name'];?></td>
		<td><?php echo $rs['user_sex']?'男':'女';?></td>
		<td><div id='group_list_<?php echo $rs["id"];?>'><?php echo $rs['gname'] ? $rs['gname'] : '未分组';?></div></td>
		<td>
			<a href='index.php?app=view&privacy&group&group_list=1&aid=<?php echo $rs['id'];?>&id=<?php echo $rs['gid'];?>' name='ajax' target='group_list_<?php echo $rs['id'];?>'>修改</a>&nbsp;&nbsp;
			<a href='index.php?app=act&privacy&del&type=person&id=<?php echo $rs['user_id']?>' name='ajax' target='act_result'>删除</a>
		</td>
	</tr>
	<?php }?>
</table>	<a class="invite-btn" style="display:block" href='index.php?app=view&privacy&add_person' name='ajax' target='act_result'>添加管理员</a>

