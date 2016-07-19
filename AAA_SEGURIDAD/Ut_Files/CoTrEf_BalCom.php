<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta>
 	<link rel="stylesheet" href="./Themes/layersmenu-demo.css" type="text/css"></link>
	<link rel="stylesheet" href="./Themes/layerstreemenu_courier.css" type="text/css"></link>
	<link rel="shortcut icon" href="LOGOS/shortcut_icon_phplm.png"></link>
	<title>BALANCE DE COMPROBACION</title>
	<?php include ("libjs/layersmenu-browser_detection.js"); ?>
	<script language="JavaScript" type="text/javascript" src="libjs/layerstreemenu-cookies.js"></script>
	<?php
	    session_start();
	 	include_once ("General.inc.php");      					// Definiciones generales
        include_once ("lib/PHPLIB.php");                         // Templates manager
        include_once ("lib/layersmenu-common.inc.php");          // Funciones Comunes de Arbol	
	    include_once ("GenUti.inc.php");
	    include_once ("lib/treemenu_3cols.inc.php");           //  segun Tipo de arbol deseado
//        	    include_once ("lib/phptreemenu.inc.php");
        set_time_limit(0) ;
	    $pPer = fGetParam("pPer", -1);
	    if (!($pPer>=1)) die ("<br><br><br><br><CENTER>NO SE PUDO ESTABLECER EL PERIODO</CENTER>") ;
	    $pEsq = fGetParam("pEsq", '');
	    if (!$pEsq) die ("<br><br><br><br><CENTER>NO SE PUDO ESTABLECER EL ESQUEMA DE CUENTAS</CENTER>");

	    $link = mysql_connect(DBSRVR, DBUSER, DBPASS)    or die ("NO PUEDE ACCEDER AL SERVIDOR DE DATOS");
	    mysql_select_db (DBNAME)                           or die ("LA BASE DE DATOS NO ESTA DISPONIBLE");
	    $sSQL = "DROP TABLE IF EXISTS tmp_saldo ";
	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA DE ELIMINACION DE DATOS TEMPORALES");
	
	    $sSQL = "CREATE  TEMPORARY TABLE IF NOT EXISTS tmp_saldo
	            (cuenta varchar(15), red_ascendent integer(10),
	            cue_padre integer(10), cue_id integer(10),
                det_codcuenta varchar(15), det_idauxiliar integer(10),
	            descr varchar(80), salan decimal(12,2), valdb decimal(12,2),
	            valcr decimal(12,2), saldo decimal(12,2), tipmovim integer) ";
	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA DE CREACION");
	    $sSQL = "DELETE FROM tmp_saldo ";
	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA ELIMINACION ");

//concat(cc.cue_descripcion, repeat('-',40)) as descr,
	    $sSQL = "INSERT INTO tmp_saldo
	            SELECT red_codcuenta as cuenta , red_Ascendent, cc.cue_padre, cc.cue_id,
	                ' ' AS det_codcuenta, 0 as det_idauxiliar,
                    rpad(cc.cue_descripcion, 32 - ((cc.cue_posicion - 2) * 4), '-') as descr,
                    sum(0.00) as salan,
	                sum(det_valdebito)  as valdb , sum(det_valcredito) as valcr,
	                sum(det_valdebito)  - sum(det_valcredito) as saldo, cc.cue_tipmovim
	            FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
	            WHERE com_numperiodo <= " . $pPer . " AND com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND
	                red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent  " ;
	    if (strlen($pEsq)>1) $sSQL .= "  AND red_codcuenta " . stripslashes($pEsq)   ;
	    $sSQL .= " GROUP BY 1,2,3,4, 5 " .
	             " ORDER BY 1,2 " ;
//echo "\n\n" . $sSQL;
        $result = mysql_query ($sSQL)   or die ("FALLO LA CONSULTA DE SALDOS DE CUENTAS");

        $sSQL = "CREATE TEMPORARY TABLE tmp_auxiliares
                        SELECT
                                ucase( rpad(concat( left(per_Apellidos, 20), ' ', left(per_Nombres, 15)), 28, '-') )as tmp_descripcion,
                                per_codauxiliar as tmp_codauxiliar
                        FROM conpersonas
                        UNION
                        SELECT  ucase( rpad(concat( left(act_descripcion, 20), ' ', left(act_descripcion1, 15)), 28, '-') )as tmp_descripcion,
                                act_codauxiliar as tmp_codauxiliar
                        FROM conactivos";
	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA DE AUXILIARES ");

    	$sSQL = "CREATE INDEX  tmp_aux ON tmp_auxiliares(tmp_codauxiliar)";
    	$result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA DE AUXILIARES 2 ");
//      Insertar el detalle de auxiliares.
	    $sSQL = "INSERT INTO tmp_saldo
	            SELECT det_idauxiliar as cuenta , 9 as red_Ascendent, cue_id as cue_padre,
                    (cue_id * 100000) + det_idauxiliar as cue_id,
	                det_codcuenta, det_idauxiliar,	
	                tmp_descripcion as descr,
                    sum(0) as salan,
	                sum(det_valdebito)  as valdb , sum(det_valcredito) as valcr,
	                sum(det_valdebito)  - sum(det_valcredito) as saldo, 1
                FROM ((concomprobantes JOIN condetalle on det_regnumero)
                     JOIN concuentas ON cue_codcuenta = det_codcuenta)
                     LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
	            WHERE com_numperiodo <= " . $pPer . " AND com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0";

	    if (strlen($pEsq)>1) $sSQL .= "  AND cue_codcuenta " . stripslashes($pEsq)   ;
	    $sSQL .= " GROUP BY 1,2,3,4,5,6,7" .
	             " ORDER BY 1,7 " ;
        $result = mysql_query ($sSQL)   or die ("FALLO LA CONSULTA DE SALDOS DE AUXILIARES");

        if (mysql_affected_rows() < 1 ) die ("<br><br><center>NO EXISTE INFORMACION PARA ESTE PERIODO</center>");
	    $sSQL = "SELECT MIN(cue_padre) as minpadre FROM tmp_saldo ";
	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA EVALUACION ");
	    $line = mysql_fetch_array($result);
	    $sSQL = "UPDATE tmp_saldo SET cue_padre = 1 WHERE cue_padre = " . $line["minpadre"];
	    $result = mysql_query ($sSQL)                   or die ("FALLO EL AJUSTE DE ESTRUCTURA");
	    $slUrl = "if(tipmovim > 0, concat('../Co_Files/CoTrTr_movim.php?action=L&s_com_NumPeriodo=" . $pPer . "&s_det_CodCuenta=',det_codcuenta,'&s_det_IDAuxiliar=',det_idauxiliar ),'')";
	    $mid = new TreeMenu();
	    $mid->setDBConnParms(DATOS_CON);
	    $mid->setTableName("tmp_saldo");
	    $mid->setTableFields(array(
	        "id"        => "cue_id",
	        "parent_id" => "cue_Padre",

	        "text"      => "concat('&nbsp;',cuenta, '. ', replace(descr,'-','&nbsp;'), ".
	                       "repeat('&nbsp;',15-length(format(valdb,2))), format(valdb,2),' ', ".
	                       "repeat('&nbsp;',25-length(format(valcr,2))), format(Valcr,2),     " .
	                       "repeat('&nbsp;',25-length(format(saldo,2))), format(saldo,2),' ') " ,
	        "href"      => $slUrl,
	        "title"     => "",
	        "icon"      => "",
	        "target"    => "'myIframe1'",
	        "orderfield"    => "cue_padre",
	        "expanded"  => "0"));
	    $mid->scanTableForMenu("treemenu1");
	    $mid->newTreeMenu("treemenu1");

	?>

</head>
<body>
	<div class="normalbox">
    	<div class="normalbox" align="left">
        	<CENTER>SALDOS DE CUENTAS <BR>
	                Presione sobre una Cuenta para Ver / Editar su contenido
	        </CENTER>
	    </div>
	</div>
	<div class="normalbox" align="left">
	     <table margin="1">
	         <tr><td width="100px" align="center"> CUENTA </td>
	             <td width="300px" align="center"> DESCRIPCION </td>
	             <td width="150px" align="left"> DEBITOS </td>
	             <td width="150px" align="center"> CREDITOS </td>
	             <td width="100px" align="right"> S A L D O </td>
	             <td width="100px" align="center">    </td>
	         </tr>
	     </table>
	</div>
	<div class="normalbox" align="left">
        <?php
         	print "<PRE>";
            $mid->printTreeMenu("treemenu1");
#	         $mid->printPHPTreeMenu("treemenu1");
	         print "</PRE>";
     	?>
	</div>
</body>
</html>
