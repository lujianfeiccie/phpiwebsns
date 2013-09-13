<?php
$user_id=get_sess_userid();
$pals_rs=api_proxy("pals_self_by_uid","*",$user_id);
?>
<table border="0" cellpadding="2" cellspacing="1" class="msg_inbox" style="margin:15px auto;">
	<tr><th>头像</th><th>名字</th><th>性别</th><th>操作</th></tr>
	<?php foreach($pals_rs as $rs){?>
	<tr>
		<td><div class="avatar"><a href='../home.php?h=<?php echo $rs['pals_id'];?>'><img src=../<?php echo $rs['pals_ico'];?> /></a></div></td>
		<td><?php echo $rs['pals_name'];?></td>
		<td><?php echo $rs['pals_sex'] ? '男':'女';?></td>
		<td><a href='index.php?app=act&privacy&add&type=person&pals_id=<?php echo $rs['pals_id'];?>' name='ajax' target='act_result'>添加</a></td>
	</tr>
	<?php }?>
</table>