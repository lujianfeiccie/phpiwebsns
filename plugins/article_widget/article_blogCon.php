<?php
include(dirname(__file__)."/../includes.php");
include(dirname(__file__)."/table_prefix.php");
require_once("article/config/config.php");

if($article_diggPower){
	//取得类别节点缩进
	function article_get_channel_tip($nodepath){
		$nodenum = (strlen($nodepath)/3)-1;
		$node = "";
		for($i=0;$i<$nodenum;$i++){
			$node .= "&nbsp;&nbsp;";
		}
		return $node;
	}

	$dbo=new dbex;
	dbplugin('w');
	$t_article_channel=$ArticleTable['article_channel'];
	$sql="select * from $t_article_channel where is_digg=1 order by nodepath";
	$sort_rs=$dbo->getRs($sql);
?>
	<tr>
		<th>投稿：</th>
		<td style="height:30px">
			<input type='hidden' name='plugin_submit_file[]' value='<?php echo self_url(__FILE__)."digg_action.php";?>' />
			<select name='article_channel_id'>
				<option value='0'>请选择</option>
			<?php
				foreach($sort_rs as $rs){
					echo '<option value='.$rs['id'].'>'.article_get_channel_tip($rs['nodepath']).$rs['name'].'</option>';
				}
			?>
			</select>
			(文章类)
		</td>
	</tr>

<?php }?>