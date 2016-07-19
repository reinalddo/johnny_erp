<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<base target="_self">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta>
<link rel="stylesheet" href="./Themes/layersmenu-demo.css" type="text/css"></link>
<link rel="stylesheet" href="./Themes/layerstreemenu.css" type="text/css"></link>
<link rel="shortcut icon" href="LOGOS/shortcut_icon_phplm.png"></link>
<title>PRUEBA ARBOL CUENTAS</title>

<?php include ("libjs/layersmenu-browser_detection.js"); ?>

<script language="JavaScript" type="text/javascript" src="libjs/layerstreemenu-cookies.js"></script>
<?php
session_start();
include_once ("General.inc.php");      					// Definiciones generales
include ("lib/PHPLIB.php");
include ("lib/layersmenu-common.inc.php");
include ("lib/treemenu.inc.php");
include ("../LibPhp/SegLib.php");
include ("../De_Files/Cabecera_fah.php");                 // Cabecera no generada por CCS
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
<?php
?>
    <table align="center">
        <tr>
            <td valign="top">
                <div class="normalbox" style="background-color:#FFFFF7">
                    <div class="normalbox" width="400" align="left" style=" width:400; background-color:#FFFFF7">
                        <CENTER>ARBOL DE CUENTAS <BR>
                        		Presione sobre una Cuenta para Ver / Editar su contenido
                        </CENTER>
                    </div>
                </div>
                <div class="normalbox" align="left">
<?php

    echo $mid->getTreeMenu("treemenu1");
?>
                </div>
            </td>
            <td align="top">
                <iframe valign="center" width="400" height="400" name="myIframe1" src="../Co_Files/CoAdCu_mant.php" frameborder=0>
                </iframe>
            </td>
        </tr>
    </table>
</html>
