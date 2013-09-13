<?php
class dbex
{
   public function query($sql)   {
      if(mysql_query($sql)){
      	return true;
      }else{
      	return false;
      }
   }
 public function getTable(){
   		$result = mysql_query("SHOW TABLES");
   		if ($result){
   			while($row=mysql_fetch_row($result)){
   				$table[] = $row[0];
   			}
   		}
   		return $table;
   }
}
?>