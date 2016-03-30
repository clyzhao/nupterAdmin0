<?php
header("content-type:text/html;charset=utf-8");
require_once 'conn.php';

if($id=$_GET['id']){
  $sql="DELETE FROM reading WHERE id='$id'";
  if(mysql_query($sql)){
    echo "<script>alert('删除文章成功');</script>";
    echo "<script>window.location='index.php';</script>";
  }else{
    echo "<script>alert('服务器出错，请重试');</script>";
    echo "<script>window.location='index.php';</script>";
  }
}else{
  echo "<script>alert('服务器没有得到请求数据，请重试');</script>";
  echo "<script>window.location='index.php';</script>";
}

