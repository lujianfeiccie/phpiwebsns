<?php
	require("session_check.php");	
	require("../foundation/aintegral.php");
	require("../foundation/module_affair.php");

	//语言包引入
	$m_langpackage=new modulelp;
	
	//数据表定义区
	$t_ask=$tablePreStr."ask";
	$t_ask_reply=$tablePreStr."ask_reply";
	
	$dbo = new dbex;
	dbtarget('w',$dbServs);
	
	//判断是否批量删除
	if(get_argp('checkany')){//批量

		$ask_ids = get_argp('checkany');
		if(!empty($ask_ids)){
			foreach($ask_ids as $rs){
				//获取悬赏积分
				$sql="select user_id,reward from $t_ask where ask_id=$rs";
				$reward=$dbo->getRow($sql);
				
				$sql = "delete from $t_ask where ask_id=$rs";
				if($dbo->exeUpdate($sql)){
					$sql = "delete from $t_ask_reply where ask_id=$rs";
					$dbo->exeUpdate($sql);
					increase_integral($dbo,$reward['reward'],$reward['user_id']);
				}
			}
		}
	}else{//单条
		
		//变量区
		$ask_id=intval(get_argg('ask_id'));
		$sendor_id=intval(get_argg('sendor_id'));
		
		//获取悬赏积分
		$sql="select user_id,reward from $t_ask where ask_id=$ask_id";
		$reward=$dbo->getRow($sql);
		
	
		$sql = "delete from $t_ask where ask_id=$ask_id";
	
		if($dbo->exeUpdate($sql)){
			$sql = "delete from $t_ask_reply where ask_id=$ask_id";
			$dbo->exeUpdate($sql);
			//退还用户积分
			increase_integral($dbo,$reward['reward'],$reward['user_id']);
			echo $m_langpackage->m_del_suc;		
		}else{
			echo $m_langpackage->m_del_lose;
		}
	}
?>
<script language="javascript" type="text/javascript">
window.location.href='ask_list.php?order_by=ask_id&order_sc=desc';
</script>