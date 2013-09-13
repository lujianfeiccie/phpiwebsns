<?php
require("session_check.php");
require("../foundation/fpages_bar.php");
require("../foundation/fsqlseletiem_set.php");

//语言包引入
$eb_langpackage = new event_backstagelp;

require("../foundation/fback_search.php");
require("../api/base_support.php");
//读写初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//接收参数
$op = get_argg('op');
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<SCRIPT language=JavaScript src="../servtools/kindeditor/kindeditor.js"></SCRIPT>

<script type='text/javascript'>
function del_poster(typeid){
	window.location.href="event_type_detail.action.php?op=del_poster&typeid="+typeid;
}

function event_detail(){
	var typename = document.getElementById('typename').value;
	var displayorder = document.getElementById('displayorder').value;
	if(typename=="" || typename==null){
		alert("<?php echo $eb_langpackage->eb_enter_category_name; ?>");
		return false;
	}
	if(displayorder=="" || displayorder==null){
		alert("<?php echo $eb_langpackage->eb_enter_category_sort; ?>");
		return false;
	}
}
</script>
</head>
<body>
<div id="maincontent">
<div class="wrap">
<div class="crumbs"><?php echo $eb_langpackage->eb_location; ?> &gt;&gt; <a href="javascript:void(0);"><?php echo $eb_langpackage->eb_activity_sort; ?></a> &gt;&gt; <a href="event_type_list.php"><?php echo $eb_langpackage->eb_activity_type; ?></a>
<?php 
	$rs = array('type_id'=>'','type_name'=>'','poster'=>'','poster_thumb'=>'','template'=>'','display_order'=>'');
	if($op=="edit"){
		$typeid = get_argg('typeid');
		$t_event_type=$tablePreStr."event_type";
		$sql = "select * from $t_event_type where type_id=$typeid";
		$rs = $dbo->getRow($sql);
	}
?>
<hr/>
<form enctype="multipart/form-data" id="event_type_detail" name="event_type_detail" method="post" action="event_type_detail.action.php?op=<?php echo $op; ?>&typeid=<?php echo $rs['type_id'];?>" onSubmit="return event_detail();">
  <table class="form-table" border="0">
    <tr>
      <th><?php echo $eb_langpackage->eb_category_name; ?></th>
      <td><input class="regular-text" type="text" name="typename" id="typename" value="<?php echo $rs['type_name'];?>" /></td>
    </tr>
    <tr>
      <th><?php echo $eb_langpackage->eb_default_poster; ?></th>
      <td>
	  <?php if($rs['poster_thumb']){ ?>
      <img src="<?php echo $rs['poster_thumb']; ?>" alt="<?php echo $eb_langpackage->eb_image_loading; ?>" />
	  <?php }else{ ?>
      <img src="../uploadfiles/event/default_event_poster.jpg" alt="<?php echo $eb_langpackage->eb_image_loading; ?>" />
	  <?php } ?>
            <p class="lightcolor"><?php echo $eb_langpackage->eb_default_poster_prompt; ?>&nbsp;&nbsp;
            <?php if($op=="edit"){ ?>
            <a class="red" href="javascript:del_poster('<?php echo $rs['type_id'];?>')" ><?php echo $eb_langpackage->eb_delete; ?></a>
            <?php } ?>
            </p>
      <p><input  type="file" name="attach" id="attach" /> </p>
      </td>
    </tr>
    <tr>
      <th><?php echo $eb_langpackage->eb_default_template; ?></th>
      <td>
      <textarea style='width:550px;height:400px;_width:550px;' class="regular-textarea" name="templates" id="templates"><?php echo $rs['template'];?></textarea>      <p class="lightcolor"><?php echo $eb_langpackage->eb_default_template_prompt; ?> </p>

      </td>
    </tr>
    <tr>
      <th><?php echo $eb_langpackage->eb_display_order; ?></th>
      <td><input class="small-text" type="text" name="displayorder" id="displayorder" value="<?php echo $rs['display_order'];?>" /></td>
    </tr>
    <tr>
      <th><input class="regular-button" type="submit" name="sm" id="sm" value="<?php echo $eb_langpackage->eb_submit; ?>" /></th>
      <td></td>
    </tr>
  </table>
</form>
</div>
</div>
</div>
<script type="text/javascript">
KE.show({
	id:'templates',
	resizeMode:0
});
</script>
</body>
</html>