<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<base target="_self">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta>
<link rel="stylesheet" href="./Themes/layersmenu-demo.css" type="text/css"></link>
<link rel="stylesheet" href="./Themes/layerstreemenu.css" type="text/css"></link>
<link rel="shortcut icon" href="LOGOS/shortcut_icon_phplm.png"></link>
<title>TREE MENU</title>
<?php include ("libjs/layersmenu-browser_detection.js"); ?>
<script language="JavaScript" type="text/javascript" src="libjs/layerstreemenu-cookies.js"></script>
<?php
session_start();
include_once ("General.inc.php");      					// Definiciones generales
include ("lib/PHPLIB.php");
include ("lib/layersmenu-common.inc.php");
include ("lib/treemenu.inc.php");
$mid = new TreeMenu();
$mid->setDBConnParms(DATOS_CON);
$mid->setTableName("concuentas");
$mid->setTableFields(array(
	"id"		=> "cue_ID",
	"parent_id"	=> "cue_Padre",
	"text"		=> "concat(cue_codCuenta, '.&nbsp;&nbsp;&nbsp;&nbsp;', cue_Descripcion)",
	"href"		=> "concat('../Co_Files/CoAdCu_mant.php' , '?' , 'Cue_ID=', cue_ID)",
	"title"		=> "",
	"icon"		=> "",
//	"icon"		=> "''",	// this is an alternative to the line above
	"target"	=> "'myIframe1'",
	"orderfield"	=> "cue_padre",
	"expanded"	=> "0"));

$mid->scanTableForMenu("treemenu1");
$mid->newTreeMenu("treemenu1");
?>

</head>
<body style="background-color:#FFFFF7">
<div class="normalbox" style="background-color:#FFFFF7">
<div class="normalbox" align="left" style="background-color:#FFFFF7">
<CENTER>ARBOL DE CUENTAS <BR>
		Presione sobre una Cuenta para Ver / Editar su contenido
</CENTER>
</div>
</div>
<div class="normalbox" align="left">
<?php

$mid->printTreeMenu("treemenu1");
?>
</div>

  </body>
</html>
