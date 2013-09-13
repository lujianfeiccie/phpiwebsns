<?php
	//引入公共文件
	require("session_check.php");
	require("../foundation/fpages_bar.php");
	require("../foundation/fsqlseletiem_set.php");
	require("../foundation/module_event.php");
	//语言包引入
	$eb_langpackage = new event_backstagelp;
	
	//引入公共文件
	require("../foundation/fback_search.php");
	require("../api/base_support.php");
	
	//$is_check=check_rights("c13");
	//if(!$is_check){
	//	echo "";
	//	exit;
	//}
	
	//数据操作初始化
	$t_event=$tablePreStr."event";
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//当前页面参数
	$page_num=trim(get_argg('page'));
	//变量区
	$c_active=get_argg('active');
	$c_province=get_argg('s1');
	$c_city=get_argg('s2');
	$c_start_time=get_argg('start_time');
	$c_end_time=get_argg('end_time');
	
	$c_orderby=short_check(get_argg('order_by'));
	$c_ordersc=short_check(get_argg('order_sc'));
	$c_perpage=get_argg('perpage') ? intval(short_check(get_argg('perpage'))) : 20;
	
	$eq_array=array('event_id','type_id','public','grade');
	$like_array=array('user_name','title');
	$date_array=array();
	$num_array=array('limit_num');
	$sql=spell_sql($t_event,$eq_array,$like_array,$date_array,$num_array,'','');
	
	//判断活动是否结束
	if($c_active!=''){
		if($c_active==0){
			$sql.=" and ($t_event.end_time < '".constant('NOWTIME')."') ";
		}
		if($sql==1){
			$num_array.=" and ($t_event.end_time >= '".constant('NOWTIME')."') ";
		}
	}
	//判断活动省市
	if($c_province!=''){
		$sql.=" and ($t_event.province = '$c_province') ";
	}
	if($c_city!=''){
		$sql.=" and ($t_event.city = '$c_city') ";
	}
	//判断活动时间
	if($c_start_time!=''){
		$sql .= "and ($t_event.start_time = '$c_start_time') ";
	}
	if($c_end_time!=''){
		$sql .= "and ($t_event.end_time = '$c_end_time') ";
	}
	//order by
	if(!empty($c_orderby)){
		$sql.=" order by $t_event.$c_orderby ";
	}
	$sql.=" $c_ordersc ";
	
	//设置分页
	$dbo->setPages($c_perpage,$page_num);

	//取得数据
	$event_rs  =  $dbo->getRs($sql);

	//分页总数
	$page_total=$dbo->totalPage;
	
	//按字段排序
	$o_event_id='';$o_dateline='';$o_start_time='';$o_member_num='';
	if(get_argg('order_by')==''||get_argg('order_by')=="event_id"){$o_event_id="selected";}
	if(!get_argg('order_by')||get_argg('order_by')=="dateline"){$o_dateline="selected";}
	if(get_argg('order_by')=="start_time"){$o_start_time="selected";}
	if(get_argg('order_by')=="member_num"){$o_member_num="selected";}

	//显示控制
	$isset_data="";
	$none_data="content_none";
	$isNull=0;
	if(empty($event_rs)){
		$isNull=1;
		$isset_data="content_none";
		$none_data="";
	}
	
	$event_sort_rs = api_proxy("event_sort_by_self");
	$event_type = event_sort_list($event_sort_rs, '');
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/area.js'></script>
<script type='text/javascript' src='../servtools/calendar.js'></script>
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
</head>
<script type='text/javascript'>
function lock_member_callback(event_id,type_value){
	if(type_value==0){
	str="<font color='red'><?php echo $eb_langpackage->eb_lock; ?></font>";document.getElementById("unlock_button_"+event_id).style.display="";document.getElementById("lock_button_"+event_id).style.display="none";
	}else{
	str="<?php echo $eb_langpackage->eb_normal; ?>";document.getElementById("unlock_button_"+event_id).style.display="none";document.getElementById("lock_button_"+event_id).style.display="";
	}
}

function lock_member(event_id,type_value){
	var lock_member=new Ajax();
	lock_member.getInfo("event_list.action.php?op=lock","get","app","event_id="+event_id+"&type_value="+type_value,function(c){lock_member_callback(event_id,type_value);});
}

function del_event(event_id){
	var del_event = new Ajax();
	del_event.getInfo("event_list.action.php?op=del_singular","get","app","event_id="+event_id,"operate_"+event_id); 
}

function check_form(){
	var start_time=document.getElementById("start_time").value;
	var end_time=document.getElementById("end_time").value;
	var time_format=/\d{4}\-\d{1,2}\-\d{1,2}/;
	if(start_time){
		if(!time_format.test(start_time)){
			alert("<?php echo $eb_langpackage->eb_date_format_input_error; ?>");
			return false;
		}
	}
	if(end_time){
		if(!time_format.test(end_time)){
			alert("<?php echo $eb_langpackage->eb_date_format_input_error; ?>");
			return false;
		}
	}
}

function remove_all(){
	if(confirm('<?php echo $eb_langpackage->eb_confirm_delete; ?>')){
		window.data_list.action="event_list.action.php?op=del_plural";
		window.data_list.submit();
	}else{
		return false;
	}
}

function execution_operat(){
	window.data_list.action="event_list.action.php?op=eo";
	window.data_list.submit();
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
        <div class="crumbs"><?php echo $eb_langpackage->eb_location; ?> &gt;&gt; <a href="javascript:void(0);"><?php echo $eb_langpackage->eb_app_management; ?></a> &gt;&gt; <a href="event_list.php?order_by=event_id&order_sc=desc"><?php echo $eb_langpackage->eb_activity_management; ?></a></div>
        <hr />
        <div class="infobox">
            <h3><?php echo $eb_langpackage->eb_filter_condition; ?></h3>
            <div class="content">
<form method="get" id="event_list">
	<table class="form-table">
		<tr>
			<th width=12%><?php echo $eb_langpackage->eb_activity_ID; ?></th>
			<td><input class="small-text" name="event_id" id="event_id" /></td>
			<th><?php echo $eb_langpackage->eb_title; ?></th>
			<td><input class="small-text" name="title" id="title" /></td>
		</tr>
		<tr>
			<th><?php echo $eb_langpackage->eb_creator_name; ?></th>
			<td><input class="small-text" name="user_name" id="user_name" /></td>
			<th width=10%><?php echo $eb_langpackage->eb_activity_type; ?></th>
			<td>
            <?php echo $event_type; ?>
            </td>
		</tr>
		<tr>
			<th><?php echo $eb_langpackage->eb_public_nature; ?></th>
			<td>
			<select name="public" id="public">
              <option value=""><?php echo $eb_langpackage->eb_unlimited; ?></option>
			  <option value="0"><?php echo $eb_langpackage->eb_privacy; ?></option>
              <option value="1"><?php echo $eb_langpackage->eb_half_publicity; ?></option>
              <option value="2"><?php echo $eb_langpackage->eb_publicity; ?></option>
			</select>			</td>
			<th><?php echo $eb_langpackage->eb_activity_city; ?></th>
			<td>
            <select name="s1" id="s1"><option><?php echo $eb_langpackage->eb_please_select; ?></option></select>
			<select name="s2" id="s2"><option><?php echo $eb_langpackage->eb_please_select; ?></option></select>
			<script type="text/javascript">
                setup();
                document.getElementById('s1').value='<?php echo get_argg('province');?>';
                change(1);
                document.getElementById('s2').value='<?php echo get_argg('city');?>';
            </script>
            </td>
		</tr>
		<tr>
			<th><?php echo $eb_langpackage->eb_end_if; ?></th>
			<td valign="top">
            <select name="active" id="active">
            <option value=""><?php echo $eb_langpackage->eb_unlimited; ?></option>
            <option value="0"><?php echo $eb_langpackage->eb_not_end; ?></option>
            <option value="1"><?php echo $eb_langpackage->eb_already_end; ?></option>
            </select>
            </td>
			<th><?php echo $eb_langpackage->eb_activity_time; ?></th>
			<td>
            <input type='text' AUTOCOMPLETE=off onclick='calendar(this);' class="small-text" name='start_time' id='start_time' value='<?php echo get_argg('start_time');?>'> ~ <input class="small-text" type='text' AUTOCOMPLETE=off name='end_time' id='end_time' onclick='calendar(this);' value='<?php echo get_argg('end_time');?>'> (YYYY-MM-DD)
            </td>
		</tr>
		<tr>
			<th valign="top"><?php echo $eb_langpackage->eb_activity_Status; ?></th>
			<td>
            <select name="grade" id="grade">
            <option value=""><?php echo $eb_langpackage->eb_unlimited; ?></option>
            <option value="0"><?php echo $eb_langpackage->eb_to_audit; ?></option>
            <option value="-1"><?php echo $eb_langpackage->eb_failed_audit; ?></option>
            <option value="1"><?php echo $eb_langpackage->eb_passed_audit; ?></option>
            <option value="-2"><?php echo $eb_langpackage->eb_closed; ?></option>
            <option value="2"><?php echo $eb_langpackage->eb_recommend; ?></option>
            </select>
            </td>
			<th><?php echo $eb_langpackage->eb_results_sort; ?></th>
			<td><select name='order_by'>
              <option value='event_id' <?php echo $o_event_id;?>><?php echo $eb_langpackage->eb_default_sort; ?></option>
              <option value='dateline' <?php echo $o_dateline;?>><?php echo $eb_langpackage->eb_launch_time; ?></option>
              <option value='start_time' <?php echo $o_start_time;?>><?php echo $eb_langpackage->eb_start_time; ?></option>
              <option value='member_num' <?php echo $o_member_num;?>><?php echo $eb_langpackage->eb_participants_number; ?></option>
            </select>
			  <?php echo order_sc();?>
      		<?php echo perpage();?>
            </td>
		</tr>
		<tr>
			<th><?php echo $eb_langpackage->eb_number_limit; ?></th>
			<td colspan="3">
            <input class="small-text" name="limit_num1" id="limit_num1" /> ~ 
			<input class="small-text" name="limit_num2" id="limit_num2" />
            </td>
		</tr>
  		<tr><th></th>
			<td colspan="3">
            <input class="regular-button" type="submit" name="sm" id="sm" value="<?php echo $eb_langpackage->eb_search; ?>" onclick="check_form()" />
            </td>
		</tr>
	</table>
</form>
	</div>
</div>
<form name="data_list" id="data_list" method="post" action="">
<div class="infobox">
    <h3><?php echo $eb_langpackage->eb_activity_list; ?></h3>
    <div class="content">
	<table class='list_table <?php echo $isset_data;?>'>
	<thead>
		<tr>
        <th width="10px"><input type="hidden" id="" name="" value="" /></th>
        <th align="left"  width="200px"><?php echo $eb_langpackage->eb_activity_name; ?></th>
        <th align="left" ><?php echo $eb_langpackage->eb_activity_city; ?></th>
        <th align="left" width="200px" ><?php echo $eb_langpackage->eb_activity_time; ?></th>
        <th align="left" ><?php echo $eb_langpackage->eb_participation_interest; ?></th>
        <th align="left" ><?php echo $eb_langpackage->eb_sponsor; ?></th>
        <th align="left" ><?php echo $eb_langpackage->eb_management_status; ?></th>
        <th align="left" ><?php echo $eb_langpackage->eb_operation; ?></th>
        </tr></thead>
	<?php foreach($event_rs as $rs){?>
		<tr>
        	<td width="10px"><input type="checkbox" class="checkbox" name="checkany[]" value="<?php echo $rs['event_id'];?>" /></td>
			<td width="200px">
			<a href='../home.php?h=<?php echo $rs['user_id'];?>&app=event_space&event_id=<?php echo $rs['event_id'];?>' target='_blank'>
			<?php echo $rs['title'];?>
			</a>
			</td>
			<td><?php echo $rs['province'];?>&nbsp;-&nbsp;<?php echo $rs['city']?></td>
			<td><?php echo date($eb_langpackage->eb_time_display_format,$rs['start_time']);?>&nbsp;-&nbsp;<?php echo date($eb_langpackage->eb_time_display_format,$rs['end_time']);?></td>
			<td><?php echo $rs['member_num'];?>&nbsp;/&nbsp;<?php echo $rs['follow_num']?></td>
			<td><?php echo $rs['user_name'];?></td>
            <td>
			<?php
			switch($rs['grade']){
				case -2:
				echo $eb_langpackage->eb_closed;
        		break;
				case -1:
				echo $eb_langpackage->eb_failed_audit;
        		break;
				case 0:
				echo $eb_langpackage->eb_to_audit;
        		break;
				case 1:
				echo $eb_langpackage->eb_passed_audit;
        		break;
				case 2:
				echo $eb_langpackage->eb_recommend;
        		break;
			}
			?>
            </td>
			<td>
            <div id="operate_<?php echo $rs['event_id'];?>">
            <?php if($rs['is_pass']==0){ $unlock='display:';$lock='display:none';}else{$unlock='display:none';$lock='display:';}?>
            <span id="unlock_button_<?php echo $rs['event_id'];?>" style="<?php echo $unlock;?>">
            <a href="javascript:lock_member(<?php echo $rs['event_id'];?>,1);"><img title="<?php echo $eb_langpackage->eb_unlock; ?>" alt="<?php echo $eb_langpackage->eb_unlock; ?>" src="images/unlock.gif" /></a>
            </span>
			<span id="lock_button_<?php echo $rs['event_id'];?>" style="<?php echo $lock;?>">
            <a href="javascript:lock_member(<?php echo $rs['event_id'];?>,0);" onclick='return confirm("<?php echo $eb_langpackage->eb_sure_lock; ?>");'><img title="<?php echo $eb_langpackage->eb_lock; ?>" alt="<?php echo $eb_langpackage->eb_lock; ?>" src="images/lock.gif" /></a>
            </span>
			<a href="javascript:del_event(<?php echo $rs['event_id'];?>);"><image src="images/del.gif" title="<?php echo $eb_langpackage->eb_delete; ?>" alt="<?php echo $eb_langpackage->eb_delete; ?>" onclick="return confirm('<?php echo $eb_langpackage->eb_confirm_delete; ?>');" /></a>
			</div>
            </td>
		</tr>
	<?php
		}
	?>
    <tr>
    <td colspan="8">
    <input class="regular-button" type="button" name="chkall" id="chkall" onclick="checkAll('data_list')" value="<?php echo $eb_langpackage->eb_select_all; ?>" />
    <input class="regular-button" type="button" id="RemoveAll" name="RemoveAll" value="<?php echo $eb_langpackage->eb_bulk_delete; ?>" onclick="remove_all()" />
    
    <input type="radio" name="radio" id="radio1" value="0" /><?php echo $eb_langpackage->eb_to_audit; ?>
    <input type="radio" name="radio" id="radio2" value="-1" /><?php echo $eb_langpackage->eb_failed_audit; ?>
    <input type="radio" name="radio" id="radio3" value="1" /><?php echo $eb_langpackage->eb_passed_audit; ?>
    <input type="radio" name="radio" id="radio4" value="-2" /><?php echo $eb_langpackage->eb_closed; ?>
    <input type="radio" name="radio" id="radio5" value="2" /><?php echo $eb_langpackage->eb_recommend; ?>
    <input class="regular-button" type="button" id="ExecutionOperat" name="ExecutionOperat" value="<?php echo $eb_langpackage->eb_execution_operation; ?>" onclick="execution_operat()" />
    </td>
    </tr>
	</table>
<?php page_show($isNull,$page_num,$page_total);?>
	<div class='guide_info <?php echo $none_data;?>'><?php echo $eb_langpackage->eb_not_select_match_data; ?></div>
</div>
</div>
</div>
</div>
</form>
</body>
</html>