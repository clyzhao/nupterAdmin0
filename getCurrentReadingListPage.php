<?php
header("content-type:text/html;charset=utf-8");
require_once 'conn.php';

$result=array();
$info_artists=array();
$start=$_REQUEST['start'];
$every_page_artists_num=$_REQUEST['every_page_artists_num'];

$sql="SELECT * FROM reading ORDER BY id DESC LIMIT $start, $every_page_artists_num";
$res_sql=mysql_query($sql);
while($res=mysql_fetch_assoc($res_sql)){
  array_push($info_artists, $res);
}

$result['info_artists']=$info_artists;
echo json_encode($result);