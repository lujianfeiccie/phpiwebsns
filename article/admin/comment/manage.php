<?php
require("foundation/fpages_bar.php");
$comments_rs=select_spell($ArticleTable['article_comment']);
$isNull=0;
if(empty($comments_rs)){
	$isNull=1;
}
?>
<form action='#' id='content_list' name='ajax' target='act_result' method='post'>
	<table class="msg_inbox" style="margin:15px auto">
		<tr>
			<th></th><th>用户名</th><th>ip</th><th>时间</th><th>内容</th>
		</tr>
		<?php foreach($comments_rs as $rs){?>
		<tr>
			<td><input type='checkbox' name='content_item[]' value='<?php echo $rs['id'];?>|<?php echo $rs['content_id'];?>' /></td>
			<td><?php echo $rs['user_name'];?></td>
			<td><?php echo $rs['user_ip'];?></td>
			<td><?php echo $rs['addtime'];?></td>
			<td><?php echo sub_str($rs['content'],50);?></td>
		</tr>
		<?php }?>
		<tr>
			<td colspan=5>
			<input class="regular-btn" type='button' value='全选' onclick=form_select('content_list',2) />
			<input class="regular-btn" type='button' value='删除' onclick=submit_form('content_list','index.php?app=act&comment&del'); />
			</td>
		</tr>
	</table>
	<?php echo page_show($isNull,$page_num,$page_total);?>
</form>