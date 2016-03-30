<?php
header("content-type:text/html;charset=utf-8");
require_once 'conn.php';

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

if((!$title || !$author || !$tag || !$editorValue) && !$initial_url){
  echo "<script>alert('请填写必要的内容');</script>";
  echo "<script>window.location='index.php';</script>";
}else{
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
  if(!$initial_url){
    $ifReprint=="no";
  }

  $sql="insert reading(title, author, tag, editorValue, img_url1, img_url2, img_url3, ifReprint, initial_url, ifPush, ifCarousel) value('$title', '$author', '$tag', '$editorValue', '$img_url1', '$img_url2', '$img_url3', '$ifReprint', '$initial_url', '$ifPush', '$ifCarousel')";
  if(mysql_query($sql)){
    echo "<script>alert('提交文章成功');</script>";
    echo "<script>window.location='index.php';</script>";
  }else{
    echo "<script>alert('提交文章时服务器出错，请重试');</script>";
    echo "<script>window.location='index.php';</script>";
  }
}
