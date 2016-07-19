<?php
include_once('genFire.php');
$fp = FirePHP::getInstance(true);
function debugOut($pLab, $pDat, $pMod = "log"){
	if(fGetParam('pAppDbg', true)){
		global $fp;
		  if (isset($fp)){
			 $fp->log($pDat, $pLab);
		  }
	   }
	}
?>