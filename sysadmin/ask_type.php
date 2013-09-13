<?php
require("session_check.php");
require("../api/base_support.php");
//变量区

$t_ask_type=$tablePreStr."ask_type";

$dbo = new dbex;
dbtarget('w',$dbServs);

$sql="select * from $t_ask_type order by order_num asc";

$ask_type_rs=$dbo->getAll($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript'>
function manager_sort(sort_id,type_value){
	if(type_value=="add"){
		var sort_name=document.getElementById("add_value").value;
		var sort_order=document.getElementById("add_order_num").value;
	}
	if(type_value=="change"){
		var sort_name=document.getElementById("input_title_"+sort_id).value;
		var sort_order=document.getElementById("input_num_"+sort_id).value;
	}
	var manager_sort=new Ajax();
	manager_sort.getInfo("ask_type.action.php?type_value="+type_value+"&sort_id="+sort_id,"post","app","sort_name="+sort_name+"&sort_order="+sort_order,function(c){window.location.reload();});
}

function add_sort(show_1,show_2,hidden_1,hidden_2){
	document.getElementById(show_1).style.display="none";
	document.getElementById(show_2).style.display="";
	document.getElementById(hidden_1).style.display="";
	document.getElementById(hidden_2).style.display="";
	}

function cancel_sort(show_1,show_2,hidden_1,hidden_2){
	document.getElementById("add_value").value="";
	document.getElementById(show_1).style.display="";
	document.getElementById(show_2).style.display="none";
	document.getElementById(hidden_1).style.display="none";
	document.getElementById(hidden_2).style.display="none";
	}

function change_sort(show_1,show_2,show_3,hidden_1,hidden_2,hidden_3){
	document.getElementById(show_1).style.display="none";
	document.getElementById(show_2).style.display="none";
	document.getElementById(show_3).style.display="none";
	document.getElementById(hidden_1).style.display="";
	document.getElementById(hidden_2).style.display="";
	document.getElementById(hidden_3).style.display="";
	}

function cancel_change(show_1,show_2,show_3,hidden_1,hidden_2,hidden_3){
	document.getElementById(show_1).style.display="";
	document.getElementById(show_2).style.display="";
	document.getElementById(show_3).style.display="";
	document.getElementById(hidden_1).style.display="none";
	document.getElementById(hidden_2).style.display="none";
	document.getElementById(hidden_3).style.display="none";
	}

</script>
</head>
<body>
<div id="maincontent">
  <div class="wrap">
	<div class="crumbs">当前位置 &gt;&gt; <a href="left.php?part_id=user">用户管理</a> &gt;&gt; <a href="ask_type.php">问吧分类</a></div>
	<hr />
	<div class="infobox">
	  <h3>问吧问题分类管理</h3>
			<div class="content">
				<table class='list_table <?php echo $isset_data;?>'>
					<thead><tr>
	            <th width="100">排序</th>
	            <th>分类名称</th>
	            <th style="text-align:center">操作</th>		      
				  </tr></thead>
				<?php foreach($ask_type_rs as $rs){?>
					<tr>
						<td>
							<div id="show_num_<?php echo $rs['id'];?>"><?php echo $rs['order_num'];?></div>
							<div id="order_by_<?php echo $rs['id'];?>" style="display:none">
							  <input type="text" class="small-text" id="input_num_<?php echo $rs['id'];?>" maxlength="15" value="<?php echo $rs['order_num'];?>" />
							</div>
						</td>
						<td>
							<div id="show_title_<?php echo $rs['id'];?>"><?php echo $rs['name'];?></div>
							<div id="title_<?php echo $rs['id'];?>" style="display:none">
								<input type="text" class="small-text" id="input_title_<?php echo $rs['id'];?>" maxlength="30" value="<?php echo $rs['name'];?>" />
							</div>
						</td>
						<td  style="text-align:center">
							<div id="button_<?php echo $rs['id'];?>">
								<a href="javascript:change_sort('show_num_<?php echo $rs['id'];?>','show_title_<?php echo $rs['id'];?>','button_<?php echo $rs['id'];?>','order_by_<?php echo $rs['id'];?>','title_<?php echo $rs['id'];?>','action_<?php echo $rs['id'];?>')">修改</a> |
							  <a href="javascript:manager_sort('<?php echo $rs["id"];?>','del')" onclick='return confirm("确认删除");'>删除</a>
							</div>
							<div id="action_<?php echo $rs['id'];?>" style="display:none">
							  <input type='button' onclick='manager_sort("<?php echo $rs['id'];?>","change")' class='regular-button' value='确认' />
							  <input type='button' onclick=cancel_change('show_num_<?php echo $rs['id'];?>','show_title_<?php echo $rs['id'];?>','button_<?php echo $rs['id'];?>','order_by_<?php echo $rs['id'];?>','title_<?php echo $rs['id'];?>','action_<?php echo $rs['id'];?>') class='regular-button' value='取消' />
							</div>
						</td>
					</tr>
				<?php }?>
					<tr height="40px">
						<td>
						  <div id="order_num" style="display:none">
							<input type='text' class="small-text" id='add_order_num' name='add_order_num' maxlength='15' value='' />
						  </div>
						</td>
						<td>
							<div id="sort_input" style="display:none">
								<input type='text' class="small-text" id='add_value' name='add_value' maxlength='15' value='' />
							</div>
						</td>
						<td  style=" text-align:center"><div id="add_button"><input type="button" onclick='add_sort("add_button","order_num","sort_input","add_action");' class="regular-button" value="添加" /></div>
								<div id="add_action" style="display:none">
								  <input type='button' onclick=manager_sort('0','add') class="regular-button" value='确认' />
								  <input type='button' onclick='cancel_sort("add_button","order_num","sort_input","add_action");' class="regular-button" value='取消' />
							 </div>
						</td>
					</tr>
				</table>
			</div>
	</div>
	</div>
</div>
</body>
</html>