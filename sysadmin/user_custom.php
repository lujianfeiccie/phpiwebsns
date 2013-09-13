<?php
require("session_check.php");	
require("../foundation/fpages_bar.php");
require("../api/base_support.php");

//表定义区
$t_user=$tablePreStr."user";
$t_user_information=$tablePreStr."user_information";
//数据库操作初始化
$dbo = new dbex;
dbtarget('w',$dbServs);
//当前页面参数
$page_num=trim(get_argg('page'));
//变量区
$info_name=short_check(get_argg('info_name'));
$c_perpage=get_argg('perpage') ? intval(get_argg('perpage')):20;
$dbo->setPages(10,$page_num);//设置分页
//搜索条件设置
$condition="where 1=1";

if(get_argg('search')){
  $search_name=get_argg('search_name');
  $condition=$condition."  and info_name like '%$search_name%'";
}
//取出数据列表
$sql="select * from $t_user_information ".$condition ."  order by sort asc";
//取得数据
$info_rs=$dbo->getRs($sql);
$page_total=$dbo->totalPage; //分页总数
//显示控制
$isNull=0;
$isset_data="";
$none_data="content_none";
if(empty($info_rs)){
	$isset_data="content_none";
	$none_data="";
	$isNull=1;
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
			if(c!='success'){
				alert('删除失败');
			}
			window.location.reload();
		}
    
    </script>
</head>
<body>
    <div id="maincontent">
        <div class="wrap">
            <div class="crumbs">当前位置 &gt;&gt; <a href="javascript:void(0);">用户管理</a> &gt;&gt; <a href="user_custom.php">会员自定义</a></div>
            <hr />
            <div class="infobox">
                <h3>筛选条件</h3>
                <div class="content">
                    <form action="" method="get" name='form' onsubmit='return check_form();'>
                    <input type="hidden" name="mod" id="mod" value="user_custom" />
                    <input type="hidden" name="search" id="search" value="1" />
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th width="90">信息名称</th>
                            <td><input type="text" class="small-text" name='search_name' value="<?php echo get_argg('search_name');?>"></td>
                        </tr>
                        <tr>
                        	<td ><input class="regular-button" type="submit" value="搜索" /></td>
                        </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>           
            <div class="infobox">
                <h3>信息列表</h3>
                <div class="content">
                    <table class="list_table <?php echo $isset_data;?>">
                        <thead>
                        <tr>
                            <th>信息名称</th>
                            <th style="text-align:center">输入类型</th>
                            <th style="text-align:center">信息值</th>
                            <th style="text-align:center">排序</th>
                            <th style="text-align:center">操作</th>
                        </tr>
                        </thead>
                        <?php foreach($info_rs as $rs){ ?>
                        <tr>
                            <td><?php echo $rs['info_name'];?></td>
                            <td style="text-align:center"><?php echo $input_type_value[$rs['input_type']];?></td>
                            <td style="text-align:center"><?php echo $rs['info_values'];?></td>
                            <td style="text-align:center"><?php echo $rs['sort'];?></td>
                            <td align="center">
                                <a href="user_custom_edit.php?id=<?php echo $rs['info_id'];?>" >修改</a> |
                                <a href="javascript:del_information(<?php echo $rs['info_id'];?>);" onclick='return confirm("确认删除");'>删除</a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <?php page_show($isNull,$page_num,$page_total);?>
                    <div class='guide_info <?php echo $none_data;?>'>没有查询到与条件相匹配的数据</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>