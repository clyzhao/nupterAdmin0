<?php 
header("Content_type:text/html;character=utf-8");
require_once 'conn.php';

$sql="select * from reading where id=9";
if($res=mysql_query($sql)){
  $info=mysql_fetch_assoc($res);
  for($i=1; $i<=3; $i++){
    $img_url="img_url".$i;
    echo $info[$img_url];
  }
}
