<?php
require("foundation/modules_channel.php");
$id = intval(get_argp('id'));
$channel_rs = select_spell($ArticleTable['article_channel'],'*','parentid='.$id,'nodepath','','getALL',1,'art_channel/list/all/tree');
foreach($channel_rs as $rs){
?>
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
<?php } ?>