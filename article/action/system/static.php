<?php
$index_content=file_get_contents($siteDomain.'article/index.php');
$ref=fopen('index.html','w+');
fwrite($ref,$index_content);
fclose($ref);
action_return(1, '静态化成功', '-1');
?>