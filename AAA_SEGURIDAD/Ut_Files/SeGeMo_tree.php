<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<META HTTP-EQUIV="Content-Style-Type" CONTENT="text/css">
 	<?php
    	include ("libjs/layersmenu-browser_detection.js");
    ?>
	<title>MODULOS</title>
	<script language="JavaScript" type="text/javascript" src="libjs/layerstreemenu-cookies.js"></script>
	<?php
	    session_start();
        include_once ("General.inc.php");      					// Definiciones generales
        include_once ("lib/PHPLIB.php");                         // Templates manager
        include_once ("lib/layersmenu-common.inc.php");          // Funciones Comunes de Arbol	
	    include_once ("lib/treemenu.inc.php");                   // Clase arbol
	    $mid = new TreeMenu();
	    $mid->setDBConnParms(SEGUR_CON);
	    $mid->setTableName("segmodulos");
	    $mid->setTableFields(array(
	        "id"        => "mod_ID",
	        "parent_id" => "mod_Padre",
	        "text"      => "mod_Descripcion",
	        "href"      => "concat('../Se_Files/SeGeMo_mant.php' , '?' , 'mod_ID=', mod_ID)",
	        "title"     => "mod_Comentario",
	        "icon"      => "",
	    //  "icon"      => "''",    // this is an alternative to the line above
	        "target"    => "'myIframe1'",
	        "orderfield"    => "mod_padre",
	        "expanded"  => "0"));
	    $mid->scanTableForMenu("treemenu1");
	$mid->newTreeMenu("treemenu1");
	?>
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css"></link>	
<link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">	</link></head>
<link rel="stylesheet" href="./Themes/layersmenu-demo.css" type="text/css"></link>
<body class="externbox" style="background-color:#FFFFF7 ">

    <div class="normalbox" style="background-color:#FFFFF7 >
        <div class="normalbox" align="left" style="background-color:#FFFFF7" >
    	    <CENTER>-MODULOS: MANTENIMIENTO- <BR>
                <font class="mediumtxt" style="FONT-SIZE:8">
                    Presione sobre una categoría para Ver / Editar su contenido
                 </font>
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

