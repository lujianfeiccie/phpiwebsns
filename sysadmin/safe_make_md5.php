<?php
//引入公共文件
require("session_check.php");
require("../foundation/fsqlseletiem_set.php");
require("../api/base_support.php");
require("../foundation/fsafe.php");

if(empty($safe)){
	$safe = array (
	  'file_type' => 'php|js|html',
	  'func' => 'com|system|exec|eval|escapeshell|cmd|passthru|base64_decode|gzuncompress',
	  'code' => ''
	);
}
$dir=array();
$dir=dir_list($webRoot);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
<script type='text/javascript' src='../servtools/calendar.js'></script>
<script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
<script type='text/javascript' src='js/jy.js'></script>
</head>
<script type='text/javascript'>
function start_make_md5(){
	var file_type = document.getElementById('file_type').value;
	scan_file_type(file_type);
}

function scan_file_type(file_type){
	var scan_obj=new Ajax();
	scan_obj.getInfo("safe_process.action.php","post","app","action=file_type&file_type="+file_type+"&make_md5=1",function(c){
		if(c==1){
			alert("文件镜像已生成");
			window.location.href='safe.php';
		}else{
			alert("生成文件镜像未完成，程序中止");alert(c);
		}
	});
}

</script>
<body>
	<div id="maincontent">
		<div class="wrap">
			<div class="crumbs">当前位置 &gt;&gt;应用管理&gt;&gt;木马扫描</div>
			<hr />
			<div class="infobox">
	    	<h3>筛选条件</h3>
	    	<div class="content">
					<form action="safe_iframe.php" name="safe_form" method="post" target="safe_iframe" >
						<table class="form-table">

							<tr>
								<th width=80px>扫描范围</th>
								<td>
								<?php foreach ($dir as $key => $val){?>
										<input type='checkbox' name='dir[]' value='<?php echo $val;?>' /><?php echo $key;?>
								<?php }?>
								</td>
							</tr>

							<tr>
								<th>文件类型</th>
								<td><input name="file_type" id="file_type" type='text' class="regular-text" value='<?php echo $safe['file_type']?>' /> 多个请用“|”隔开</td>
							</tr>

							<tr>
								<input type="hidden" name="safe" value="md5" />
								<th></th>
								<td><input name="submit" type='submit' class="regular-button" value='生成镜像' /></td>
							</tr>

						</table>
	        </form>
	    	</div>
			</div>
		</div>
	</div>
	<iframe name="safe_iframe" id="safe_iframe" width="0" height="0"></iframe>
</body>
</html>