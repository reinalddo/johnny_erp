<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta>
 	<link rel="stylesheet" href="./Themes/layersmenu-demo.css" type="text/css"></link>
	<link rel="stylesheet" href="./Themes/layerstreemenu_courier.css" type="text/css"></link>
	<link rel="shortcut icon" href="LOGOS/shortcut_icon_phplm.png"></link>
	<title>BALANCE GENERAL</title>
 	<?php include ("libjs/layersmenu-browser_detection.js"); ?>
 	<script language="JavaScript" type="text/javascript" src="libjs/layerstreemenu-cookies.js"></script>
	<?php
        session_start();	
	    include_once ("General.inc.php");
        include_once ("GenUti.inc.php");
        include ("lib/aaa_TreeGen.inc.php");
        include ("lib/treemenu_3cols.inc.php");           //  segun Tipo de arbol deseado
        set_time_limit(0) ;
	    $pPer = fGetParam("pPer", -1);
	    if (!($pPer>=1)) die ("<br><br><br><br><CENTER>NO SE PUDO ESTABLECER EL PERIODO</CENTER>") ;
	    $pEsq = fGetParam("pEsq", '');
	    if (!$pEsq) die ("<br><br><br><br><CENTER>NO SE PUDO ESTABLECER EL ESQUEMA DE CUENTAS</CENTER>");
	    $link = mysql_connect(DBSRVR, DBUSER, DBPASS)    or die ("NO PUEDE ACCEDER AL SERVIDOR DE DATOS");
	    mysql_select_db (DBNAME)                           or die ("LA BASE DE DATOS NO ESTA DISPONIBLE");
	    $sSQL = "CREATE TABLE IF NOT EXISTS tmp_saldo ".
	            "(cuenta varchar(15), red_ascendent integer(10),  ".
	            "cue_padre integer(10), cue_id integer(10),  ".
	            "descr varchar(80), salan decimal(12,2), valdb decimal(12,2), ".
	            "valcr decimal(12,2), saldo decimal(12,2), tipmovim integer) ";
	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA DE CREACION");
	    $sSQL = "DELETE FROM tmp_saldo ";
	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA ELIMINACION ");
	    $sSQL = "INSERT INTO tmp_saldo ".
	            "SELECT red_codcuenta as cuenta , red_Ascendent, cc.cue_padre, cc.cue_id, ".
	                "concat(cc.cue_descripcion, repeat('-',40)) as descr, ".
	                "sum(det_valdebito)  as valdb , 0, sum(det_valcredito) as valcr, ".
	                "sum(det_valdebito)  - sum(det_valcredito) as saldo, cc.cue_tipmovim ".
	            "FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc ".
	            "WHERE com_numperiodo <= " . $pPer . " AND com_estProceso = 5 AND " .
	                "det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND ".
	                "red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent  " ;
	    //echo $sSQL;
	    if (strlen($pEsq)>1) $sSQL .= "  AND red_codcuenta " . stripslashes($pEsq)   ;
	    $sSQL .= " GROUP BY 1,2 " .
	             " ORDER BY 1,2 " ;
	    $result = mysql_query ($sSQL)   or die ("FALLO LA CONSULTA Generadora de datos");
	    if (mysql_affected_rows() < 1 ) die ("<br><br><center>NO EXISTE INFORMACION PARA ESTE PERIODO</center>");
	    $sSQL = "SELECT MIN(cue_padre) as minpadre FROM tmp_saldo ";

	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA EVALUACION ");
	    $line = mysql_fetch_array($result);
	    $sSQL = "UPDATE tmp_saldo SET cue_padre = 1 WHERE cue_padre = " . $line["minpadre"];

	    $result = mysql_query ($sSQL)                   or die ("FALLO LA CONSULTA 2");
	    $slUrl = "if(tipmovim > 0, concat('../Co_Files/CoTrTr_movim.php?action=L&s_com_NumPeriodo=" . $pPer .
	                               "&s_det_CodCuenta=', cuenta),'')";
	    $mid = new TreeMenu();
	    $mid->setDBConnParms(DATOS_CON);
	    $mid->setTableName("tmp_saldo");
	    $mid->setTableFields(array(
	        "id"        => "cue_id",
	        "parent_id" => "cue_Padre",
	        "text"      => "concat('&nbsp;',cuenta, '.&nbsp;', replace(left(descr,30),'-','&nbsp;'), " .
	                       "repeat('&nbsp;',15-length(format(saldo,2))), format(saldo,2),'&nbsp;')",
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
    	<table margin="0">
	         <tr><td width="10%" align="center"> CUENTA </td>
	             <td width="20%" align="center"> DESCRIPCION </td>
	             <td width="70%" align="center"> S A L D O </td>
	         </tr>
	    </table>
	</div>
	<div class="normalbox" align="left">
	    <?php
    	    print "<PRE>";
	        $mid->printTreeMenu("treemenu1");
	        print "</PRE>";
	        unset($mid);
		?>
	</div>
</body>
</html>
