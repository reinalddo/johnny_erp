<?php
require_once('../Connections/conn1.php');
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("../LibPhp/ConLib.php");
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
include("../LibPhp/GenCifras.php");
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

?>
<?php
//print_r($_SESSION);
$qry_rs1 =
  "SELECT com_RegNumero AS 'REG',  
	  det_secuencia AS 'SEC',
	  com_TipoComp AS 'TIP',
	  com_NumComp AS 'COM',
	  com_FecTrans AS 'FTR',
	  com_FecContab AS 'FCO',
	  com_Emisor AS 'CEM',
	  com_CodReceptor AS 'CRE',
	  com_Receptor AS 'REC',
	  com_Concepto AS 'CON',
	  com_Usuario AS USR,
	  concat(det_CodCuenta, '   ') AS 'CCU',
	  '----' AS 'ABC',
	  IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',
	  cue_Descripcion AS 'CUE',
	  concat(IF(det_idauxiliar <> 0 ,concat(IFNULL( concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')),
	  concat(per_Apellidos, ' ', ifnull(per_Nombres, ' ')) ),                             ' :  ' ), ''), left(det_Glosa,15) )   AS 'DES',
	  det_NumCheque as 'DOC',
	  det_ValDebito  AS 'VDB',
	  det_ValCredito AS 'VCR'
	  FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
	      LEFT JOIN conpersonas ON (per_CodAuxiliar = det_IdAuxiliar)
	      LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
	      LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta)
	  WHERE " . fGetParam("pQry", "com_REgNumero=-9999");
if (fGetParam("pAdoDbg", 0) == 1) {echo "<br> Q1: "; print_r($qry_rs1); }
$db->execute("SET lc_time_names=es_EC");
$rs1 = $db->execute($qry_rs1);
    //fDbgContab("Paso 1a " . $sqltext);
    if (!$rs1) fErrorPage('',"NO SE PUDO DEFINIR LA TRANSACCION " . $pRNum);
    $rs1->MoveFirst();
    //fDbgContab("Paso 1aa " . $sqltext);
$r1 = $rs1->FetchNextObject();

//print_r($r1);
$slCondicion = 	  " WHERE " . fGetParam("pQry", "com_RegNumero=-9999");
//JVL 30S    "SELECT com_numcomp AS 'COM', UPPER(date_format(com_feccontab, '%d %M de %Y'))  as 'FEC',
$qry_rs2 =
  "SELECT com_numcomp AS 'COM', UPPER(date_format(com_feccontab, '%Y / %m / %d'))  as 'FEC',
    com_Emisor,
    com_tipocomp,
    concat(per_Apellidos, ' ', ifnull(per_Nombres, ' ')) AS BCO,
    det_valcredito as 'VAL', com_receptor as 'BEN',
    det_numcheque as CHQ
    from concomprobantes join condetalle on det_regnumero = com_regnumero
    join conpersonas on per_codauxiliar = det_idauxiliar " . $slCondicion;
;

if (fGetParam("pAdoDbg", 0) == 1) {echo "<br> Q2: "; print_r($qry_rs2); }
$rs2 = $db->execute($qry_rs2) or die(mysql_error());
$rs2->MoveFirst();
$r2 = $rs2->FetchNextObject(false);

$alEmpre = $db->GetAssoc("Select par_clave, concat(par_descripcion, ' ', ifnull(par_valor1,''), ' ',ifnull(par_valor2,''))
			from genparametros WHERE par_clave in('EGNOM', 'EGRUC')");

/*mysql_select_db($database_conn1, $conn1);
$query_Recordset1 = "SELECT  com_RegNumero AS 'REG',                     det_secuencia AS 'SEC',                     com_TipoComp AS 'TIP',                     com_NumComp AS 'COM',                     com_FecTrans AS 'FTR',                     com_FecContab AS 'FCO',                     com_Emisor AS 'CEM',                     com_CodReceptor AS 'CRE',                     com_Receptor AS 'REC',                     com_Concepto AS 'CON',                     concat(det_CodCuenta, '   ') AS 'CCU',                     '----' AS 'ABC',                     IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',                     cue_Descripcion AS 'CUE',                     concat(IF(det_idauxiliar <> 0 ,                                 concat(IFNULL( concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')),                                    concat(per_Apellidos, ' ', ifnull(per_Nombres, ' ')) ),                             ' :  ' ), ''), det_Glosa )   AS 'DES',                     det_NumCheque as 'CHE',                     det_ValDebito  AS 'VDB',                     det_ValCredito AS 'VCR'                  FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)                     LEFT JOIN conpersonas ON (per_CodAuxiliar = det_IdAuxiliar)                     LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)                     LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta) ";
$lsQuery=fGetParam('pQrycom') ;

if (strlen($lsQuery)>1){
   $query_Recordset1=$query_Recordset1." WHERE ". $lsQuery. " ORDER BY 1, 2";
} */
/*echo "<br><br><br> Resultados:";*/
//print_r($rs1);
//print_r($r2);


date_default_timezone_set('EC');
setlocale(LC_MONETARY, 'en_EC');
$slTexto = strtoupper(num2letras($r2->VAL, false, 2, 2, " Dolares", " /100 ")) . " *";
$slTexto = str_pad($slTexto, 40,"*");
$slTexto = str_pad($slTexto . " " , 40,"* ");
$slTexto .="* * * * * *";
$ilLong = strlen($slTexto);

/*$slTexto = str_pad($slTexto, 180,"* ");
$slTexto = str_pad($slTexto . " " , 80,"* ");
$ilLong = strlen($slTexto);*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>CHEQUE <?php echo $_SESSIONI['g_empresa'] . " - " . $r1->TIP . " - " . $r1->COM;?> </title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	left:79px;
	top:14px;
	width:183px;
	height:22px;
	z-index:1;
}
#apDiv2 {
	position:absolute;
	left:79px;
	top:38px;
	width:183px;
	height:22px;
	z-index:2;
}
.style1 {
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 14px;
}
.style2 {font-family: Tahoma, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 9pt; height: 12pt; border:   1 thin black  }
.style6 {font-family: Tahoma, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 9pt; height: 12pt; border:   1 thin black  }
#apDiv3 {
	position:absolute;
	left:80px;
	top:62px;
	width:181px;
	height:53px;
	z-index:3;
}
#apDiv4 {
	position:absolute;
	left:80px;
	top:154px;
	width:181px;
	height:17px;
	z-index:4;
}
/*#apDiv5 {
	position:absolute;
	left:397px;
	top:73px;
	width:268px;
	height:23px;
	z-index:5;
}
#apDiv6 {
	position:absolute;
	left:670px;
	top:73px;
	width:90px;
	height:21px;
	z-index:6;
	text-align:right;
}*/
#apDiv6 {
	position:absolute;
	left:8cm;
	top:0.5cm;
	width:9cm;
	height:0.6cm;
	z-index:5;
	font-size: 9pt;
	font-weight: bold
}
#apDiv5 {
	position:absolute;
	left:17cm;
	top:0.5cm;
	width:3cm;
	height:0.6cm;
	z-index:6;
	text-align:right;
	font-size: 12pt;
	font-weight: bold
}
#apDiv7 {
	position:absolute;
	left:311px;
	top:98px;
	width:464px;
	height:39px;
	z-index:7;
}
.style3 {
	font-size: 14px;
	font-family: Tahoma, Arial, Helvetica, sans-serif;
}
#apDiv8 {
	position:absolute;
	left:80px;
	top:173px;
	width:182px;
	height:23px;
	z-index:8;
}
#apDiv9 {
	position:absolute;
	left:314px;
	top:140px;
	width:79px;
	height:20px;
	z-index:9;
	text-align:center;
}
#apDiv10 {
	position:absolute;
	left:394px;
	top:140px;
	width:322px;
	height:21px;
	z-index:10;
}
.style4 {
	font-size: 12px;
	font-family: Tahoma, Arial, Helvetica, sans-serif;
}
#apDiv11 {
	position:absolute;
	left:43px;
	top:376px;
	width:724px;
	height:482px;
	z-index:11;
}
#lftBox{
    left:0.05cm;
    top:0.05cm;
    width:8cm;
    height:7cm;
}
.style5 {font-size: 10pt; font-family: Tahoma, Arial, Helvetica, sans-serif; border: 0}
.sinlineas{border-left: 0 none; border-right: 0 none; border-bottom: 0 none; border-bottom: 0 none; border-top: 0 none;
	font-size:8pt;
}
.colheader {border-left: 1px thin black; border-right: 1px thin black; border-bottom: 1px thin black; border-bottom: 1px thin black;
	border-top: 1px thin black;}
-->
</style>
</head>
<script>
	window.onload=function (){window.print()};
</script>
<body style="margin-left: 0cm; margin-top:0.3cm">
<table width="22cm">
  <tr>
    <!--<td rowspan=6 style="width:2.4cm; font-size: 9pt">
    </td> !-->
    <td style="width:0.1cm; height: 0.2cm">&nbsp;      	</td>
    <td style="width:13cm;   height: 0.2cm; font-size:10pt"><?php echo $r1->REC;?>     	</td>
    <td style="width:2cm; height: 0.2cm">&nbsp;            	</td>
    <td style="width:6cm;   height: 0.4cm; text-align:right ; vertical-align:top; font-size:12pt; font-weight:bolder">
	<?php echo str_pad(number_format($r2->VAL, 2,'.',',' ), 16,"***", STR_PAD_LEFT) ."***";?>            	</td>
  </tr>
  <tr>
    <td >&nbsp;      	</td>
    <td colspan=3 style="font-size: 10pt; height: 35pt; vertical-align:top; line-height:1.5; font-weight:bold">
      <?php echo $slTexto;?></td>
  </tr>
  <!-- <tr>
    <td >&nbsp;      	</td>
    <td colspan=3><?php echo "*******************************************************";?></td>
  </tr> !-->
  <tr>
  <tr>
    <td colspan=4 style="padding-left: 0pt; font-size:10pt">GUAYAQUIL, <?php echo $r2->FEC;?></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rs1);
?>
