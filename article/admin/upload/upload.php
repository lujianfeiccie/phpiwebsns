<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title></title><link href="../skin/default/jooyea/css/base.css" type="text/css" rel="stylesheet">
  <script type="text/javascript" language="javascript">
  	function uploadImg(formObj)
  	{
  		if(document.getElementById("imgUrl").value == '')
  		{
  			return false;
  		}
  		return true;
  	}
  </script>
  </head>
    <body style="margin:0;padding:0;background-color:transparent;font-size:12px">
    <form name="upload" method="post" action="index.php?app=act&upload&upload<?php echo isset($_GET['mod']) ? '&mod='.$_GET['mod']:'';?>" onSubmit="return uploadImg(this);" enctype="multipart/form-data" style="margin:0">
    	<input class="left mr10" type="file" style="padding:3px 0" id="imgUrl" name="attach[]"/><input class="small-btn left" type="submit" value="上传" />
    </form>
    </body>
</html>
