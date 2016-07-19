<?php
/**
 *
 */
include_once('GenUti.inc.php');
include_once('extGallery-config.php');

$images = array();
debugOut("GET IMAGES PARAM", $_GET);
debugOut("Dir", $dir_to_scan);
$d = dir($dir_to_scan);
//$d=dir("/var/www/AAA/AAA_7_3/Rh_Files/imagenes/images");
//$d=dir("../Rh_Files/imagenes/images");
debugOut("Dir-", $d);

while($name = $d->read()){
	$alInfo = pathinfo($dir_to_scan.$name);
	debugOut("pathinfo-", $alInfo);
	//date("F d Y H:i:s.",fileatime("test.txt")
    if(!preg_match('/\.(jpg|Jpg|JPG|gif|png)$/', $name)) continue;
    $size = filesize($dir_to_scan.$name);
    $lastmod = filemtime($dir_to_scan.$name); //*1000;
    $thumb = "thumb_".$name;
    $images[] = array('name' => $name,
			'size' => $size,
			'lastmod' => $lastmod,
			'url' => $dir.$name,
            'thumb_url' => $dir_thumbs.$thumb);
}
$d->close();
$o = array('images'=>$images);
echo json_encode($o);
