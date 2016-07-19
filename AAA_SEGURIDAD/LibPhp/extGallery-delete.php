<?php
/**
 *
 */
include('GenUti.inc.php');
include_once('extGallery-config.php');

$arrayImg  = explode(";", $_POST['images']);

foreach($arrayImg as $imgname) {
    if ($imgname != "") {
        unlink($dir_to_scan.$imgname);
        unlink($dir_base.'/thumbs/thumb_'.$imgname);
    }
}

echo '{success: true}';
