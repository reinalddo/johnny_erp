<?php
/*
 *	Generacion de Comprobante de Retencion
 *	@author	 Marco Valle Sánchez
 *	@package  AAA
 *	@subpackage	Contabilidad
 *	@created	05/03/2009
 *
 *
 **/
include("../LibPhp/ezPdfReport.php");
//include("../LibPhp/GenCifras.php");
include("adodb.inc.php");
$db = NewADOConnection("mysql");        //      Define a global connection.
function &fDefineQry(&$db, $pQry=false)
{
    $db->Execute("SET lc_time_names = es_EC ");
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
    $alSql[] = "SELECT 	ID as ID,
			idProvFact COD,
			concat(pro.per_Apellidos, ' ', pro.per_Nombres) NOM,
			UPPER(date_format(fechaRegistro,'%M %d %y')) FEC,
			
			year(fechaRegistro) PER,
			ifNULL(pro.per_Ruc,'********') as RUC,
			pro.per_Direccion as Direcc,
			concat(ifNULL(pro.per_Direccion,' '), '  / Telf:', ifNULL(pro.per_Telefono1,' ')) as DIR,
			concat(establecimiento, ' ', puntoEmision, ' ', secuencial) as  FAC,
			LEFT(UPPER(tco.tab_Descripcion),24) as TIP,
			baseImpGrav as BIV,
			civ.tab_porcentaje as PIV,
			montoIva as MIV,
			montoIvaBienes as BIB,
			porRetBienes TIB,
			prb.tab_porcentaje as PIB,
			prb.tab_Descripcion AS DIB,
			valorRetBienes as MIB,
			montoIvaServicios as BIS,
			porRetServicios TIS,
			prs.tab_porcentaje as PIS,
			prs.tab_Descripcion AS DIS,
			valorRetServicios as MIS,
			BaseImpAir as BIR,
			cra.tab_txtData AS TIR,
			cra.tab_porcentaje as PIR,
			cra.tab_Descripcion AS DIR_, valRetAir as MIR,
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
    return $rs;
   /*$rs = mysql_query($alSql, $db);*/
   return $rs;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<style>
/*@page {size: 210mm 297mm; margin: 20mm }*/
</style>
<script>
function imprimirPagina()
{
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
function Minimize()
{
window.innerWidth = 100;
window.innerHeight = 100;
window.screenX = screen.width;
window.screenY = screen.height;
alwaysLowered = true;
}
</script>

</head>

<body onload="window.print();">
<form name="all">
<?php
	//tiene q ver con la conexion
	///////////////////////***********//////////////////////////////////nuevo marco 17/03/09
	define("RelativePath", "..");
	include(RelativePath . "/Common.php");
	include(RelativePath . "/Template.php");
	include(RelativePath . "/Sorter.php");
	include(RelativePath . "/Navigator.php");
	include_once(RelativePath . "/Rt_Files/../De_Files/Cabecera.php");
	// Controls
	$Cabecera = new clsCabecera("../De_Files/");
	$Cabecera->BindEvents();
	$Cabecera->Initialize();
	$empresa=$Cabecera->lbEmpresa;
	//echo $empresa->Text;
	$tipoempresa=substr($empresa->Text, 0, 7);
	//echo $tipoempresa;
	////////////////////////////*************************************////////////////////////////
$ilPag = 1;
$ilTipProceso= fGetParam('pTipo', 20);//Tipo de proceso, si no se viene en GET, el tipo es 20
$slQry   = " ID = " . fGetParam('ID', false);
$slSem   = trim(fGetParam('pro_Semana', false));
if (!$slQry) fErrorPage('','DEBE DEFINIR UN CRITERIO DE SELECCION (Semana y/o Proceso)', true,  false);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry ); //aqui traigo los datos
$rs->MoveFirst();
$igVertPos = 360;
$igPaso = 35;
if(($tipoempresa=="Amenegs")|| ($tipoempresa=="Costafr")|| ($tipoempresa=="LIGHT F"))
{
    $igcabvert_l1 = 147;
    $igcabvert_l2 = 190;
    $igcabvert_l3 = 225;
    $igcabvert_liz = 1350;
    
}
elseif(($tipoempresa=="Forzafr")||($tipoempresa=="MUNDIPA"))
	{
	    $igcabvert_l1 = 162;
	    $igcabvert_l2 = 205;
	    $igcabvert_l3 = 240;
	    $igcabvert_liz = 1350;
	}
	elseif($tipoempresa=="Forzain")
	{
	    $igcabvert_l1 = 172;
	    $igcabvert_l2 = 215;
	    $igcabvert_l3 = 250;
	    $igcabvert_liz = 1750;
	}
	

while ($r = $rs->fetchRow())
    {

echo "<DIV STYLE='POSITION:absolute; LEFT:150px; TOP:".$igcabvert_l1."px;'>".$r['NOM']."</DIV>";
echo "<DIV STYLE='POSITION:absolute; LEFT:".$igcabvert_liz."px; TOP:".$igcabvert_l1."px;'>".$r['FEC']."</DIV>";
echo "<DIV STYLE='POSITION:absolute; LEFT:195px; TOP:".$igcabvert_l2."px;'>".$r['RUC']."</DIV>";
echo "<DIV STYLE='WIDTH:400px; font-size: 14px; POSITION:absolute; LEFT:".$igcabvert_liz."px; TOP:".$igcabvert_l2."px;'>".$r['TIP']."</DIV>";
echo "<DIV STYLE='POSITION:absolute; LEFT:195px; TOP:".$igcabvert_l3."px;'>".$r['Direcc']."</DIV>";
echo "<DIV STYLE='WIDTH:200px; POSITION:absolute; LEFT:".$igcabvert_liz."px; TOP:".$igcabvert_l3."px;'>".$r['FAC']."</DIV>";


	if($r['MIB']>0)
	{
	echo "<DIV STYLE='POSITION:absolute; LEFT:70px;  TOP:".$igVertPos."px;'>".$r['PER']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:465px; TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['BIB']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:725px; TOP:".$igVertPos."px;'>IVA</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:959px; TOP:".$igVertPos."px;'>".$r['TIB']."</DIV>";
   	echo "<DIV STYLE='POSITION:absolute; LEFT:1250px; TOP:".$igVertPos."px; text-align:right; width:60px;'>".$r['PIB']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:1500px;TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['MIB']."</DIV>";
	$igVertPos +=  $igPaso;
	}
	if($r['MIS']>0)
	{
	echo "<DIV STYLE='POSITION:absolute; LEFT:70px;  TOP:".$igVertPos."px;'>".$r['PER']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:465px; TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['BIS']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:725px; TOP:".$igVertPos."px;'>IVA</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:959px; TOP:".$igVertPos."px;'>".$r['TIS']."</DIV>";
   	echo "<DIV STYLE='POSITION:absolute; LEFT:1250px; TOP:".$igVertPos."px; text-align:right; width:60px;'>".$r['PIS']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:1500px;TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['MIS']."</DIV>";
	$igVertPos +=  $igPaso;
	}

	if($r['MIR']>0)
	{
	echo "<DIV STYLE='POSITION:absolute; LEFT:70px;  TOP:".$igVertPos."px;'>".$r['PER']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:465px; TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['BIR']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:725px; TOP:".$igVertPos."px;'>RENTA</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:959px; TOP:".$igVertPos."px;'>".$r['TIR']."</DIV>";
   	echo "<DIV STYLE='POSITION:absolute; LEFT:1250px; TOP:".$igVertPos."px; text-align:right; width:60px;'>".$r['PIR']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:1500px;TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['MIR']."</DIV>";
	$igVertPos +=  $igPaso;
	}
	
	if($r['MIR2']>0)
	{
	echo "<DIV STYLE='POSITION:absolute; LEFT:70px;  TOP:".$igVertPos."px;'>".$r['PER']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:465px; TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['BIR2']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:725px; TOP:".$igVertPos."px;'>RENTA</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:959px; TOP:".$igVertPos."px;'>".$r['TIR2']."</DIV>";
   	echo "<DIV STYLE='POSITION:absolute; LEFT:1250px; TOP:".$igVertPos."px; text-align:right; width:60px;'>".$r['PIR2']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:1500px;TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['MIR2']."</DIV>";
	$igVertPos +=  $igPaso;
	}
	
	if($r['MIR3']>0)
	{
	echo "<DIV STYLE='POSITION:absolute; LEFT:70px;  TOP:".$igVertPos."px;'>".$r['PER']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:465px; TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['BIR3']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:725px; TOP:".$igVertPos."px;'>RENTA</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:959px; TOP:".$igVertPos."px;'>".$r['TIR3']."</DIV>";
   	echo "<DIV STYLE='POSITION:absolute; LEFT:1250px; TOP:".$igVertPos."px; text-align:right; width:60px;'>".$r['PIR3']."</DIV>";
  	echo "<DIV STYLE='POSITION:absolute; LEFT:1500px;TOP:".$igVertPos."px; text-align:right; width:50px;'>".$r['MIR3']."</DIV>";
	$igVertPos +=  $igPaso;
	}
	
	
  	$total=$r['MIS'] + $r['MIB'] + $r['MIR']+ $r['MIR2'] + $r['MIR3'];
	echo "<DIV STYLE='POSITION:absolute; LEFT:1500px; TOP:520px;'>".$total."</DIV>";
}
?>

<table width="959" border="0" cellpadding="0" cellspacing="0"> 
  <tr>
    <td width="433" height="302">&nbsp;</td>
    <td width="65">&nbsp;</td>
    <td width="461">&nbsp;</td>
  </tr>
  <tr>
    <td height="24">&nbsp;</td>
   <!-- <td valign="top"><input name="imprimir"  id="noprint" type="button" onClick="imprimirPagina();" value="Imprimir"></td>-->
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
