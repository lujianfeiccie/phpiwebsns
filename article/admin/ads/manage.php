<?php
$ads_rs=select_spell($ArticleTable['article_ads'],'*');
?>
<table class="msg_inbox" style="margin:15px auto;">
	<tr>
		<th>名称</th><th>类型</th><th>宽×高</th><th>广告代码</th><th>操作</th>
	</tr>
	<?php foreach($ads_rs as $rs){?>
	<tr>
		<td><?php echo $rs['title'];?></td>
		<td>
			<?php if($rs['type_id']==0){
				echo '图片';
			}else if($rs['type_id']==1){
				echo 'flash';
			}else if($rs['type_id']==2){
				echo '文字';
			}else{
				echo '文字链接';
			}
			?>
		</td>
		<td><?php echo $rs['width'];?>×<?php echo $rs['height'];?></td>
		<td><input type='text' class='med-text' value='<script type="text/javascript" src="<?php echo 'ads/ad_'.$rs['id'].'.js';?>"></script>' /></td>
		<td>
			<a href='index.php?app=view&ads&edit&id=<?php echo $rs['id'];?>' name='ajax' target='cms_content'>修改</a>
			<a href='index.php?app=act&ads&del&id=<?php echo $rs['id'];?>' name='ajax' target='cms_content'>删除</a>
		</td>
	</tr>
	<?php }?>
</table>
<a class="regular-btn" style="display:block" href='index.php?app=view&ads&edit' name='ajax' target='act_result'>添 加</a>