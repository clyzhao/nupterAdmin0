<?php
header("content-type:text/html;charset=utf-8");
require_once 'conn.php';

$id=$_POST['id'];
$title=$_POST['title'];
$author=$_POST['author'];
$tag=$_POST['tag'];
$editorValue=$_POST['editorValue'];
$img_url1=$_POST['img_url1'];
$img_url2=$_POST['img_url2'];
$img_url3=$_POST['img_url3'];
$initial_url=$_POST['initial-url'];
$ifPush=$_POST['push'];
$ifCarousel=$_POST['carousel'];
$ifReprint="yes";

if($ifPush=="on"){
  $ifPush="yes";
}else{
  $ifPush="no";
}
if($ifCarousel=="on"){
  $ifCarousel="yes";
}else{
  $ifCarousel="no";
}

if(!$initial_url && !$title && !$author && !$tag && !$editorValue){
  echo "<script>alert('服务器未得到数据，请重试');</script>";
  echo "<script>window.location='index.php';</script>";
}else{
  if($initial_url==''){
    $ifReprint="no";
  }

  $sql="UPDATE reading SET title='$title', author='$author', tag='$tag', editorValue='$editorValue', img_url1='$img_url1', img_url2='$img_url2', img_url3='$img_url3', initial_url='$initial_url', ifPush='$ifPush', ifCarousel='$ifCarousel', ifReprint='$ifReprint' where id='$id'";
  if(mysql_query($sql)){
    echo "<script>alert('提交文章成功');</script>";
    echo "<script>window.location='index.php';</script>";
  }else{
    echo "<script>alert('提交文章时服务器出错，请重试');</script>";
    echo "<script>window.location='index.php';</script>";
  }
}