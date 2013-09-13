<?php
require("foundation/modules_channel.php");
$channel_rs=select_spell($ArticleTable['article_channel'],'*','parentid=0','nodepath','','getALL',1,'art_channel/list/all/manage');
?>
<script type="text/javascript">
function tree_operate(id){
	var trees_div = document.getElementById('trees_'+id);
	var trees_tip = document.getElementById('trees_tip_'+id);
	if(trees_tip.innerHTML=='+'){
		trees_tip.innerHTML='-';
		trees_div.style.display='';
		var get_trees = new Ajax();
		if(trees_div.innerHTML==''){
			get_trees.getInfo("index.php?app=view&channel&tree","post","app","id="+id,function(c){
				if(c!=''){
					trees_div.innerHTML=c;
					autoAjax();
				}
			});
		}
	}else{
		trees_tip.innerHTML='+';
		trees_div.style.display='none';
	}
}
</script>
<div class="type_list">
	<ul>
	<?php foreach($channel_rs as $rs){?>
		<li>
			<?php echo get_channel_tip($rs['nodepath']);?>
			<a hidefocus="true" href="javascript:;" onclick="tree_operate(<?php echo $rs['id'];?>);">
				<em><span id='trees_tip_<?php echo $rs['id'];?>'>+</span></em>
				<?php echo $rs['name'];?>
			</a>
		  <span>
		  [首页显示优先级:<?php echo $rs['order_num'];?>]
		  &nbsp;<a href='index.php?app=view&channel&edit&channel_id=<?php echo $rs["id"]?>' name='ajax' target="act_result">修改</a>
          &nbsp;|&nbsp;<a href='index.php?app=act&channel&del&id=<?php echo $rs['id'];?>' name='ajax' target="act_result">删除</a>
		  </span>
		</li>
		<div id="trees_<?php echo $rs['id'];?>"></div>
	<?php }?>
	</ul>
</div>

<div class="type_btn">
	<a class="regular-btn" style="display:block" href='index.php?app=view&channel&edit' name='ajax' target='act_result'>添 加</a>
</div>
