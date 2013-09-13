<form action='index.php?app=act&system&edit' name='ajax' target='cms_content'>
	<table border="0" cellpadding="2" cellspacing="1" class="form_table">
		<tr>
			<th>开启投稿</th>
			<td><input type='radio' name='diggPower' value=1 <?php echo $article_diggPower ? 'checked':'';?> />是 <input type='radio' name='diggPower' value=0 <?php echo $article_diggPower ? '':'checked';?> />否</td>
		</tr>
		<tr>
			<th>评论权限</th>
			<td>
				<input type='radio' value='0' name='comType' <?php echo $comType==0 ? 'checked':'';?> />游客
				<input type='radio' value='1' name='comType' <?php echo $comType==1 ? 'checked':'';?> />会员
				<input type='radio' value='2' name='comType' <?php echo $comType==2 ? 'checked':'';?> />禁止
			</td>
		</tr>
		<tr>
			<th>参与权限</th>
			<td>
				<input type='radio' value='0' name='diggType' <?php echo $comType==0 ? 'checked':'';?> />游客
				<input type='radio' value='1' name='diggType' <?php echo $comType==1 ? 'checked':'';?> />会员
				<input type='radio' value='2' name='diggType' <?php echo $comType==2 ? 'checked':'';?> />禁止
			</td>
		</tr>
		<tr>
			<th>模板编译</th>
			<td>
				<div id='comp_info'>
					<input type='radio' onclick="$('action_comp').href=$('action_comp').href+'&compType='+this.value;" value='server' name='compType' <?php echo $compType=='server' ? 'checked':'';?> />服务模式
					<input type='radio' onclick="$('action_comp').href=$('action_comp').href+'&compType='+this.value;" value='debug' name='compType' <?php echo $compType=='debug' ? 'checked':'';?> />调试模式
					<a id='action_comp' href='index.php?app=act&system&compile&compType=<?php echo $compType;?>' name='ajax' hidefocus="true" target='comp_info'>开始模板编译</a>
				</div>
			</td>
		</tr>
		<tr>
			<th>更新首页</th>
			<td><span id="static_area"><a href='index.php?app=act&system&static'>开始生产静态</a></span></td>
		</tr>
		<tr>
			<th>站点标题</th>
			<td><input class="med-text" type='text' name='webTitle' value='<?php echo $webTitle;?>' /></td>
		</tr>
		<tr>
			<th>站点关键字</th>
			<td><input class="med-text" type='text' name='webKeys' value='<?php echo $webKeys;?>' /></td>
		</tr>
		<tr>
			<th>站点描述</th>
			<td><input  class="med-text"type='text' name='webDesc' value='<?php echo $webDesc;?>' /></td>
		</tr>
		<tr>
			<th>站点作者</th>
			<td><input class="med-text" type='text' name='webAuthor' value='<?php echo $webAuthor;?>' /></td>
		</tr>

		<tr><td></td><td><input class="regular-btn" type='submit' value='保存' /></td></tr>
	</table>
</form>
<script type="text/javascript">autoAjax();</script>