<?php
require("foundation/fpages_bar.php");
require("foundation/modules_channel.php");
$search=get_args('search');
$condition='';
$isNull=0;
$title='';
$user_name='';
$status='';
$min_date='';
$max_date='';
$channel_id='';
$tag='';
$page_num=intval(get_argg('page'));
if($search==1){
	$condition=' 1=1 ';
	$title=short_check(get_args('title'));
	$user_name=short_check(get_args('user_name'));
	$status=get_args('status');
	$tag=get_args('tag');
	$min_date=short_check(get_args('min_date'));
	$max_date=short_check(get_args('max_date'));
	$channel_id=intval(get_args('channel_id'));
	$condition.=$title ? " and title like '%$title%' ":"";
	$condition.=$user_name ? " and user_name='$user_name' ":"";
	$condition.=($status==='') ? "":" and status='$status' ";
	$condition.=$min_date ? " and date(addtime)<='$min_date' ":"";
	$condition.=$max_date ? " and date(addtime)>='$max_date' ":"";
	$condition.=$channel_id ? " and channel_id='$channel_id' ":"";
	$condition.=$tag ? " and tag like '$tag%' ":"";
	$isNull=(strlen($condition)>5) ? 1:0;
}

$content_rs=select_spell($ArticleTable['article_news'],'*',$condition,'id','desc');

$channel_rs=select_spell($ArticleTable['article_channel'],'*','','nodepath','','getALL',1,'art_channel/list/all');

if(empty($content_rs)){
	$isNull=1;
}
?>
<script type="text/javascript">parent.hiddenDiv();</script>
<form action='index.php?app=view&content&manage&search=1' name='ajax' target='cms_content'>
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
		<tr>
			<th> 标题 </th><td><input class="small-text" type='text' name='title' value='<?php echo $title;?>' /></td>
			<th> 作者 </th><td><input class="small-text" type='text' name='user_name' value='<?php echo $user_name;?>' /></td>
		</tr>
		<tr>
			<th> 日期 </th><td><input class="small-text" type='text' name='min_date' size='6' value='<?php echo $min_date;?>' onclick='calendar(this);' />~<input class="small-text" type='text' name='max_date' size='6' value='<?php echo $max_date;?>' onclick='calendar(this);' /></td>
			<th> 审核 </th><td><select name='status'><option value='' <?php echo $status=='' ? "selected":"";?>>不限</option><option value='0' <?php echo $status=='0' ? "selected":"";?>>待审</option><option value='1' <?php echo $status=='1' ? "selected":"";?>>已审</option></select></td>
		</tr>
		<tr>
			<th> 频道 </th>
			<td>
				<select name='channel_id'>
				<option value=0>不限</option>
				<?php foreach($channel_rs as $rs){
					$selected=($channel_id==$rs['id'])?'selected':'';
					echo '<option value='.$rs['id'].' '.$selected.'>'.get_channel_tip($rs['nodepath']).$rs['name'].'</option>';
				}?>
				</select>
			</td>
			<th> 标签 </th><td><input class="small-text" type='text' name='tag' value='<?php echo $tag;?>' /></td>
		</tr>
		<tr><th></th><td><input class="regular-btn" type='submit' value='搜索' /></td></tr>
	</table>
</form>
<hr />
<form action='#' id='content_list' name='ajax' target='act_result' method='post'>
	<table class="msg_inbox" style="margin:10px auto;">
		<tr><th></th><th width='180px'>标题</th><th width='60'>频道</th><th width='50px'>作者</th><th width='30px'>顶/踩</th><th width='25px'>评论</th><th width='25px'>审核</th><th width='25px'>推荐</th><th width='25px'>投稿</th><th width='25px'>排序</th><th width='25px'>时间</th><th width='25px'>操作</th></tr>
		<?php foreach($content_rs as $rs){?>
		<tr>
			<td><input type='checkbox' name='content_item[]' value='<?php echo $rs['id'];?>|<?php echo $rs['channel_id'];?>' /></td>
			<td><a href='index.php?app=view&show&id=<?php echo $rs['id'];?>' target='_blank'><?php echo $rs['title'];?></a></td>
			<td><?php echo $rs['channel_name'];?></td>
			<td><?php echo $rs['user_name'];?></td>
			<td><?php echo $rs['support'].'/'.$rs['against'];?></td>
			<td><?php echo $rs['comments'];?></td>
			<td><?php echo $rs['status'] ? "已审":"待审";?></td>
			<td><?php echo $rs['is_recom'] ? "是":"否";?></td>
			<td><?php echo $rs['is_digg'] ? "是":"否";?></td>
			<td><?php echo $rs['order_num'];?></td>
			<td><?php echo $rs['addtime'];?></td>
			<td>
				<a name='ajax' target='act_result' href='index.php?app=view&content&edit&id=<?php echo $rs['id'];?>'>修改</a>
			</td>
		</tr>
		<?php }?>
		<tr>
			<td colspan=12>
			<input class="regular-btn" type='button' value='全选' onclick=form_select('content_list',2) />
			<input class="regular-btn" type='button' value='审核' onclick=submit_form('content_list','index.php?app=act&content&batch&mod=auditing&method=1'); />
			<input class="regular-btn" type='button' value='取消审核' onclick=submit_form('content_list','index.php?app=act&content&batch&mod=auditing&method=0'); />
			<input class="regular-btn" type='button' value='推荐' onclick=submit_form('content_list','index.php?app=act&content&batch&mod=recom&method=1'); />
			<input class="regular-btn" type='button' value='取消推荐' onclick=submit_form('content_list','index.php?app=act&content&batch&mod=recom&method=0'); />
			<input class="regular-btn" type='button' value='删除' onclick=submit_form('content_list','index.php?app=act&content&del'); />
			</td>
		</tr>
	</table>
	<?php echo page_show($isNull,$page_num,$page_total);?>
</form>
<script type="text/javascript">autoAjax();</script>