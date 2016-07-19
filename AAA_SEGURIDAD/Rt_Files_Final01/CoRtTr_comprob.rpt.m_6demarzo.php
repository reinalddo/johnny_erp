<?php
/*
 *	Generacion de Comprobante de Retencion
 *	@author	 Marco Valle Sanchez
 *	@package  AAA
 *	@subpackage	Contabilidad
 *	@created	05/03/2009
 *
 *
 **/
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/GenCifras.php");
include("adodb.inc.php");
$db = NewADOConnection("mysql");        //      Define a global connection.

function &fDefineQry(&$db, $pQry=false)
{
    $pQry="ID = 1";
    echo "viene: ".$pQry;
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
    $alSql[] = "SELECT 	ID as ID, idProvFact COD, concat(pro.per_Apellidos, ' ', pro.per_Nombres) NOM,
						date_format(fechaRegistro,'%M %d %y') FEC, year(fechaRegistro) PER,
						ifNULL(pro.per_Ruc,'********') as RUC,
						concat(ifNULL(pro.per_Direccion,' '), '  / Telf:', ifNULL(pro.per_Telefono1,' ')) as DIR,
						concat(establecimiento, ' ', puntoEmision, ' ', secuencial) as  FAC,
						UPPER(tco.tab_Descripcion) as TIP,
						baseImpGrav as BIV, civ.tab_porcentaje as PIV, montoIva as MIV,
						montoIvaBienes as BIB, porRetBienes TIB, prb.tab_porcentaje as PIB, prb.tab_Descripcion AS DIB, valorRetBienes as MIB,
						montoIvaServicios as BIS, porRetServicios TIS, prs.tab_porcentaje as PIS, prs.tab_Descripcion AS DIS, valorRetServicios as MIS,
						BaseImpAir as BIR, cra.tab_txtData AS TIR, cra.tab_porcentaje as PIR, cra.tab_Descripcion AS DIR_, valRetAir as MIR,
						BaseImpAir2 as BIR2, cr2.tab_txtData AS TIR2, cr2.tab_porcentaje as PIR2, cr2.tab_Descripcion AS DIR2, valRetAir2 as MIR2,
						BaseImpAir3 as BIR3, cr3.tab_txtData AS TIR3, cr3.tab_porcentaje as PIR3, cr3.tab_Descripcion AS DIR3, valRetAir3 as MIR3,
						valRetAir + valRetAir2+ valRetAir3+ valorRetBienes + valorRetServicios as TOT
				FROM fiscompras fco
						LEFT JOIN fistablassri sus ON sus.tab_CodTabla = '3'  AND fco.codSustento +0  = sus.tab_Codigo +0
						LEFT JOIN fistablassri tco ON tco.tab_CodTabla = '2'  AND fco.tipoComprobante +0 = tco.tab_Codigo
						LEFT JOIN fistablassri civ ON civ.tab_CodTabla = '4'  AND fco.porcentajeIva = civ.tab_Codigo
						LEFT JOIN fistablassri pic ON pic.tab_CodTabla = '6'  AND fco.porcentajeIce = pic.tab_Codigo
						LEFT JOIN fistablassri prb ON prb.tab_CodTabla = '5a' AND fco.porRetBienes = prb.tab_Codigo
						LEFT JOIN fistablassri prs ON prs.tab_CodTabla = '5'  AND fco.porRetServicios = prs.tab_Codigo
						LEFT JOIN fistablassri cra ON cra.tab_CodTabla = '10' AND fco.codRetAir = cra.tab_Codigo
						LEFT JOIN fistablassri cr2 ON cr2.tab_CodTabla = '10' AND fco.codRetAir2 = cr2.tab_Codigo
						LEFT JOIN fistablassri cr3 ON cr3.tab_CodTabla = '10' AND fco.codRetAir3 = cr3.tab_Codigo
						LEFT JOIN fistablassri ccm ON civ.tab_CodTabla = '2'  AND fco.docModificado = ccm.tab_Codigo
						LEFT JOIN fistablassri tra ON tra.tab_CodTabla = 'A'  AND fco.tipoTransac = tra.tab_Codigo
						LEFT JOIN conpersonas  pro ON pro.per_CodAuxiliar = fco.codProv
						LEFT JOIN genparametros par ON par.par_clave= 'TIPID' AND par.par_secuencia = pro.per_tipoID
						LEFT JOIN conpersonas  pv2 ON pv2.per_CodAuxiliar = fco.idProvFact
						LEFT JOIN genparametros pm2 ON pm2.par_clave= 'TIPID' AND pm2.par_secuencia = pv2.per_tipoID
                 WHERE " . $pQry . " AND valRetAir + valorRetBienes + valorRetServicios  > 0";
		 
		 
    $rs= fSQL($db, $alSql);
    //echo $alSql;
    return $rs;
}
$slQry   = " ID = " . fGetParam('ID', false);
$rs = fDefineQry($db, $slQry );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<script>
function imprimirPagina() {
  if (window.print)
  {
	document.all.imprimir.style.visibility='hidden'  
    window.print();
	}
  else
  {
    alert("Lo siento, pero a tu navegador no se le puede ordenar imprimir" +
      " desde la web. Actualizate o hazlo desde los menús");
  }
}
</script>

</head>

<body>
<form name="all">
<?php
	$variable1="Servicios Mariscal";
	$variable2="8 de Enero de 2009";
	$variable3="0991343997001";
	$variable4="Factura";
	$variable5="Jose Mascote 4925 entre Sedalana";
	$variable6="001-001-0010521";
	$variable7="2009";
	$variable8="153.00";
	$variable9="Renta";
	$variable10="329";
	$variable11="2.00%";
	$variable12="3.06";
	$variable13="3.06";
							
?>
	<DIV STYLE="POSITION:absolute; LEFT:90px; TOP:110px;;"><?php echo $variable1?></DIV>
	<DIV STYLE="POSITION:absolute; LEFT:780px; TOP:110px;"><?php echo $variable2?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:135px; TOP:135px;"><?php echo $variable3?></DIV>
	<DIV STYLE="POSITION:absolute; LEFT:925px; TOP:135px;"><?php echo $variable4?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:135px; TOP:162px;"><?php echo $variable5?></DIV>
	<DIV STYLE="POSITION:absolute; LEFT:925px; TOP:162px; "><?php echo $variable6?></DIV>
	<DIV STYLE="POSITION:absolute; LEFT:70px; TOP:250px; "><?php echo $variable7?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:265px; TOP:250px;"><?php echo $variable8?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:496px; TOP:250px; "><?php echo $variable9?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:650px; TOP:250px; "><?php echo $variable10?></DIV>
   	<DIV STYLE="POSITION:absolute; LEFT:880px; TOP:250px; "><?php echo $variable11?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:1105px; TOP:250px; "><?php echo $variable12?></DIV>
  	<DIV STYLE="POSITION:absolute; LEFT:1105px; TOP:360px; "><?php echo $variable13?></DIV>

<table width="959" border="0" cellpadding="0" cellspacing="0"> 
  <tr>
    <td width="433" height="302">&nbsp;</td>
    <td width="65">&nbsp;</td>
    <td width="461">&nbsp;</td>
  </tr>
  <tr>
    <td height="24">&nbsp;</td>
    <td valign="top"><input name="imprimir" id="noprint" type="button" onClick="imprimirPagina();" value="Imprimir"></td>
  <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="57">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
