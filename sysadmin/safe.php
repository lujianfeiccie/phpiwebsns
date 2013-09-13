<?php
//引入公共文件
require("session_check.php");
require("../foundation/fsqlseletiem_set.php");
require("../api/base_support.php");
require("../foundation/fsafe.php");

//默认扫描参数
if(empty($safe)){
	$safe = array (
	  'file_type' => 'php|js|html',
	  'func' => 'com|system|exec|eval|escapeshell|cmd|passthru|base64_decode|gzuncompress',
	  'code' => ''
	);
}

$dir=array();

//获取网站根目录
$dir=dir_list($webRoot);

//获取文件的md5镜像方案
$md5_file_list=get_md5_file_list();
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

function show_message(msg){
	var div_obj = document.getElementById('content');
	div_obj.innerHTML+=msg+"<br>";
}

function start_scan(){
	show_message("扫描开始");
	scan_file_type();
}

function scan_file_type(){
	show_message("正在检索文件类型...");
	var file_type = document.getElementById('file_type').value;
	var scan_obj=new Ajax();
	scan_obj.getInfo("safe_process.action.php","post","app","action=file_type&file_type="+file_type,function(c){
		if(c==1){
			show_message("检索文件类型完毕");
			scan_file_md5();
		}else{
			show_message("检索文件类型未完成，扫描中止");alert(c);
		}
	});
}

function scan_file_md5(){
	show_message("正在进行文件历史修改对比...");
	var filename = document.getElementById('filename').value;
	var scan_obj=new Ajax();
	scan_obj.getInfo("safe_process.action.php","post","app","action=file_md5&filename="+filename,function(c){
		if(c==1){
			show_message("文件历史修改对比完毕");
			scan_func();
		}else{
			show_message("文件历史修改对比未完成，扫描中止");alert(c);
		}
	});
}

function scan_func(){
	show_message("正在进行特征函数扫描...");
	var func = document.getElementById('func').value;
	var scan_obj=new Ajax();
	scan_obj.getInfo("safe_process.action.php","post","app","action=func&func="+func,function(c){
		if(c==1){
			show_message("特征函数扫描完毕");
			scan_code();
		}else{
			show_message("特征函数扫描未完成，扫描中止");alert(c);
		}
	});
}

function scan_code(){
	show_message("正在进行特征代码扫描...");
	var code = document.getElementById('code').value;
	var scan_obj=new Ajax();
	scan_obj.getInfo("safe_process.action.php","post","app","action=code&code="+code,function(c){
		if(c==1){
			show_message("特征代码扫描完毕");
			show_scan_result();
		}else{
			show_message("特征代码扫描未完成，扫描中止");alert(c);
		}
	});
}

function show_scan_result(){
	show_message('扫描完成，将为您显示扫描结果...');
	setTimeout('location.href="safe_list.php"',2500);
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
								<?php	foreach ($dir as $key => $val){?>
									<input type='checkbox' name='dir[]' value='<?php echo $val;?>' /><?php echo $key;?>
								<?php }?>
							</td>
						</tr>

						<tr>
							<th>文件类型</th>
							<td><input name="file_type" id="file_type" type='text' class="regular-text" value='<?php echo $safe['file_type']?>' /> 多个请用“|”隔开</td>
						</tr>

						<tr>
							<th>特征函数</th>
							<td><input name="func" id="func" type='text' class="regular-text" value='<?php echo $safe['func'];?>' /> 多个请用“|”隔开</td>
						</tr>

						<tr>
							<th>特征代码</th>
							<td><input name="code" id="code" type='text' class="regular-text" value='<?php echo htmlentities($safe['code']);?>' /> 多个请用“|”隔开</td>
						</tr>

						<tr>
							<th>MD5校验镜像</th>
							<td>
								<select name="filename" id="filename">
								<?php
								foreach($md5_file_list as $val){
									echo "<option value='$val'>$val</option>";
								}
								?>
								</select>
							</td>
        		</tr>

						<tr>
							<input type="hidden" name="safe" value="scan" />
							<th></th>
							<td><input name="submit" class="regular-button" type='submit' value='开始扫描' /></td>
						</tr>
					</table>

				</form>
			</div>

		</div>

		<div class="infobox">
			<h3>木马扫描</h3>
			<div id="content" class="content"></div>
		</div>

	</div>

</div>

<iframe name="safe_iframe" id="safe_iframe" width="0" height="0"></iframe>
</body>
</html>