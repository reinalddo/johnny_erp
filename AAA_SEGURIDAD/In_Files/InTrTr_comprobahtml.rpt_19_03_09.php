<?php
/*
 *	Generacion de Comprobante de Inventario
 *	@author	 Juan Camilo Velez
 *	@package  AAA
 *	@subpackage	Inventario
 *	@created	28/01/2009
 *	@rev		fah 28/01/09	Soporte de descripciones dinamicas en: Nombre de Comprobante, Tipo de Emisor/Receptor, Codigos de Emisor/Receptor
 *
 *
 **/
session_start();
include('General.inc.php');
include('GenUti.inc.php');
require_once('../LibPhp/conn1.php');
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_conn1, $conn1);
//	@fah 18/01/09	Añadir campos TRADES, TXTEMI, TXTREC, 
$query_Recordset1 = "SELECT cla_descripcion AS TRADES,
			    cla_txtEmisor   AS TXTEMI,
			    cla_txtReceptor   AS TXTREC,
			    com_tipocomp AS TIPO,
			    com_numcomp AS COMPR,
			    det_secuencia  AS SECUE,
			    com_concepto AS CONCEP,
			    com_feccontab AS FECHA,
			    com_refoperat AS REFOP,
			    com_emisor AS CODEM,
			    concat(b.per_Apellidos, ' ', b.per_nombres) as BODEG,
			    com_codreceptor AS CODRE,
			    concat(p.per_Apellidos, ' ', p.per_nombres) as RECEP,
			    det_coditem AS CODIT,
			    left(concat(act_descripcion, ' ', act_descripcion1),25) as ITEM,
			    det_candespachada AS CANTI,
			    det_cantequivale AS CANTE,
			    uni_abreviatura AS UNIDA,
			    det_costotal AS COSTO,
			    det_valtotal AS VALOR
			    FROM genclasetran JOIN concomprobantes ON cla_aplicacion = 'IN' AND com_tipoComp = cla_tipoComp
				  LEFT JOIN conpersonas b ON b.per_codauxiliar = com_emisor
				  LEFT JOIN conpersonas p ON p.per_codauxiliar = com_codreceptor
				  LEFT JOIN invdetalle ON det_regnumero = com_regnumero
				  LEFT JOIN conactivos ON act_codauxiliar = det_coditem
				  LEFT JOIN genunmedida ON uni_CodUnidad = act_unimedida ";
/*$Recordset1 = mysql_query($query_Recordset1, $conn1) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_conn1, $conn1);
$query_Recordset1 = "SELECT com_tipocomp AS TIPO,                 com_numcomp AS COMPR,                 det_secuencia  AS SECUE,  com_concepto AS CONCEP,               com_feccontab AS FECHA,                 com_refoperat AS REFOP,                 com_emisor AS CODEM,                 concat(b.per_Apellidos, ' ', b.per_nombres) as BODEG,                 com_codreceptor AS CODRE,                 concat(p.per_Apellidos, ' ', p.per_nombres) as RECEP,                 det_coditem AS CODIT,                 left(concat(act_descripcion, ' ', act_descripcion1),25) as ITEM,                 det_candespachada AS CANTI,                 det_cantequivale AS CANTE,                 uni_abreviatura AS UNIDA,                 det_costotal AS COSTO,                 det_valtotal AS VALOR FROM genclasetran JOIN concomprobantes ON cla_aplicacion = 'IN' AND com_tipoComp = cla_tipoComp              LEFT JOIN conpersonas b ON b.per_codauxiliar = com_emisor              LEFT JOIN conpersonas p ON p.per_codauxiliar = com_codreceptor              LEFT JOIN invdetalle ON det_regnumero = com_regnumero              LEFT JOIN conactivos ON act_codauxiliar = det_coditem              LEFT JOIN genunmedida ON uni_CodUnidad = act_unimedida ";
*/
$lsQuery=fGetParam('pQryCom') ; 

if (strlen($lsQuery)>1){
   $query_Recordset1=$query_Recordset1." WHERE ". $lsQuery. "  ORDER  BY com_emisor, com_numcomp, com_tipocomp";
}

$Recordset1 = mysql_query($query_Recordset1, $conn1) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
/*
@TODO Hacer gsColFlag dinámica para habilitar la columna valor según el perfil del usuario
**/ 
$gsColFlag='none';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso8859-1" />
<title><?php echo $row_Recordset1['TRADES'] . " # " . $row_Recordset1['COMPR']; ?></title> <!-- @fah28/01/09-->
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	left:611px;
	top:39px;
	width:183px;
	height:31px;
	z-index:1;
}
.HEADER_DET{
	border-bottom:#999999 1pt solid;
	border-top:#999999 1pt solid;
	border-left:#999999 1pt solid;
	border-right:#999999 1pt solid;
	font-size: 10px; font-family: Arial,Tahoma,, Helvetica, sans-serif;
}
.LINE_DET{
	border-bottom:#999999 0pt solid;
	border-top:#999999 0pt solid;
	border-left:#999999 0pt solid;
	border-right:#999999 0pt solid;
	font-size: 12px; font-family: Arial,Tahoma,, Helvetica, sans-serif;
}
#apDiv2 {
	position:absolute;
	left:42px;
	top:42px;
	width:183px;
	height:22px;
	z-index:2;
}
.style2 {font-family: Arial,Tahoma,, Helvetica, sans-serif; font-weight: bold; font-size: 12px; 
	}
#apDiv3 {
	position:absolute;
	left:43px;
	top:66px;
	width:181px;
	height:53px;
	z-index:3;
}
#apDiv4 {
	position:absolute;
	left:43px;
	top:168px;
	width:181px;
	height:17px;
	z-index:4;
}
#apDiv5 {
	position:absolute;
	left:324px;
	top:87px;
	width:302px;
	height:23px;
	z-index:5;
}
#apDiv6 {
	position:absolute;
	left:696px;
	top:84px;
	width:90px;
	height:21px;
	z-index:6;
	text-align:right;
}
#apDiv7 {
	position:absolute;
	left:250px;
	top:112px;
	width:464px;
	height:39px;
	z-index:7;
}
.style3 {
	font-size: 14px;
	font-family: Arial,Tahoma,, Helvetica, sans-serif;
}
#apDiv8 {
	position:absolute;
	left:43px;
	top:187px;
	width:182px;
	height:23px;
	z-index:8;
}
#apDiv9 {
	position:absolute;
	left:250px;
	top:154px;
	width:79px;
	height:20px;
	z-index:9;
	text-align:center;
}
#apDiv10 {
	position:absolute;
	left:330px;
	top:154px;
	width:322px;
	height:21px;
	z-index:10;
}
.style4 {
	font-size: 12px;
	font-family: Arial,Tahoma,, Helvetica, sans-serif;
}
#apDiv11 {
	position:absolute;
	left:13px;
	top:16px;
	width:752px;
	height:482px;
	z-index:11;
}
.style5 {font-size: 10px; font-family: Arial,Tahoma,, Helvetica, sans-serif; }
.style6 {
	font-family: Arial,Tahoma,, Helvetica, sans-serif;
	font-size: 24px;
}
-->
</style>
</head>

<body onload="window.print();">
<div id="apDiv11" >
  <table width="100%" border="0">
    <tr>
      <td class="style2" colspan="3"><div align="center"><?php echo $row_Recordset1['TRADES'] . " # " . $row_Recordset1['COMPR']; ?></div></td> <!-- @fah28/01/09-->
    </tr>
    <tr>
      <td width="11%" class="style2">FECHA:</td>
      <td width="72%"><span class="style4"><?php echo $row_Recordset1['FECHA']; ?> </span></td>
      <td width="16%"><span class="style2">SEM: </span><span class="style3"><?php echo $row_Recordset1['REFOP']; ?></span></td>
    </tr>
    <tr>
      <td class="style2"><?php echo $row_Recordset1['TXTEMI']; ?>:</td> <!-- @fah28/01/09-->
      <td class="style4" colspan="2"><?php echo $row_Recordset1['BODEG']; ?></td>
    </tr>
    <tr>
      <td class="style2"><?php echo $row_Recordset1['TXTREC']; ?>:</td> <!-- @fah28/01/09-->
      <td class="style3"  colspan="2"><?php echo $row_Recordset1['RECEP']; ?></td>
    </tr>
    <tr>
      <td class="style2">CONCEPTO:</td>
      <td class="style4"  colspan="4"><?php echo $row_Recordset1['CONCEP']; ?></td>
    </tr>
  </table>
  <br /> 
  
    <table width="750" border="0" cellpadding="2" cellspacing="0">
      <tr height="20pt">
        <td width="17" class="HEADER_DET" >S</td>
        <td width="52" class="HEADER_DET">CODIGO</td>
        <td width="460" class="HEADER_DET">ITEM</td>
        <td width="28" class="HEADER_DET"><div align="center">UNI</div></td>
        <td width="95" class="HEADER_DET"><div align="center">CANTID.</div></td>
        <td width="74" class="HEADER_DET" style="display:<?php echo $gsColFlag?>"><div align="center">VALOR</div></td>
      </tr>
      <?php 
	  $cont_valor=0;
	  do { ?>
      <tr>
        
         <td class="LINE_DET"><?php print $row_Recordset1['SECUE']; ?></td>
         <td class="LINE_DET"><?php print $row_Recordset1['CODIT']; ?></td>
         <td class="LINE_DET"><?php print $row_Recordset1['ITEM']; ?></td>
         <td class="LINE_DET" width="28"><div align="center"><?php print $row_Recordset1['UNIDA']; ?></div></td>
        <td class="LINE_DET"><div align="right"><?php print $row_Recordset1['CANTE']; ?></div>
        <div align="right"></div><div align="justify"></div></td>
         <td class="LINE_DET" style="display:<?php echo $gsColFlag?>"><div align="right"><?php print $row_Recordset1['VALOR']; 
		 						  $cont_valor= $cont_valor + $row_Recordset1['VALOR'];?>
         </div></td>
		</tr>
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
      
      <tr>
        <td class="style5" colspan="4"><div align="right" class="style2">SUMA</div></td>
        <td class="style5" ><div align="right" class="style2"></div></td>
        <td class="style5" align="right" style="display:<?php echo $gsColFlag?>"><?php echo $cont_valor; ?></td>
      </tr>
  </table>
    <p>&nbsp;</p>
    <table width="100%"  border="0" cellpadding="2" cellspacing="0">
      <tr>
        <td class="HEADER_DET" valign="top"><div align="center">Aprobado</div></td>
        <td class="HEADER_DET" valign="top"><div align="center">Entregado</div></td>
        <td class="HEADER_DET" valign="top"><div align="center">Recibido</div></td>
      </tr>
      <tr>
        <td height="30" valign="top" class="HEADER_DET">&nbsp;</td>
        <td valign="top" class="HEADER_DET">&nbsp;</td>
        <td valign="top" class="HEADER_DET">&nbsp;</td>
      </tr>
      <tr>
        <td class="HEADER_DET"height="45" valign="bottom" colspan="3"><p><span class="style2">Nombre:</span>_____________________ <span class="style2">Placa:</span>_________________ <span class="style2">Veh&iacute;culo Marca:</span>____________________</p></td>
      </tr>
    </table>
<?php include('userfecha.inc.php'); ?>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>