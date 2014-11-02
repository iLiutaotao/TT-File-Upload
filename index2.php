<?php
$uptypes=array('image/jpg',  //上传文件类型列表
 'image/jpeg',
 'image/png',
 'image/pjpeg',
 'image/gif',
 'image/bmp',
 'application/x-shockwave-flash',
 'image/x-png',
 'application/msword',
 'audio/x-ms-wma',
 'audio/mp3',
 'application/vnd.rn-realmedia',
 'application/x-zip-compressed',
 'application/octet-stream');
 
 $images=array('image/jpg',  //上传文件类型列表
 'image/jpeg',
 'image/png',
 'image/pjpeg',
 'image/gif',
 'image/bmp');

$max_file_size=20971520;   //上传文件大小限制, 单位BYTE
$path_parts=pathinfo($_SERVER['PHP_SELF']); //取得当前路径
$destination_folder="up/"; //上传文件路径
$watermark=0;   //是否附加水印(1为加水印,其他为不加水印);
$watertype=1;   //水印类型(1为文字,2为图片)
$waterposition=1;   //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
$waterstring=""; //水印字符串
$waterimg="";  //水印图片
$imgpreview=1;   //是否生成预览图(1为生成,其他为不生成);
$imgpreviewsize=1/2;  //缩略图比例
?>
<script type="text/javascript">function oCopy(obj){obj.select();js=obj.createTextRange();js.execCommand("Copy");};function sendtof(url){window.clipboardData.setData('Text',url);alert('复制地址成功，粘贴给你好友一起分享。');};function select_format(){var on=document.getElementById('fmt').checked;document.getElementById('site').style.display=on?'none':'';document.getElementById('sited').style.display=!on?'none':'';};var flag=false;function DrawImage(ImgD){var image=new Image();image.src=ImgD.src;if(image.width>0&&image.height>0){flag=true;if(image.width/image.height>=120/80){if(image.width>120){ImgD.width=120;ImgD.height=(image.height*120)/image.width;}else {ImgD.width=image.width;ImgD.height=image.height;};ImgD.alt=image.width+"×"+image.height;}else {if(image.height>80){ImgD.height=80;ImgD.width=(image.width*80)/image.height;}else {ImgD.width=image.width;ImgD.height=image.height;};ImgD.alt=image.width+"×"+image.height;}};};function FileChange(Value){flag=false;document.all.uploadimage.width=10;document.all.uploadimage.height=10;document.all.uploadimage.alt="";document.all.uploadimage.src=Value;};</script>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我是光-文件上传-普通方式</title>
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
                <header><h2>我是光-文件上传-普通方式</h2></header>
                <hr />
                <ul style="list-style-type: none;">
                    <li><i class="icon-ok"></i> 不必注册帐号，不必登录</li>
                    <li><i class="icon-ok"></i> 直链下载，无广告，无需等待</li>
                    <li><i class="icon-ok"></i> 清爽无图界面，无须Flash，同样适合手机访问</li>
					<li><i class="icon-ok"></i>允许最大大小为：20MB</li>
                </ul>
            </header>
            <?php echo $htmlOut;?>
            <section class='box well'>
                <form id="fileForm" method="post" enctype="multipart/form-data" name="upform">
				<input name=upfile type=file onchange="javascript:FileChange(this.value);">
                    <input type="submit" name="files[]" /><br />
                </form>
				<p>允许上传的文件类型为:jpg|jpeg|gif|bmp|png|swf|mp3|wma|zip|rar|doc</p>
                <hr />
            </section>
             <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if (!is_uploaded_file($_FILES["upfile"][tmp_name]))
//是否存在文件
{
echo "<font color='red'>文件不存在！</font>";
exit;
}
 $file = $_FILES["upfile"];
 if($max_file_size < $file["size"])
 //检查文件大小
 {
 echo "<font color='red'>文件太大！</font>";
 exit;
  }
if(!in_array($file["type"], $uptypes))
//检查文件类型
{
 echo "<font color='red'>不能上传此类型文件！</font>";
 exit;
}
if(!file_exists($destination_folder))
mkdir($destination_folder);

$filename=$file["tmp_name"];
$image_size = getimagesize($filename);
$pinfo=pathinfo($file["name"]);
$ftype=$pinfo[extension];
$destination = $destination_folder.time().".".$ftype;
if (file_exists($destination) && $overwrite != true)
{
     echo "<font color='red'>同名文件已经存在了！</a>";
     exit;
  }
 if(!move_uploaded_file ($filename, $destination))
 {
   echo "<font color='red'>移动文件出错！</a>";
     exit;
  }
$pinfo=pathinfo($destination);
$fname=$pinfo[basename];
echo "<div id=\"site\"><table border=\"0\"><tr><td valign=\"top\">文件地址:</td><td><input type=\"text\" onclick=\"sendtof(this.value)\" onmouseover=\"oCopy(this)\" style=font-size=9pt;color:blue size=\"44\" value=\"http://".$_SERVER['SERVER_NAME'].$path_parts["dirname"]."/".$destination_folder.$fname."\"/>
</td></tr></table></div><div id=\"sited\" style=\"display:none\"><table border=\"0\"><tr><td valign=\"top\">文件地址:</td></tr></table></div>";
if(in_array($file["type"], $images)){
echo "宽度:".$image_size[0]."</br>";
echo " 长度:".$image_size[1]."</br>";
}
if($watermark==1)
{
$iinfo=getimagesize($destination,$iinfo);
$nimage=imagecreatetruecolor($image_size[0],$image_size[1]);
$white=imagecolorallocate($nimage,255,255,255);
$black=imagecolorallocate($nimage,0,0,0);
$red=imagecolorallocate($nimage,255,0,0);
imagefill($nimage,0,0,$white);
switch ($iinfo[2])
{
 case 1:
 $simage =imagecreatefromgif($destination);
 break;
 case 2:
 $simage =imagecreatefromjpeg($destination);
 break;
 case 3:
 $simage =imagecreatefrompng($destination);
 break;
 case 6:
 $simage =imagecreatefromwbmp($destination);
 break;
 default:
 die("<font color='red'>不能上传此类型文件！</a>");
 exit;
}

imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]);
imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white);

switch($watertype)
{
 case 1:  //加水印字符串
 imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black);
 break;
 case 2:  //加水印图片
 $simage1 =imagecreatefromgif("xplore.gif");
 imagecopy($nimage,$simage1,0,0,0,0,85,15);
 imagedestroy($simage1);
 break;
}

switch ($iinfo[2])
{
 case 1:
 //imagegif($nimage, $destination);
 imagejpeg($nimage, $destination);
 break;
 case 2:
 imagejpeg($nimage, $destination);
 break;
 case 3:
 imagepng($nimage, $destination);
 break;
 case 6:
 imagewbmp($nimage, $destination);
 //imagejpeg($nimage, $destination);
 break;
}

//覆盖原上传文件
imagedestroy($nimage);
imagedestroy($simage);
}

if($imgpreview==1)
{
echo "<br>图片预览:<br>";
echo "<a href=\"".$destination."\" target='_blank'><img src=\"".$destination."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);
echo " alt=\"图片预览:\r文件名:".$fname."\r上传时间:".date('m/d/Y h:i')."\" border='0'></a>";
}
}
?>
            <section class='box well'>
                <ul style="list-style-type:none;">
                    <li>作者 <a href="http://www.liujiantao.me/" target="_brank">LiuJianTao</a></li>
					<center><div class="btn btn-default">©2014 · Powered by <a href="http://www.woshiguang.com/" target="_blank">WoShiGuang</a></div></center>
                </ul>
            </section>
        </div>
    </div>
</div>
<script type='text/javascript' src='style/js/jquery.min.js'></script>
<script type='text/javascript' src='style/js/bootstrap.min.js'></script>

</body>
</html>