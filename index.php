<?php
session_start();
if ($_SESSION["name"]) {
} else {
    header("Location:login.html");
}
include 'conn.php';
$isUpdate = 0;
if ($_GET) {
    $id = $_GET["id"];
    $sql = "select * from reading where id=$id limit 1";
    if ($res = mysql_query($sql)) {
        $info = mysql_fetch_assoc($res);       
    }
    $isUpdate = 1;
} else {
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>文章管理系统</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <style>
  html,body{
    height: 1500px;
    overflow-x: hidden;
  }
  .container{
    width: 100%;
  }
  .container, .row, #sidebar{
    height: 100%;
  }
  .container, #sidebar{
    padding: 0;
    margin: 0;
  }
  #sidebar{
    background: #262d40;
    text-align: center;
  }
  #sidebar a{
    color: #ffffff;
    text-decoration: none;
  }
  #sidebar-tab li>a:hover{
    background: rgb(72,143,210);
  }
  #sidebar-tab li.active{
    background: rgb(72,143,210);
  }
  .header{
    height: 50px;
    background: rgb(61,74,93);
    color: #ffffff;
  }
  .header-content{
    float: right;
    margin-right: 20px !important;
    line-height: 50px;
  }
  #header-container{
    margin: 0 !important;
    padding: 0 !important;
  }
  .form-container{
    margin-top: 50px;
  }
  #img-container img{
    display: none;
    height: 200px;
    width: 150px;
    border: 1px solid #ccc;
    margin: 20px 10px;
  }
  .artists-container{
    margin-top: 50px;
  }
  </style>
  <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/plupload.full.min.js"></script>
  <script type="text/javascript" src="js/qiniu.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="ueditor/ueditor.all.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="ueditor/lang/zh-cn/zh-cn.js"></script>
</head>

<body>
<div class="container">
  <div class="row">
    <div class="col-md-3 col-lg-2" id="sidebar">
      <a href="#home"><h2 style="color: rgb(37,155,254); margin-top: 50px;">掌上南邮4.0</h2></a>
      <nav style="margin-top: 30px;">
        <ul class="nav nav-pills nav-stacked" id="sidebar-tab" role="tablist">
          <li role="presentation" class="active">
            <a href="#home" aria-controls="home" role="pill" data-toggle="pill">新增文章</a>
          </li>
          <li role="presentation">
            <a href="#artists" aria-controls="artists" role="pill" data-toggle="pill" id="artists-list">文章列表</a>
          </li>
          <li role="presentation">
            <a href="#messages" aria-controls="messages" role="pill" data-toggle="pill">消息管理</a>
          </li>
        </ul>
      </nav>
    </div>

    <div class="col-md-9 col-lg-10" id="header-container">
      <header class="header">
        <div class="header-content">
          <span>欢迎您，</span>
          <span><?php echo $_SESSION["name"]; ?></span>
        </div>
      </header>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="home">
          <div class="container form-container">
            <div class="row">
              <div class="col-md-7 col-lg-6 col-md-offset-2">
                <p style="margin-top: 10px;">转载文章请直接到表单底部添加链接提交</p>
                <p style="margin-bottom: 20px;">特色图片说明：特色图片可选择添加，最多添加3张，用于在APP端显示文章列表时附加显示，不显示在文章中，原创及转载文章都可添加特色图片。文章中的图片请在编辑文章内容时添加。</p>
                <form action="addReading.php" method="POST">
                  <div class="form-group">
                    <label for="title">文章标题</label>
                    <input type="text" class="form-control" id="title" name="title">
                  </div>
                  <div class="form-group">
                    <label for="author">文章作者</label>
                    <input type="text" class="form-control" id="author" name="author" value="<?php echo $_SESSION["name"]; ?>">
                  </div>
                  <div class="form-group">
                    <label for="tag">文章标签</label>
                    <input type="text" class="form-control" id="tag" name="tag">
                  </div>
                  <div class="form-group">
                    <label for="content">文章内容</label>
                    <div id="article_content">
                      <script id="editor" type="text/plain" style="width:70%;height:300px;"></script>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="img" style="display: block;">特色图片</label>
                    <p>特色图片用于在APP端显示文章列表时附加显示，可与文章中的图片不同。文章中的图片请在编辑文章内容时添加。</p>
                    <div id="up_file">
                      <a class="btn btn-success" id="pickfiles" href="#">
                          <i class="glyphicon glyphicon-plus"></i>
                          <span>添加特色图片</span>
                      </a>
                      <input type="text" name="img_url1" style="display:none" id="img_url1" value=''>
                      <input type="text" name="img_url2" style="display:none" id="img_url2" value=''>
                      <input type="text" name="img_url3" style="display:none" id="img_url3" value=''>
                    </div>
                    <div id="img-container">
                      <img id="img1" src="">
                      <img id="img2" src="">
                      <img id="img3" src="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="initial-url">转载文章请直接提交原文链接<span>（为了更好的用户体验，建议提交方便在移动设备上阅读的文章链接）</span></label>
                    <input type="text" class="form-control" id="initial-url" name="initial-url">
                  </div>
                  <div class="form-group">
                    <label for="additonal" style="display: block;">附加选项</label>
                    <input type="checkbox" name="push" style="display: inline-block;"> 推送
                    <input type="checkbox" name="carousel" style="display: inline-block;"> 轮播
                  </div>
                  <button type="submit" class="btn btn-success" style="float: right; margin-top: 30px;">提交文章</button>
                </form> 
              </div>
            </div>
          </div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="artists">
          <div class="container artists-container">
            <div class="row">
              <div class="col-md-12 col-lg-10 col-lg-offset-1" id="artists-table-container">
                <table class='table table-bordered table-hover table-responsive' width='100%' style="margin-bottom: 0 !important;">
                  <tr>
                      <th width='5%'>序号</th>
                      <th width='25%'>标题</th>
                      <th width='10%'>作者</th>
                      <th width='10%'>标签</th>
                      <th width='25%'>缩略图</th>
                      <th width='12.5%'>编辑</th>
                      <th width='12.5%'>删除</th>
                  </tr>
                </table>
                <table class='table table-bordered table-hover table-responsive' width='100%' id="artists-table" style="margin-bottom: 0"></table> 
                <nav style="text-align: center;">
                  <ul class="pagination" id="pagination">
                    <nav>
                      <ul class="pagination">
                        <li class="disabled">
                          <a href="#" aria-label="Previous" id="previous_button">
                            <span aria-hidden="true">&laquo;</span>
                          </a>
                        </li>
                        <li><a href="#" style="display: none;" class="page" id="page_con1"></a></li>
                        <li><a href="#" style="display: none;" class="page" id="page_con2"></a></li>
                        <li><a href="#" style="display: none;" class="page" id="page_con3"></a></li>
                        <li><a href="#" style="display: none;" class="page" id="page_con4"></a></li>
                        <li><a href="#" style="display: none;" class="page" id="page_con5"></a></li>
                        <li>
                          <a href="#" aria-label="Next" id="next_button">
                            <span aria-hidden="true">&raquo;</span>
                          </a>
                        </li>
                      </ul>
                    </nav>
                  </ul>
                </nav>
              </div> 
            </div>
          </div>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="messages">
          
        </div>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var ueditor = UE.getEditor('editor');
  ueditor.ready(function () {
    <?php
    if ($isUpdate) {
        $str = str_replace("\n", "", $info["content"]);
       $str = str_replace("\r", "", $str);
       echo "ueditor.setContent('".$str."');";
    };
    ?>
  });

  var index=1;
  var uploader = Qiniu.uploader({
    runtimes: 'html5,flash,html4', //上传模式,依次退化
    browse_button: 'pickfiles', //上传选择的点选按钮，**必需**
    uptoken_url: 'getQiniuToken.php?la=la',
    //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
    // uptoken : '<Your upload token>',
    //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
    unique_names: true,
    // 默认 false，key为文件名。若开启该选项，SDK会为每个文件自动生成key（文件名）
    // save_key: true,
    // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK在前端将不对key进行任何处理
    domain: 'http://qiniu-plupload.qiniudn.com/',
    //bucket 域名，下载资源时用到，**必需**
    container: 'up_file', //上传区域DOM ID，默认是browser_button的父元素，
    max_file_size: '100mb', //最大文件体积限制
    flash_swf_url: 'js/plupload/Moxie.swf', //引入flash,相对路径
    max_retries: 3, //上传失败最大重试次数
    dragdrop: false, //开启可拖曳上传
    //drop_element: 'container', //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
    chunk_size: '4mb', //分块上传时，每片的体积
    auto_start: true, //选择文件后自动上传，若关闭需要自己绑定事件触发上传
    init: {
        'FilesAdded': function (up, files) {
            plupload.each(files, function (file) {
                // 文件添加进队列后,处理相关的事情
            });
        },
        'BeforeUpload': function (up, file) {
            // 每个文件上传前,处理相关的事情
        },
        'UploadProgress': function (up, file) {

        },
        'FileUploaded': function (up, file, info) {
          if(index<=3){
            var res = JSON.parse(info);
            var sourceLink = "http://7xnxx0.com1.z0.glb.clouddn.com/" + res.key; // 获取上传成功后的文件的Url
            var imgId="#img"+index;
            var img_url="#img_url"+index;
            $(imgId).css("display", "inline-block");
            $(imgId).attr("src", sourceLink);
            $(img_url).val(sourceLink);
            // var value=$(img_url).
            index=index+1;
          }else{
            alert("不能再上传啦！");
          }
        },
        'Error': function (up, err, errTip) {
            //上传出错时,处理相关的事情
        },
        'UploadComplete': function () {
            //队列文件处理完毕后,处理相关的事情
        },
        'Key': function (up, file) {
            // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
            // 该配置必须要在 unique_names: false , save_key: false 时才生效
            var key = "";
            // do something with key here
            return key;
        }
    }
  });

  $(document).ready(function(){
    var page_num;
    var every_page_artists_num=10; //设置每页显示15条
    $("#artists-list").click(function(){
      $.ajax({
        type:"post",
        url:"getFirstReadingListPage.php",
        dataType:"json",
        data:{
          every_page_artists_num:every_page_artists_num
        },

        success:function(json){
          $("#artists-table").html(function(){
            var result;
            for(var i=0; i<every_page_artists_num; i++){
              result=result
              +"<tr>"
                +"<td width='5%'>"+json.firstpage_artists[i].id+"</td>"
                +"<td width='25%'>"+json.firstpage_artists[i].title+"</td>"
                +"<td width='10%'>"+json.firstpage_artists[i].author+"</td>"
                +"<td width='10%'>"+json.firstpage_artists[i].tag+"</td>"
                +"<td width='25%'>"
                  +"<img width='33%' height='50px' src='"+json.firstpage_artists[i].img_url1+"'>"
                  +"<img width='33%' height='50px' src='"+json.firstpage_artists[i].img_url2+"'>"
                  +"<img width='33%' height='50px' src='"+json.firstpage_artists[i].img_url3+"'>"
                +"</td>"
                +"<td width='12.5%'><a href='edit.php?id="+json.firstpage_artists[i].id+"'>编辑</a></td>"
                +"<td width='12.5%'><a href='deleteReading.php?id="+json.firstpage_artists[i].id+"'>删除</a></td>"
              +"</tr>";
            }
            return result;
          });    

          page_num=json.page_num;
          var pages=$(".page");
          var page_con_tem="#page_con";
          if(page_num<5){
            for(var i=1; i<page_num+1; i++){
              page_con=page_con_tem+i;
              $(page_con).css("display", "inline-block");
              $(page_con).html(i);
            }
          }else{
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).css("display", "inline-block");
              $(page_con).html(i);
            }
          }
          $("#page_con1").parent().addClass("active");
        }
      });
    });  

    $(".page").click(function(){
      var current_page=$(this).html();
      var start=(current_page-1)*every_page_artists_num;
      var parent=$(this).parent();
      $.ajax({
        type:"post",
        url:"getCurrentReadingListPage.php",
        dataType:"json",
        data:{
          start:start,
          every_page_artists_num:every_page_artists_num
        },

        success:function(json){
          $("#artists-table").html(function(){
            var result;
            for(var i=0; i<json.info_artists.length; i++){
              result=result
              +"<tr>"
                +"<td width='5%'>"+json.info_artists[i].id+"</td>"
                +"<td width='25%'>"+json.info_artists[i].title+"</td>"
                +"<td width='10%'>"+json.info_artists[i].author+"</td>"
                +"<td width='10%'>"+json.info_artists[i].tag+"</td>"
                +"<td width='25%'>"
                  +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url1+"'>"
                  +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url2+"'>"
                  +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url3+"'>"
                +"</td>"
                +"<td width='12.5%'><a href='edit.php?id="+json.info_artists[i].id+"'>编辑</a></td>"
                +"<td width='12.5%'><a href='deleteReading.php?id="+json.info_artists[i].id+"'>删除</a></td>"
              +"</tr>";
            }
            return result;
          }); 

          // if(current_page!=1){
          //   $("#previous_button").parent().removeClass("disabled");
          // }else if(current_page==page_num){

          // }

          if(current_page>=4 && page_num-current_page>=2){
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(current_page-3+i);
            }
            $(".page").parent().removeClass("active");
            $("#page_con3").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
          }else if(current_page==2){
            $(".page").parent().removeClass("active");
            $("#page_con2").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(i);
            }
          }else if(current_page==3){
            $(".page").parent().removeClass("active");
            $("#page_con3").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(i);
            }
          }else if(current_page==page_num-1 && current_page!=2 && current_page!=3){
            $(".page").parent().removeClass("active");
            $("#page_con4").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(current_page-4+i);
            }
          }else if(current_page==page_num){
            $(".page").parent().removeClass("active");
            $("#page_con5").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(current_page-5+i);
            }
          }else{
            $(".page").parent(".active").removeClass("active");
            parent.addClass("active");
            $("#previous_button").parent().removeClass("disabled");
          }  
        }
      });
    });

    $("#previous_button").click(function(){
      var current_page=$(".pagination .active").children().html()-1;
      var page_id="#page_con"+current_page;
      console.log(current_page);
      // var current_page=$(this).html()-1;
      var start=(current_page-1)*every_page_artists_num;
      var parent=$(page_id).parent();
      $.ajax({
        type:"post",
        url:"getCurrentReadingListPage.php",
        dataType:"json",
        data:{
          start:start,
          every_page_artists_num:every_page_artists_num
        },

        success:function(json){
          $("#artists-table").html(function(){
            var result;
            for(var i=0; i<json.info_artists.length; i++){
              result=result
              +"<tr>"
                +"<td width='5%'>"+json.info_artists[i].id+"</td>"
                +"<td width='25%'>"+json.info_artists[i].title+"</td>"
                +"<td width='10%'>"+json.info_artists[i].author+"</td>"
                +"<td width='10%'>"+json.info_artists[i].tag+"</td>"
                +"<td width='25%'>"
                  +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url1+"'>"
                  +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url2+"'>"
                  +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url3+"'>"
                +"</td>"
                +"<td width='12.5%'><a href='edit.php?id="+json.info_artists[i].id+"'>编辑</a></td>"
                +"<td width='12.5%'><a href='deleteReading.php?id="+json.info_artists[i].id+"'>删除</a></td>"
              +"</tr>";
            }
            return result;
          }); 

          if(current_page>=4 && page_num-current_page>=2){
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(current_page-3+i);
            }
            $(".page").parent().removeClass("active");
            $("#page_con3").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
          }else if(current_page==2){
            $(".page").parent().removeClass("active");
            $("#page_con2").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(i);
            }
          }else if(current_page==3){
            $(".page").parent().removeClass("active");
            $("#page_con3").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(i);
            }
          }else if(current_page==page_num-1 && current_page!=2 && current_page!=3){
            $(".page").parent().removeClass("active");
            $("#page_con4").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(current_page-4+i);
            }
          }else if(current_page==page_num){
            $(".page").parent().removeClass("active");
            $("#page_con5").parent().addClass("active");
            $("#previous_button").parent().removeClass("disabled");
            var page_con_tem="#page_con";
            for(var i=1; i<6; i++){
              page_con=page_con_tem+i;
              $(page_con).html(current_page-5+i);
            }
          }else{
            $(".page").parent(".active").removeClass("active");
            parent.addClass("active");
            $("#previous_button").parent().removeClass("disabled");
          }  
        }
      });
    });

    $("#next_button").click(function(){
      var current_page=$(".pagination .active").children().html()-1+2;
      if(current_page<=page_num){
        var page_id="#page_con"+current_page;
        console.log(current_page);
        // var current_page=$(this).html()-1;
        var start=(current_page-1)*every_page_artists_num;
        var parent=$(page_id).parent();
        $.ajax({
          type:"post",
          url:"getCurrentReadingListPage.php",
          dataType:"json",
          data:{
            start:start,
            every_page_artists_num:every_page_artists_num
          },

          success:function(json){
            $("#artists-table").html(function(){
              var result;
              for(var i=0; i<json.info_artists.length; i++){
                result=result
                +"<tr>"
                  +"<td width='5%'>"+json.info_artists[i].id+"</td>"
                  +"<td width='25%'>"+json.info_artists[i].title+"</td>"
                  +"<td width='10%'>"+json.info_artists[i].author+"</td>"
                  +"<td width='10%'>"+json.info_artists[i].tag+"</td>"
                  +"<td width='25%'>"
                    +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url1+"'>"
                    +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url2+"'>"
                    +"<img width='33%' height='50px' src='"+json.info_artists[i].img_url3+"'>"
                  +"</td>"
                  +"<td width='12.5%'><a href='edit.php?id="+json.info_artists[i].id+"'>编辑</a></td>"
                  +"<td width='12.5%'><a href='deleteReading.php?id="+json.info_artists[i].id+"'>删除</a></td>"
                +"</tr>";
              }
              return result;
            }); 
            
            if(current_page>=4 && page_num-current_page>=2){
              var page_con_tem="#page_con";
              for(var i=1; i<6; i++){
                page_con=page_con_tem+i;
                $(page_con).html(current_page-3+i);
              }
              $(".page").parent().removeClass("active");
              $("#page_con3").parent().addClass("active");
              $("#previous_button").parent().removeClass("disabled");
            }else if(current_page==2){
              $(".page").parent().removeClass("active");
              $("#page_con2").parent().addClass("active");
              $("#previous_button").parent().removeClass("disabled");
              var page_con_tem="#page_con";
              for(var i=1; i<6; i++){
                page_con=page_con_tem+i;
                $(page_con).html(i);
              }
            }else if(current_page==3){
              $(".page").parent().removeClass("active");
              $("#page_con3").parent().addClass("active");
              $("#previous_button").parent().removeClass("disabled");
              var page_con_tem="#page_con";
              for(var i=1; i<6; i++){
                page_con=page_con_tem+i;
                $(page_con).html(i);
              }
            }else if(current_page==page_num-1 && current_page!=2 && current_page!=3){
              $(".page").parent().removeClass("active");
              $("#page_con4").parent().addClass("active");
              $("#previous_button").parent().removeClass("disabled");
              var page_con_tem="#page_con";
              for(var i=1; i<6; i++){
                page_con=page_con_tem+i;
                $(page_con).html(current_page-4+i);
              }
            }else if(current_page==page_num){
              $(".page").parent().removeClass("active");
              $("#page_con5").parent().addClass("active");
              $("#previous_button").parent().removeClass("disabled");
              var page_con_tem="#page_con";
              for(var i=1; i<6; i++){
                page_con=page_con_tem+i;
                $(page_con).html(current_page-5+i);
              }
            }else{
              $(".page").parent(".active").removeClass("active");
              parent.addClass("active");
              $("#previous_button").parent().removeClass("disabled");
            }  
          }
        });
      }
    });
  });
    

  
</script>
</body>
</html>