<?php
header("content-type:text/html;charset=utf-8");
require_once 'conn.php';

$result=array();
$info_artists=array();
$every_page_artists_num=$_REQUEST['every_page_artists_num'];

$sql_get_page_num="SELECT COUNT(*) AS count FROM reading";
$artists_total_num=mysql_fetch_array(mysql_query($sql_get_page_num));
$count=$artists_total_num['count'];
$page_num=((int)$count/$every_page_artists_num)+1;
$result['page_num']=(int)$page_num;

$sql_get_firstpage_artists="SELECT * FROM reading ORDER BY id DESC LIMIT 0,$every_page_artists_num";
$res_firstpage_artists=mysql_query($sql_get_firstpage_artists);
while($result_artist=mysql_fetch_assoc($res_firstpage_artists)){
  array_push($info_artists, $result_artist);
}
$result['firstpage_artists']=$info_artists;

echo json_encode($result);

