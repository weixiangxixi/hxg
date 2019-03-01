<?php

// $data = file_get_contents("http://qr.topscan.com/api.php?bg=ffffff&fg=000000&el=l&w=220&m=10&text=http://m.xx.com/index.php/mobile/user/register/5769AlRWVgMFUQcCBlsEVQRYA1ZQUFgFCl1cAlFVBw/");
// file_put_contents('qrcode/a.jpg', $data);
$id = intval($_GET['id']);
$file = "statics/templates/yungou/images/mobile/qrcode/".$id.".jpg";
$dst_path = 'tgt.jpg';
$src_path = $file;
//创建图片的实例
$dst = imagecreatefromstring(file_get_contents($dst_path));
$src = imagecreatefromstring(file_get_contents($src_path));

//获取水印图片的宽高
list($src_w, $src_h) = getimagesize($src_path);
//将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
imagecopymerge($dst, $src, 122, 180, 0, 0, $src_w, $src_h, 100);
//如果水印图片本身带透明色，则使用imagecopy方法
//imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);
//输出图片
list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
switch ($dst_type) {
    case 1://GIF
        header('Content-Type: image/gif');
        imagegif($dst);
        break;
    case 2://JPG
        header('Content-Type: image/jpeg');
        imagejpeg($dst);
        break;
    case 3://PNG
        header('Content-Type: image/png');
        imagepng($dst);
        break;
    default:
        break;
}
imagedestroy($dst);
imagedestroy($src);