<?php
require("session_check.php");
require("../foundation/fpages_bar.php");

//语言包引入
$eb_langpackage = new event_backstagelp;

require("../foundation/fsqlseletiem_set.php");
require("../api/base_support.php");
//变量区

//读写初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
$t_event_type = $tablePreStr."event_type";
//权限

//操作实例
$sql="select * from $t_event_type order by display_order asc";
$event_type_rs = $dbo->getRs($sql);

$op = get_argg('op');
if($op=="del"){
	$typeid = get_argg('typeid');
	$sql = "delete from $t_event_type where type_id=$typeid";
	$dbo->exeupdate($sql);
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>

<script type='text/javascript'>
function inserts(){
	window.location.href="event_type_detail.php?op=add";
}

function deletes(typeid){
	window.location.href="event_type_list.action.php?op=del&typeid="+typeid;
}
</script>
</head>
<body>
<div id="maincontent">
    <div class="wrap">
	<div class="crumbs"><?php echo $eb_langpackage->eb_location; ?> &gt;&gt; <a href="javascript:void(0);"><?php echo $eb_langpackage->eb_user_management; ?></a> &gt;&gt; <a href="event_type_list.php"><?php echo $eb_langpackage->eb_activity_sort; ?></a></div>
<hr />
<div class="infobox">
<h3><?php echo $eb_langpackage->eb_activity; ?></h3>
<div class="content">
<form method="get" action="" id="event_type_list" name="event_type_list">
<table class="list_table" id="mytable">
<thead>
	<tr>
            <th width="30%"><?php echo $eb_langpackage->eb_ranked_num;?></th>
            <th width="30%"><?php echo $eb_langpackage->eb_name;?></th>
            <th><?php echo $eb_langpackage->eb_operation;?></th>
    </tr>
	</thead>
<?php foreach($event_type_rs as $rs){?>
	<tr>
    <td>
    <?php echo $rs['display_order'];?>
	<input type="hidden" id="typeid" name="typeid" value="<?php echo $rs['type_id'];?>" />
    </td>
    <td>
    <?php echo $rs['type_name'];?>
    </td>
    <td>
    <a href="event_type_detail.php?op=edit&typeid=<?php echo $rs['type_id'];?>"><?php echo $eb_langpackage->eb_update; ?></a>
    &nbsp;|&nbsp;
    <a href="javascript:deletes('<?php echo $rs['type_id'];?>')" onclick='return confirm("<?php echo $eb_langpackage->eb_confirm_delete; ?>");'><?php echo $eb_langpackage->eb_delete; ?></a>
    </td>
    </tr>
<?php }?>
	<tr>
    <td colspan="3">
    <input class="regular-button" type="button" id="adds" name="adds" value="<?php echo $eb_langpackage->eb_insert; ?>" onClick="inserts()" />
    </td>
    </tr>
</table>
</form>
</div>
</div>
</div>
</div>
</div>
</body>
</html>