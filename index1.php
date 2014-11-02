<?php
define("ROOT", dirname(__FILE__));

include ROOT . "/Qiniu/Client.php";
\Qiniu\Client::registerAutoloader();

$bucket = "空间名称";
$QiniuBaseUrl = "绑定的域名";
$QiniuAccessKey = 'Access Key';
$QiniuSecretKey = 'Secret Key';

$config = array('access_key' => $QiniuAccessKey,'secret_key' => $QiniuSecretKey);
$sdk = new \Qiniu\Client($config);

ob_start();

$htmlFileTableHead = <<< HTML
<section class='box well'>
  <header><h2>已上传文件列表</h2></header>
  <table class="table table-striped table-bordered table-condensed">
HTML;

$htmlFileTableFooter = <<< HTML
  </table>
</section>
HTML;

switch($_SERVER["REQUEST_METHOD"])
{
    case "POST":
        if(isset($_FILES["files"]))
        {
            echo $htmlFileTableHead;
            foreach($_FILES["files"]["error"] as $key => $error)
            {
                if($error == UPLOAD_ERR_OK)
                {
                    $tmpName = $_FILES["files"]["tmp_name"][$key];
                    $fileName = $_FILES["files"]["name"][$key];
                    $fileMD5 = md5_file($_FILES["files"]["tmp_name"][$key]);

                    $params = array('scope' => $bucket,'expires' => 3600);
                    $body = array('file' => '@' . $tmpName);
                    list($return, $error) = $sdk->putFile($bucket, $fileMD5, $body, $params);
                    if ($error !== null) {
                        $error_arr = json_encode($error);
                        echo "上传发生错误：{$error_arr['error']}，请稍后再试。";
                    } else {
                        $fileSize = ceil($_FILES["files"]["size"][$key] / 1024) . "KB";
                        $fileType = $_FILES["files"]["type"][$key];
                        $fileURL = "http://{$QiniuBaseUrl}/{$fileMD5}";
                        $fileURL = "<a href='{$fileURL}' target='_blank'>{$fileURL}</a>";

                        echo <<< HTML
                  <tr>
                    <td>{$fileName}</td>
                    <td>{$fileType}</td>
                    <td>{$fileSize}</td>
                    <td>{$fileURL}</td>
                  </tr>
HTML;
                    }
                }
            }
            echo $htmlFileTableFooter;
        }
        break;
}

$htmlOut = ob_get_clean();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我是光-文件上传-七牛</title>
    <link href="style/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="../bootstrap/css/style.css" rel="stylesheet">
</head>
<body style='font-family: "WenQuanYi Micro Hei", "WenQuanYi Zen Hei", "Microsoft YaHei", arial, sans-serif; font-size: 16px;margin: 30px auto;'>
<div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="../index.php">首页</a></li>
            <li><a href="http://www.woshiguang.com/" target="_blank">我是光</a></li>
			<li><a href="http://www.liujiantao.me/" target="_blank">涛涛的博客</a></li>
			<li><a href="../fan/index.php">WordPress版权保护机制</a></li>
			<li><a href="http://sign.liujiantao.me/" target="_blank">贴吧云签到</a></li>
			<li><a href="../yun/index.php">百度网盘真实地址获取器</a></li>
			<li><a href="../ui/index.php">BootStrap响应式布局系统</a></li>
			<li><a href="../upload/index.php">文件上传</a></li>
          </ul>
        </div>
      </div>
<div class="container">
    <div class="row">
        <div class="span12">
            <header class="box well">
                <header><h2>我是光-文件上传-七牛方式</h2></header>
                <hr />
                <ul style="list-style-type: none;">
                    <li><i class="icon-ok"></i> 不必注册帐号，不必登录</li>
                    <li><i class="icon-ok"></i> CDN加速下载，无广告，无需等待</li>
                    <li><i class="icon-ok"></i> 清爽无图界面，无须Flash，同样适合手机访问</li>
					<li><i class="icon-ok"></i> 无文件大小限制</li>
                </ul>
            </header>
            <?php echo $htmlOut;?>
            <section class='box well'>
                <form id="fileForm" method="post" enctype="multipart/form-data">
                    <input type="file" name="files[]" /><br />
                </form>
                <hr />
                <button id='addFile' class='btn btn-info'><i class="icon-plus icon-white"></i> 添加新的上传框</button>
                <button id='start' class='btn btn-success'><i class="icon-play icon-white"></i> 开始上传</button>
            </section>
            <section class='box well'>
                <ul style="list-style-type:none;">
                    <li>作者 <a href="http://www.liujiantao.me/" target="_brank">LiuJianTao</a></li>
					<li><a href="https://portal.qiniu.com/signup?code=3lbqowve04spe" target="_blank"><img src="http://www.liujiantao.me/wp-content/uploads/2014/10/qiniu-transparent.png" /></a></li>
					<center><div class="btn btn-default">©2014 · Powered by <a href="http://www.woshiguang.com/" target="_blank">WoShiGuang</a></div></center>
                </ul>
            </section>
        </div>
    </div>
</div>
<script type='text/javascript' src='style/js/jquery.min.js'></script>
<script type='text/javascript' src='style/js/bootstrap.min.js'></script>
<script type="text/javascript">
    $("#addFile").click(function(){
        $("#fileForm").append('<input type="file" name="files[]" /><br />');
    });
    $("#start").click(function(){
        $("#fileForm").submit();
    });
</script>
</body>
</html>
