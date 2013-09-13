<?php
require("foundation/fpages_bar.php");
$page_num=intval(get_argg('page'));
$tag_rs=array();
$tag_rs=select_spell($ArticleTable['article_tag'],'*');
$isNull=0;
if(empty($tag_rs)){
	$isNull=1;
}
?>
<form id="tag_form" action="#" method="post" name="ajax" target="act_result">
    <table class="msg_inbox" style="margin:15px auto;">
        <tr>
          <th width='*'>标签名称</th>
          <th width='90'>热门标签</th>
          <th width='90'>使用次数</th>
          <th width='200px'>操作</th>
        </tr>
        <?php foreach($tag_rs as $rs){?>
        <tr>
	        <td>
	          <div id="show_name_<?php echo $rs['id'];?>"><?php echo $rs['name'];?></div>
	          <div id="edit_name_<?php echo $rs['id'];?>" style="display:none">
	          	<input type="text" class="small-text" id="edit_name" name="edit_name" maxlength="10" value="<?php echo $rs['name'];?>" />
	          </div>
	        </td>
	        <td>
	        	<?php echo $rs['hot'] ? '是':'否';?>
	        </td>
	        <td>
	        	<?php echo $rs['count'];?>
	        </td>
	        <td>
	          <div id="button_<?php echo $rs['id'];?>">
	            <a href="javascript:show_hidden('action_<?php echo $rs['id'];?>','button_<?php echo $rs['id'];?>');show_hidden('edit_name_<?php echo $rs['id'];?>','show_name_<?php echo $rs['id'];?>');">修改名称</a> |
	            <a href="index.php?app=act&tag&action&type=recommend&id=<?php echo $rs['id'];?>&hot=<?php echo abs($rs['hot']-1);?>" name="ajax" target="cms_content"><?php echo $rs['hot'] ? '取消hot':'设置hot';?></a> |
	            <a href="index.php?app=act&tag&action&type=del&id=<?php echo $rs['id'];?>" name="ajax" target="act_result">删除</a>
	          </div>
	          <div id="action_<?php echo $rs['id'];?>" style="display:none">
	            <input type='submit' onclick="$('tag_form').action='index.php?app=act&tag&action&type=edit&id=<?php echo $rs['id'];?>';" class='regular-btn' value='确认' />
	            <input type='button' onclick="javascript:show_hidden('button_<?php echo $rs['id'];?>','action_<?php echo $rs['id'];?>');show_hidden('show_name_<?php echo $rs['id'];?>','edit_name_<?php echo $rs['id'];?>');$('edit_name')" class='regular-btn' value='取消' />
	      		</div>
	        </td>
        </tr>
        <?php }?>
        <tr>
            <td>
                <div id="tag" style="display:none">
                	<input type='text' class="small-text" id='add_tag' name='add_tag' maxlength='15' value='' />
                </div>
            </td>
            <td colspan=7>
                <div id="add_button">
                	<input type="button" onclick="show_hidden('tag','add_button');show_hidden('add_action','');" class="regular-btn" value="添加" />
                </div>
                <div id="add_action" style="display:none">
                	<input type='submit' onclick="$('tag_form').action='index.php?app=act&tag&action&type=add';" class="regular-btn" value='确认' />
                	<input type='button' onclick="show_hidden('add_button','tag');show_hidden('','add_action');$('add_tag').value='';" class="regular-btn" value='取消' />
                </div>
            </td>
        </tr>
        <tr><td colspan=7><?php echo page_show($isNull,$page_num,$page_total);?></td></tr>
    </table>
</form>