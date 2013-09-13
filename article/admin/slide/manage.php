<?php
$slide_rs=select_spell($ArticleTable['article_slide'],'*');
?>
<table class="msg_inbox" style="margin:15px auto;">
	<tr>
		<th>标题</th>
  <th>链接</th>
  <th>排序</th>
  <th>操作</th>
	</tr>
	<?php foreach($slide_rs as $rs){?>
	<tr>
		<td><?php echo $rs['title'];?></td>
		<td><?php echo $rs['link'];?></td>
		<td><?php echo $rs['order_num'];?></td>
		<td>
			<a href='index.php?app=view&slide&edit&id=<?php echo $rs['id'];?>' name='ajax' target='cms_content'>修改</a>
			<a href='index.php?app=act&slide&del&id=<?php echo $rs['id'];?>' name='ajax' target='cms_content'>删除</a>
  </td>
	</tr>
	<?php }?>
</table>
<a class="regular-btn" style="display:block" href='index.php?app=view&slide&edit' name='ajax' target='act_result'>添 加</a>