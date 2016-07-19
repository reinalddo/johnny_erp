<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
 	<?php
/**
*   SeGeAt_tree: Arbol General de atributos
*   Presenta un arbol con los perfiles definidos, los modulos a los que tiene acceso cada perfil
*   y los accesos en cada modulo.
*   Se presenta el arbol incluyendo todos los modulos existentes, aunque no se haya definido permisos
*   en algunos de ellos (producto cartesiano perfiles-modulos). El modulo de mantenimiento, se encarga
*   de Insertar (los nuevos atributos)o Modificar (los existentes) la informacion.
**/

    	include ("lib/aaa_GenHtml.inc");
    	include ("libjs/layersmenu-browser_detection.js");
    ?>
	<title>ATRIBUTOS</title>
	<script language="JavaScript" type="text/javascript" src="libjs/layerstreemenu-cookies.js"></script>
	<?php
	session_start();
	include_once ("General.inc.php");      					// Definiciones generales
    include_once ("lib/PHPLIB.php");                         // Templates manager
    include_once ("lib/layersmenu-common.inc.php");          // Funciones Comunes de Arbol	
	include ("lib/treemenu.inc.php");
	$mid = new TreeMenu();
	$mid->setDBConnParms(SEGUR_CON);
	//$mid->setTableName("tmp_dat", $slQuery);
	/****************************************************************
	* NOTA: LOS MODULOS DEBE INICIAR CON mod_ID = 2 y mod_padre = 1
	****************************************************************/
	$db = DB::connect($mid->dsn, true);
	if (DB::isError($db)) {
	    $mid->error("QUERY PRELIMINAR" . $db->getMessage());
	}
	$dbresult = $db->query("DELETE FROM seg_tmpdat");
	$dbresult = $db->query("DROP TABLE IF EXISTS tmp_mod " );
	$dbresult = $db->query("CREATE TEMPORARY TABLE tmp_mod
                                   SELECT mod_ID as tmp_ID, pfl_IDperfil as tmp_IDperfil, pfl_ID as tmp_pfl
                                        FROM segmodulos, segperfiles "); // Producto cartesiano PERFILES - MODULOS
	$slQuery = "INSERT INTO seg_tmpdat " .
               "SELECT  mod_ID + (pfl_ID * 1000) as tmp_ID, ".
	                    "mod_padre + (pfl_ID * 1000) as tmp_parentID, mod_descripcion as tmp_descripcion, ".
	                    "mod_comentario as tmp_coment, ".
	                    "concat('../Se_Files/SeGeAt_mant.php?atr_IDperfil=' ,tmp_idperfil,'&atr_Codmodulo=', mod_ID, '                           ') as tmp_href " .
	           "FROM  tmp_mod LEFT JOIN segatributos ON atr_IDPERFIL = tmp_IDPerfil AND atr_codmodulo = tmp_id ".
	 	             "LEFT JOIN segmodulos on mod_ID = tmp_ID ".
	 	             "LEFT JOIN segperfiles on pfl_ID = tmp_pfl ";
	$dbresult = $db->query($slQuery);
	$slQuery  = "INSERT INTO seg_tmpdat ";
	$slQuery .= "SELECT     (1000 * pfl_ID) + 1 , 1 , pfl_descripcion ,";
	$slQuery .=         "concat(' Perfil' , ' ' , pfl_idperfil), ";
	$slQuery .=         "concat('../Se_Files/SeGePe_mant.php?pfl_IDperfil=',pfl_idperfil )";
	$slQuery .= "FROM   segperfiles " ;
	$dbresult = $db->query($slQuery);
    $mid->setTableName("seg_tmpdat" );
	$mid->setTableFields(array(
	    "id"        => "tmp_ID",
	    "parent_id" => "tmp_parentID",
	    "text"      => "tmp_descripcion",
	    "href"      => "tmp_href",
	    "title"     => "tmp_coment",
	    "icon"      => "",
	    "target"    => "'myIframe1'",
	    "orderfield"    => "tmp_parentID",
	    "expanded"  => "0"));
	unset($db);
	$mid->scanTableForMenu("treemenu1");
	$mid->newTreeMenu("treemenu1");
?>

</head>
<body>
	<div class="normalbox">
    	<div class="normalbox" align="left">
	        <font class="mediumtxt">
	            <CENTER>ATRIBUTOS:<BR>
	                    Presione sobre un Elemento para Ver / Editar su contenido
	            </CENTER>
	        </font>
	    </div>
	        <?php
	            $mid->printTreeMenu("treemenu1");
	        ?>
    </div>
</body>
</html>
