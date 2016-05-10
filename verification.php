<?php
//Function
function save($result)
{
    session_start();
    $_SESSION["verification"]=$result;
}
function swap(&$a, &$b)
{
    list($a,$b)=array($b,$a);
}

//Parameter
$height=isset($_GET["height"])?abs(intval($_GET["height"])):100;
$width=isset($_GET["width"])?abs(intval($_GET["width"])):300;
/*debug
$number_max=isset($_GET["number"])?abs(intval($_GET["number"])):5;
$mass=isset($_GET["mass"])?intval($_GET["mass"]):100;
$line=isset($_GET["line"])?intval($_GET["line"]):5;
$fontScaling=isset($_GET["scale"])?abs(floatval($_GET["scale"])):0.8;
$fontRotate=isset($_GET["rotate"])?abs(intval($_GET["rotate"])):20;
$dashLength=isset($_GET["dash"])?abs(intval($_GET["dash"])):10;
$spacingMin=isset($_GET["marginMin"])?abs(floatval($_GET["marginMin"])):0.5;
$spacingMax=isset($_GET["marginMax"])?abs(floatval($_GET["marginMax"])):1.3;
$gridMin=isset($_GET["gridMin"])?abs(intval($_GET["gridMin"])):1;
$gridMax=isset($_GET["gridMax"])?abs(intval($_GET["gridMax"])):15;
*/
$number_max=5;
$mass=100;
$line=5;
$fontScaling=0.8;
$fontRotate=20;
$dashLength=10;
$spacingMin=0.5;
$spacingMax=1.3;
$gridMin=1;
$gridMax=15;
$extra="!@#$%^*?~";
$fontStyle=array(
    "assets/arial.ttf",
    "assets/consola.ttf",
    "assets/consolab.ttf",
    "assets/consolai.ttf",
    "assets/consolaz.ttf",
    "assets/wbfont.ttf"
);
$bgTop=100;
$fontBot=150;
if($height>$width) {
    swap($height,$width);
}
if($spacingMin>$spacingMax) {
    swap($spacingMin,$spacingMax);
}

//CharacterSet
$range=[];
for($i=0;$i<=9;$i++) {    //0~9
    $range[]=$i;
}
for($i=65;$i<=90;$i++) {    //A~Z
    $range[]=sprintf("%c", $i);
}
for($i=0;$i<strlen($extra);$i++) {    //extra
    $range[]=$extra[$i];
}

//RandomString
$number="";
for($i=0;$i<$number_max;$i++) {
    $number.=$range[rand(0, count($range)-1)];
}
save($number);

//ImageInitialize
Header("Content-type: image/PNG");
$img=imagecreatetruecolor($width, $height);
$bgColor=ImageColorAllocate($img, rand(0, $bgTop), rand(0, $bgTop), rand(0, $bgTop));
$fontColor=ImageColorAllocate($img, rand($fontBot, 255), rand($fontBot, 255), rand($fontBot, 255));
if(rand(0,1)==1) {
    swap($bgColor,$fontColor);
}

//ImageBackground
imagefill($img,0,0,$bgColor);

//Line
$style=[];
for($i=0;$i<$dashLength;$i++) {
    $style[]=$fontColor;
}
for($i=0;$i<$dashLength;$i++) {
    $style[]=IMG_COLOR_TRANSPARENT;
}
imagesetstyle($img, $style);    //set dash
for($i=0;$i<$line;$i++) {
    if(rand(0,1)==0) {    //straight line
        $x1=rand(0,$width);
        $x2=rand(0,$width);
        $y1=rand(0,$height);
        $y2=rand(0,$height);
        if($x1>$x2) {
            swap($x1,$x2);
        }
        if($y1>$y2) {
            swap($y1,$y2);
        }
        imageline($img, $x1, $y1, $x2, $y2, IMG_COLOR_STYLED);
    }
    else {                //circular arc
        $cx=rand(0,$width);
        $cy=rand(0,$height);
        $w=rand(0,$width);
        $h=rand(0,$height);
        $start=rand(0,360);
        $end=rand(0,360);
        if($start>$end) {
            swap($start,$end);
        }
        imagearc($img, $cx, $cy, $w, $h, $start, $end, IMG_COLOR_STYLED);
    }
}

//Dot
for($i=0;$i<$mass;$i++) {
    imagesetpixel($img, rand(0,$width), rand(0,$height), $fontColor);
}

//Grid
$gridTop=rand($gridMin,$gridMax);
$gridBottom=rand($gridMin,$gridMax);
while($gridTop<$width||$gridBottom<$width) {
    imageline($img, $gridTop, 0, $gridBottom, $height, $fontColor);
    $gridTop+=rand($gridMin,$gridMax);
    $gridBottom+=rand($gridMin,$gridMax);
}
$gridLeft=rand($gridMin,$gridMax);
$gridRight=rand($gridMin,$gridMax);
while($gridLeft<$height||$gridRight<$height) {
    imageline($img, 0, $gridLeft, $width, $gridRight, $fontColor);
    $gridLeft+=rand($gridMin,$gridMax);
    $gridRight+=rand($gridMin,$gridMax);
}

//String
if($number_max<=0) {
    imagepng($img);
    imagedestroy($img);
    exit;
}
$fontSize=intval($fontScaling*$width/$number_max);
$spacing=($width-$fontSize*$number_max)/$number_max+1;
$strx=rand(intval($spacing*$spacingMin),intval($spacing*$spacingMax));
for($i=0;$i<$number_max;$i++) {
    $strpos=rand($fontSize,$height);
    imagettftext($img, $fontSize, rand(-$fontRotate,$fontRotate), $strx, $strpos, $fontColor, $fontStyle[rand(0,count($fontStyle)-1)], $number[$i]);
    $strx+=rand(intval($spacing*$spacingMin),intval($spacing*$spacingMax))+$fontSize;
}

//Finish
imagepng($img);
imagedestroy($img);
