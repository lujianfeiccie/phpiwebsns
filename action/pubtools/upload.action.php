<?php
  //语言包引入
  $pu_langpackage=new pubtooslp;

  $limcount=1;//限制每次上传附件数量
  if(count($_FILES['attach']['name'])>$limcount){
     action_return(0,$pu_langpackage->pu_count_err,'-1');
  }

  dbtarget('w',$dbServs);
  $dbo=new dbex();

  $pic_dir='uploadfiles/photo_store/';

  $up = new upload();
  $up->set_dir($webRoot.$pic_dir,'{y}/{m}/{d}');
  $fs=$up->execute();

  $user_id=get_sess_userid();//用户ID

  //定义文件表
  $t_uploadfile=$tablePreStr."uploadfile";

  $realtxt=$fs[0];

  if($realtxt['flag']==1){
      $fileSrcStr=str_replace($webRoot,"",$realtxt['dir']).$realtxt['name'];
      $fileName=$realtxt['initname'];
      $sql="insert into $t_uploadfile (file_name,file_src,user_id,add_time) values ('$fileName','$fileSrcStr','$user_id','".constant('NOWTIME')."')";
      $dbo->exeUpdate($sql);
      $last_id=mysql_insert_id();
      echo '<script type="text/javascript">parent.addImage("'.str_replace($pic_dir,'',$fileSrcStr).'");</script>';
      action_return(1,"",-1);
  }else if($realtxt['flag']==-1){
  	  action_return(0,$pu_langpackage->pu_type_err,'-1');
  }else if($realtxt['flag']==-2){
      action_return(0,$pu_langpackage->pu_capacity_err,'-1');
  }

?>

