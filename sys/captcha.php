<?php
session_start();
header("Content-type: image/gif");
$code = rand(1000, 9999);///Случайное число
$color1 = rand (10, 200);
$color2 = rand (10, 200);
$color3 = rand (10, 200);
 
$_SESSION['code'] = $code;
 
$rgb=0xDAF0F2;//цвет заливки
$txt_box=imagettfbbox(18, 0, "../tmp/style/Graffiti1CTT.ttf", $code);
if($txt_box[0]<0){$txt_box[0]=$txt_box[0]*(-1);}
if($txt_box[1]<0){$txt_box[1]=$txt_box[1]*(-1);}
if($txt_box[2]<0){$txt_box[2]=$txt_box[2]*(-1);}
if($txt_box[3]<0){$txt_box[3]=$txt_box[3]*(-1);}
if($txt_box[4]<0){$txt_box[4]=$txt_box[4]*(-1);}
if($txt_box[5]<0){$txt_box[5]=$txt_box[5]*(-1);}
if($txt_box[6]<0){$txt_box[6]=$txt_box[6]*(-1);}
if($txt_box[7]<0){$txt_box[7]=$txt_box[7]*(-1);}
$box_width=($txt_box[0]+$txt_box[2]+$txt_box[4]+$txt_box[6])/2;
$box_height=($txt_box[1]+$txt_box[3]+$txt_box[5]+$txt_box[7])/2;
$img = imagecreatetruecolor($box_width+1,$box_height+1);//вспомагательное изображение
imagefill($img, 0, 0, $rgb);//заливаем его ...
imagettftext($img, 18, 0, 1, $box_height-2, imageColorAllocate($img, $color1,$color2,$color3), "../tmp/style/Graffiti1CTT.ttf", $code);
imagegif($img);//готово к употреблению
//убираем после себя
imagedestroy($img);
?>