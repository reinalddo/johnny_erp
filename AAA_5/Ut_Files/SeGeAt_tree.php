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
	<link rel="stylesheet" type="text/css" href="../css/suggestions.css">
	<script language="JavaScript" type="text/javascript" src="../LibJava/general.js"></script>
	<script language="JavaScript" type="text/javascript" src="../LibJava/general.js"></script>
	<script language="JavaScript" type="text/javascript" src="../LibJava/pajax-core.js"></script>
	<script language="JavaScript" type="text/javascript" src="../LibJava/pajax-commom.js"></script>
	<script language="JavaScript" type="text/javascript" src="../LibJava/pajax-parser.js"></script>
	<script language="JavaScript" type="text/javascript" src="../LibJava/autosuggestcontroller.js"></script>
	<script language="JavaScript" type="text/javascript" src="../LibJava/dataprovider.js"></script>
	<script language="JavaScript" type="text/javascript" src="libjs/layerstreemenu-cookies.js"></script>
	<script language="JavaScript" type="text/javascript" >
	function fPerfiles(pObj){
	    var slSql = "SELECT pfl_Descripcion, pfl_IDperfil FROM seguridad.segperfiles WHERE pfl_descripcion LIKE "
        fSelectBox(pObj, slSql, "", "txt_Perfil", pObj.parentNode, "180", "auto, static")
    }
    function fRecargar(pObj){
        var slUrl ='SeGeAt_tree.php?pDB=seguridad&txt_Perfil=' + document.getElementById('txt_Perfil').value  +
                   '&txt_Descr=' + document.getElementById('txt_Descr').value;
        window.location.replace(slUrl);
    }
    </script>
	<?php
error_reporting (E_ALL & ~NOTICE);
set_time_limit(0);
	include_once ("General.inc.php");      					// Definiciones generales
	include_once ("GenUti.inc.php");      					// Definiciones generales
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
	$slPerfil = fGetParam("txt_Perfil", false);

	if (strlen($slPerfil) > 1) {
        $dbresult = $db->query("DROP TABLE IF EXISTS tmp_mod " );
    	$dbresult = $db->query("CREATE  TEMPORARY TABLE tmp_mod
                                       SELECT mod_ID as tmp_ID, pfl_IDperfil as tmp_IDperfil, pfl_ID as tmp_pfl
                                            FROM segmodulos, segperfiles
                                            WHERE mod_ID >1 and pfl_estado > 0 " .
                                                  " AND pfl_IDperfil = '" . $slPerfil . "' "
                                );// Producto cartesiano PERFILES - MODULOS
echo "<br>";
    	If (!$db->query("drop table if exists `seg_tmpdat`"));
        $dbresult = $db->query("CREATE  TABLE `seg_tmpdat` (
    					  `tmp_ID` bigint(17) NOT NULL default '0',
    					  `tmp_parentID` bigint(17) NOT NULL default '0',
    					  `tmp_descripcion` varchar(50) default NULL,
    					  `tmp_coment` varchar(200) default NULL,
    					  `tmp_href` varchar(67) character set latin1 collate latin1_bin NOT NULL default ''
    					) ENGINE=MyISAM DEFAULT CHARSET=latin1;
    				");

    	$slQuery = "INSERT INTO seg_tmpdat " .
                   "SELECT  mod_ID + (pfl_ID * 100000) as tmp_ID, ".
    	                    "mod_padre + (pfl_ID * 100000) as tmp_parentID, mod_descripcion as tmp_descripcion, ".
    	                    "mod_comentario as tmp_coment, ".
    	                    "concat('../Se_Files/SeGeAt_mant.php?atr_IDperfil=' ,tmp_idperfil,'&atr_Codmodulo=', mod_ID, '                           ') as tmp_href " .
    	           "FROM  tmp_mod LEFT JOIN segatributos ON atr_IDPERFIL = tmp_IDPerfil AND atr_codmodulo = tmp_id ".
    	 	             "LEFT JOIN segmodulos on mod_ID = tmp_ID  and mod_ID >0  " .
    	 	             "LEFT JOIN segperfiles on pfl_ID = tmp_pfl and pfl_estado > 0";
    	$dbresult = $db->query($slQuery);

    	$slQuery  = "INSERT INTO seg_tmpdat ";
    	$slQuery .= "SELECT     (100000 * pfl_ID) + 1 , 1 , concat(pfl_descripcion, if(pfl_estado>=1,'',' (inactivo)')) ,";
    	$slQuery .=         "concat(' Perfil' , ' ' , pfl_idperfil), ";
    	$slQuery .=         "concat('../Se_Files/SeGePe_mant.php?pfl_IDperfil=',pfl_idperfil )";
    	$slQuery .= "FROM   segperfiles WHERE  pfl_IDperfil = '" . $slPerfil . "' " ;
    	$dbresult = $db->query($slQuery);
//print_r($dbresult); 	
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

    	$mid->scanTableForMenu("treemenu1");
    	$mid->newTreeMenu("treemenu1");
    	unset($db);
	}
?>

</head>
<body>
	<div class="normalbox" title="Seleccione un perfil, para ver detalladamente su jerarquía de accesos">
    	<div class="normalbox" align="left">
	        <font class="mediumtxt">
	            <CENTER>PERFILES:
	            <INPUT  id="txt_Descr" size=40 style="font-size:9; width:250" value = "<? echo fGetParam('txt_Descr', '') ?>"  onfocus="fPerfiles(this)"
                        onchange="fRecargar(this)" >
                <INPUT  id="txt_Perfil" size=8 style="font-size:8; width:25" value = "<? echo fGetParam('txt_Perfil', '') ?>"  readonly
                        >
	            <INPUT type="button" value="DETALLES" title="Ver detalle de permisoso " style="font-size:8; width:40"  onclick="fRecargar(this)">
                <INPUT type="button" value="NUEVO" style="font-size:8; width:40"  onclick="window.parent.frames[1].location.replace('../Se_Files/SeGePe_mant.php' )">
                </CENTER>
	        </font>
	    </div>
    	<div class="normalbox" align="left" title="Presione sobre un ELEMENTO para Ver / Editar su contenido" >
	        <font class="mediumtxt">
	            <CENTER>DETALLE DE ATRIBUTOS:<BR>
	                    
	            </CENTER>
	            <HR/>
	        </font>
	        <?php
	        if (strlen($slPerfil) > 1) {
	            $mid->printTreeMenu("treemenu1");
	        }
	        ?>

	    </div>
	            </div>
</body>
</html>
