<?php
require("session_check.php");	
require("../api/base_support.php");

//表定义区
$t_user_information=$tablePreStr."user_information";
//数据库操作初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//变量区
$id=intval(get_argg('id'));
$info_name='';
$info_sort=0;
$info_type=0;
$info_values='';
$submit_str='添加';
$hidd_value='user_information_add.action.php';
if($id){
$sql="select * from $t_user_information where info_id=$id";
$info_row=$dbo->getRow($sql);
$info_name=$info_row['info_name'];
$info_sort=$info_row['sort'];
$info_type=$info_row['input_type'];
$info_values=$info_row['info_values'];
$submit_str='修改';
$hidd_value="user_information_edit.action.php?id=$id";
}
$input_type_value=array();
$input_type_value=array("0"=>"文本框","1"=>"下拉列表","2"=>"单选按钮","3"=>"多选按钮");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" media="all" href="css/admin.css">
    <script type='text/javascript' src='../servtools/ajax_client/ajax.js'></script>
    <script type="text/javascript">
    	function  del_information(info_id){
			var del_inf=new Ajax();
			del_inf.getInfo("user_information_del.action.php","post","app","id="+info_id,function(c){del_information_callback(c);}); 
		} 
		function del_information_callback(c){
			if(c=='success'){
				alert('删除成功');
			}else{
				alert('删除失败');
			}
			window.location.reload();
		}
    </script>
</head>
<body>
    <div id="maincontent">
        <div class="wrap">
            <div class="crumbs">当前位置 &gt;&gt; <a href="javascript:void(0);">用户管理</a> &gt;&gt; <a href="user_custom_edit.php">添加自定义用户信息</a></div>         
            
             <div class="infobox">
                <h3>添加自定义用户信息</h3>
                <div class="content">
 				  <form action="<?php echo $hidd_value; ?>" method="post" name='form' onsubmit='return check_form();'>
                  
                    <table class="form-table">
                        <tr>
                            <th width="90">*信息名称</th>
                            <td><input type="text" class="small-text" name='info_name' value="<?php echo $info_name;?>" /></td>
                        </tr>
                         <tr>
                            <th width="90">*信息输入类型</th>
                            <td><select name="info_type">
                                 <?php 
								 	foreach($input_type_value as $key=>$value){
									  $select='';
									  if($key==$info_type){
									     $select="selected";
									  }
									echo "<option value='".$key."' $select >".$value."</option>";
									}
								 ?>
                             	</select>   默认为文本输入框                        
                            </td>
                        </tr>
                         <tr>
                            <th width="90">*信息值</th>
                            <td><textarea name="info_values" style="width:300px; height:200px;"><?php echo $info_values;?></textarea></td>
                        </tr>
                         <tr>
                            <th width="90">*排序</th>
                            <td><input type="text" class="small-text" name='info_sort' value="<?php echo $info_sort;?>" />默认为0</td>
                        </tr>
                         <tr>
                            <th width="90"></th>
                            <td><input type="submit" class="regular-button" value="<?php echo $submit_str;?>"/></td>
                        </tr>
                    </table>
                  </form>
                </div>
            </div>  
        </div>
    </div>
</body>
</html>