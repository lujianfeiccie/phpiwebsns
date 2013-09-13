<?php
	require("session_check.php");	
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	//语言包引入
	$m_langpackage=new modulelp;	
	$ad_langpackage=new adminmenulp;
	$bp_langpackage=new back_publiclp;
	require("../foundation/fback_search.php");

	//数据表定义区
	$t_table=$tablePreStr."ask";
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	
	//变量区
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;
		
	$eq_array=array('user_id','user_name');
	$like_array=array('title');
	$date_array=array("add_time");
	$num_array=array("reply_num","reward");
	$sql=spell_sql($t_table,$eq_array,$like_array,$date_array,$num_array,$c_orderby,$c_ordersc);
	
	//设置分页
	$dbo->setPages($c_perpage,$page_num);
	
	//取得数据
	$ask_rs=$dbo->getRs($sql);
	
	//分页总数
	$page_total=$dbo->totalPage;

	
	//按字段排序
	$o_def='';$o_add_time='';$o_reward='';$o_reply_num='';
	if(get_argg('order_by')==''||get_argg('order_by')=="ask_id"){$o_def="selected";}
	if(get_argg('order_by')=="add_time"){$o_add_time="selected";}
	if(get_argg('order_by')=="reward"){$o_reward="selected";}
	if(get_argg('order_by')=="reply_num"){$o_reply_num="selected";}
		
	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($ask_rs)){
		$isset_data="content_none";
		$none_data="";
		$isNull=1;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
</head>
<script type='text/javascript' src='../servtools/calendar.js'></script>
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript'>
function del_ask(ask_id,sendor_id){
	var del_blog=new Ajax();
	del_blog.getInfo("ask_del.action.php","GET","app","ask_id="+ask_id+"&sendor_id="+sendor_id,"operate_"+ask_id); 
}
	
function check_form(){
	var min_date_line=document.getElementById("add_time1").value;
	var max_date_line=document.getElementById("add_time2").value;
	var time_format=/\d{4}\-\d{2}\-\d{2}/;
	if(min_date_line){
		if(!time_format.test(min_date_line)){
			alert("<?php echo $m_langpackage->m_date_wrong;?>");
			return false;
			}
	}
	if(max_date_line){
		if(!time_format.test(max_date_line)){
			alert("<?php echo $m_langpackage->m_date_wrong;?>");
			return false;
			}
		}
}

function checkAll(obj){
	var form_obj=document.getElementById(obj);
	var input_obj=form_obj.getElementsByTagName('input');
	for(i=0;i<input_obj.length;i++){
		if(input_obj[i].type=='checkbox'){
			if(input_obj[i].checked==true){
				input_obj[i].checked='';
			}else{
				input_obj[i].checked='checked';
			}
		}
	}
}
</script>
<body>
<div id="maincontent">
	<div class="wrap">
    <div class="crumbs"><?php echo $ad_langpackage->ad_location;?> &gt;&gt; <a href="javascript:void(0);"><?php echo $ad_langpackage->ad_manage_mod;?></a> &gt;&gt; <a href="ask_list.php?order_by=ask_id&order_sc=desc">问吧管理</a></div>
    <hr />
    <div class="infobox">
      <h3><?php echo $m_langpackage->m_check_condition;?></h3>
      <div class="content">
				<form action="" method="GET" onSubmit="return check_form()">
				<TABLE class="form-table">
				<TR>
				<th width=12%>提问者UID</th>
				<TD width=30%><INPUT name="user_id" class="small-text" type='text' value='<?php echo get_argg('user_id');?>' /></TD>
				<th width=12%><?php echo $m_langpackage->m_author_name;?></th>
				<TD width=*><INPUT name="user_name" class="small-text" type='text' value='<?php echo get_argg('user_name');?>' /></TD></TR>
				<TR>
				<th>标题<font color=red>*</font></th>
				<TD><INPUT name="title" class="small-text" type='text' value='<?php echo get_argg('title');?>' /></TD>
				<th>提问时间</th>
				<TD>
				<INPUT class="small-text" type='text' AUTOCOMPLETE=off onclick='calendar(this);' name='add_time1' id='add_time1' value='<?php echo get_argg('add_time1');?>' /> ~ <INPUT type='text' class="small-text" name='add_time2' AUTOCOMPLETE=off onclick='calendar(this);' id='add_time2' value='<?php echo get_argg('add_time2');?>' /> (YYYY-MM-DD) </TD>
				</TR>
				<TR>
				<th>回答数</th>
				<TD colSpan=3>
				<INPUT class="small-text" type='text' name='reply_num1' value='<?php echo get_argg('reply_num1');?>' /> ~ <INPUT class="small-text" type='text' name='reply_num2' value='<?php echo get_argg('reply_num2');?>' /> </TD></TR>			
				<TR>
				<th>点击数</th>
				<TD colSpan=3>
				<INPUT class="small-text" name='view_num1' type='text' value='<?php echo get_argg('view_num1');?>'  /> ~ <INPUT class="small-text" type='text' name='view_num2' value='<?php echo get_argg('view_num2');?>' /> </TD></TR>			
				<TR>
				<th>结果排序</th>
				<TD colSpan=3>
				<SELECT name='order_by'> 
				<OPTION value="ask_id" <?php echo $o_def;?>>默认排序</OPTION>
				<OPTION value='add_time' <?php echo $o_add_time;?>>提问时间</OPTION> 
				<OPTION value='reward' <?php echo $o_reward;?>>悬赏积分</OPTION> 
				<OPTION value='reply_num' <?php echo $o_reply_num;?>>回答数</OPTION>
				</SELECT>
				<?php echo order_sc();?>
				<?php echo perpage();?>
				</TD>
				</TR>
				<tr><td colspan=2><?php echo $m_langpackage->m_red;?></td></tr>
				<tr><td colspan=2><INPUT class="regular-button" type="submit" value="<?php echo $m_langpackage->m_search;?>" /></td></tr>
				</TABLE>
				</form>
			</div>
		</div>

<form method="post" action="ask_del.action.php" id='data_list'>
<div class="infobox">
	<h3>问题列表</h3>
	<div class="content">
		<table class='list_table <?php echo $isset_data;?>'>
			<thead><tr><th width="20px">&nbsp;</th><th width='35%'> 标题 </th><th style="text-align:center"> 提问者 </th><th style="text-align:center"> 类别 </th><th style="text-align:center"> 提问时间 </th><th style="text-align:center"> 回答数 </th><th style="text-align:center"> 悬赏积分 </th><th style="text-align:center"> 状态 </th><th style="text-align:center"> 操作 </th></tr></thead>
	<?php 
			foreach($ask_rs as $rs){
	?>
			<tr>
        		<td><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $rs['ask_id'];?>" /></td>
				<td><a href='../modules.php?h=<?php echo $rs['user_id'];?>&app=ask_show&id=<?php echo $rs['ask_id'];?>' target='_blank'><?php echo $rs['title'];?></a></td>
				<td style="text-align:center"><?php echo $rs['user_name'];?></td>
				<td style="text-align:center"><?php echo $rs['type_name'];?></td>
				<td style="text-align:center"><?php echo $rs['add_time'];?></td>
				<td style="text-align:center"><?php echo $rs['reply_num'];?></td>
				<td style="text-align:center"><?php echo $rs['reward'];?></td>
				<td style="text-align:center"><?php echo $rs['status']==0?'待解决':'已解决';?></td>
				<td style="text-align:center">
					<div id="operate_<?php echo $rs['ask_id'];?>">
						<a href='javascript:del_ask(<?php echo $rs["ask_id"];?>,<?php echo $rs["user_id"];?>);' onclick='return confirm("<?php echo $m_langpackage->m_ask_del;?>");' title='<?php echo $m_langpackage->m_del;?>'><img src='images/del.gif' /></a>
					</div>
				</td>
			</tr>
	<?php 
			}
			?>
      <tr><td colspan="8"><input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $bp_langpackage->bp_select_deselect; ?>" />
      			<input class="regular-button" type="submit" id="RemoveAll" name="RemoveAll" value="<?php echo $bp_langpackage->bp_bulk_delete; ?>" /></td></tr>
		</table>
<?php page_show($isNull,$page_num,$page_total);?>
<div class='guide_info <?php echo $none_data;?>'><?php echo $m_langpackage->m_none_data;?></div>
</div>
</div>
</div>
</div>
</form>
</body>
</html>