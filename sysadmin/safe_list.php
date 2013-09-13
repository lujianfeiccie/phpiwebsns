<?php
//引入公共文件
require("session_check.php");
require("../api/base_support.php");
require("../foundation/fsafe.php");

//扫描的结果信息
defined('RESULTINFO') or define('RESULTINFO','temp/safe_resultinfo.php');

$scan_result=array();
$scan_result=array_read(constant('RESULTINFO'));
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
<body>
	<div id="maincontent">
		<div class="wrap">
			<div class="crumbs">当前位置 &gt;&gt;应用管理&gt;&gt;木马扫描</div>
			<hr />
			<div class="infobox">
				<h3>木马扫描</h3>
				<div id="content" class="content">
				<?php if(!empty($scan_result)){?>
					<table class='list_table <?php echo $isset_data;?>'>
						<thead>
						<tr>
					    <th width="350" style="text-align:left">文件地址</th>
					    <th style="text-align:center">特征函数</th>
					    <th style="text-align:center">特征函数次数</th>
					    <th style="text-align:center">特征代码</th>
					    <th style="text-align:center">特征代码次数</th>
						</tr>
						</thead>
						<?php
						foreach ($scan_result as $key => $val){
						?>
						<tr>
					    <td style="text-align:left"><?php echo str_replace($webRoot,'',$key);?></td>
					    <td style="text-align:center">
								<?php
								if(isset($val['func'])){
									$arr=array();
									foreach ($val['func'] as $key2 => $val2){
										$arr[$key2] = strtolower($val2[1]);
									}
									$arr = array_unique($arr);
									foreach ($arr as $val2){
										echo $val2." ";
									}
								}?>
							</td>

					    <td style="text-align:center"><?php if(isset($val['func'])){echo count($val['func']);}else{echo '0';}?></td>
					    <td style="text-align:center">
								<?php
								if(isset($val['code'])){
									$arr=array();
									foreach ($val['code'] as $key2 => $val2){
										$arr[$key2] = strtolower($val2[1]);
									}
									$arr = array_unique($arr);
									foreach ($arr as $val2){
										echo $val2." ";
									}
								}?>
							</td>

							<td style="text-align:center">
								<?php if(isset($val['code'])){echo count($val['code']);}else{echo '0';}?>
							</td>
						</tr>
						<?php }?>
					</table>
					<?php }else{?>
					未发现异常，系统一切正常
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>