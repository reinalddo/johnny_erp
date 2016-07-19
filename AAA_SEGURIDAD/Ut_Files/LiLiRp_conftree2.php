
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<base target="_self">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta>
<link rel="stylesheet" href="../Ut_Files/Themes/layersmenu-demo.css" type="text/css"></link>
<link rel="stylesheet" href="../Ut_Files/Themes/layerstreemenu.css" type="text/css"></link>
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
<link rel="shortcut icon" href="../Ut_Files/LOGOS/shortcut_icon_phplm.png"></link>
<title>CONFIGURACIONDE REPORTES DE LIQUIDACION</title>
<STYLE TYPE="text/css">
  iframe { z-index:-1 }
</STYLE>
<?php
//    include ("../De_Files/Cabecera_fah_2.php");
    session_start();
    include_once ("General.inc.php");      					// Definiciones generales
//    include_once ("../Common.php");
    include ("../Ut_Files/libjs/layersmenu-browser_detection.js");
//    include ("../LibPhp/SegLib.php");

?>
<script language="JavaScript" src="../LibJava/menu_func.js"></script>
<script language="JavaScript" type="text/javascript" src="../Ut_Files/libjs/layerstreemenu-cookies.js"></script>
</head>
<body style="background-color:#FFFFF7">
<?php
include (UT_DIR . "/lib/PHPLIB.php");
include (UT_DIR . "/lib/layersmenu-common.inc.php");
include (UT_DIR . "/lib/treemenu.inc.php");
//include ("../De_Files/Cabecera_fah.php");                 // Cabecera no generada por CCS
$mid = new TreeMenu();
$mid->setDirroot("../Ut_Files");
$mid->setDBConnParms(DATOS_CON);
$mid->setTableName("liqreportes LEFT JOIN liqrubros ON rub_codrubro = rep_codrubro");

$mid->setTableFields(array(
	"id"		=> "rep_secuencia",
	"parent_id"	=> "rep_PadreID",
	"text"		=> "ifnull(rep_TitLargo, rub_desclarga)",
	"href"		=> "concat('../Li_Files/LiLiRp_confdet.php' , '?' ,
                           'rep_PadreID=', rep_SECUENCIA,
                           '&rep_ReporteID=', rep_ReporteID,
                           '&rep_Nivel=', rep_Nivel + 1,
                           '&titulo=', rep_TitLargo
                            )",

/*	"href"		=> "concat('../Li_Files/LiLiRp_confdet.php' , '?' , 'rep_PadreID=', rep_PadreID,
                           if(rep_gruRubro<>0, concat('&rep_GruRubro=', rep_GruRubro),''),
                           (if(rep_Columna<>0, concat('&rep_Columna=', rep_Columna),'') )
                            q)",
*/
	"title"		=> "",
	"icon"		=> "",
//	"icon"		=> "''",	// this is an alternative to the line above
	"target"	=> "'myIframe1'",
	"orderfield"	=> "rep_ReporteID, rep_PadreID, rep_posOrdinal",
	"expanded"	=> "1"));

$mid->scanTableForMenu("treemenu1");
$mid->newTreeMenu("treemenu1");
?>
    <table align="center">
        <tr>
            <td valign="top">
                <div class="normalbox" style="background-color:#FFFFF7">
                    <div class="normalbox" width="400" align="left" style=" width:400; background-color:#FFFFF7">
                        <CENTER>ESTRUCTURA <BR>DE REPORTES
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
                <iframe valign="center" width="750" height="900" name="myIframe1" src="" frameborder=0>
                </iframe>
            </td>
        </tr>
    </table>
</html>
