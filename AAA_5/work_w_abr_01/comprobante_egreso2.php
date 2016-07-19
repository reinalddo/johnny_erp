<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso8859-1" />
<title>Comprobante de Egreso</title>
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
#apDiv2 {
	position:absolute;
	left:42px;
	top:42px;
	width:183px;
	height:22px;
	z-index:2;
}
.style2 {font-family: Tahoma, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
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
	font-family: Tahoma, Arial, Helvetica, sans-serif;
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
	font-family: Tahoma, Arial, Helvetica, sans-serif;
}
#apDiv11 {
	position:absolute;
	left:12px;
	top:70px;
	width:752px;
	height:482px;
	z-index:11;
}
.style5 {font-size: 10px; font-family: Tahoma, Arial, Helvetica, sans-serif; }
.style6 {
	font-family: Tahoma, Arial, Helvetica, sans-serif;
	font-size: 24px;
}
-->
</style>
</head>

<body>
{report recordset=$agData record=rec	groups="quiebres" resort=true}
{report_header}
<span class="style6">{$gsEmpresa}</span>
<div id="apDiv11">
  <table width="100%" border="0">
    <tr>
      <td width="13%" class="style2">Fecha:</td>
      <td width="54%"><span class="style4">{$rec.txp_fecha}</span></td>
      <td width="33%"><div align="right" class="style4"><u>2008.10.31</u></div></td>
    </tr>
    <tr>
      <td class="style2">Concepto:</td>
      <td class="style4">{$rec.txp_concepto}</td>
      <td ><span class="style2">COMPROBANTE DE EGRESO:</span> <span class="style4">{$rec.txp_num_comprobante}</span></td>
    </tr>
    <tr>
      <td class="style2">Beneficiario:</td>
      <td class="style2">{$rec.txp_beneficiario}</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="style2">Banco:</td>
      <td class="style4">{$rec.txp_banco_cta}</td>
      <td class="style2">Nro. Cheque #</td>
    </tr>
    <tr>
      <td class="style2">Valor a Pagar:</td>
      <td class="style4">{$rec.txp_valor_pagar}</td>
      <td class="style2">{$rec.txp_num_cheque}</td>
    </tr>
    <tr>
      <td class="style4">Son:</td>
      <td class="style4">{$rec.txp_valor_numero}</td>
      <td>&nbsp;</td>
    </tr>
</table>  
  <br />
	<table width="100%" border="1" cellpadding="2" cellspacing="0">
    <tr>
      <td width="10%" class="style2">Cuenta</td>
      <td width="19%" class="style2">Nombre</td>
      <td width="25%" class="style2">Auxiliar</td>
      <td width="25%" class="style2">Centro </td>
      <td width="25%" class="style2">Subcentro </td>
      <td width="26%" class="style2">CONCEPTO</td>
      <td width="10%" class="style2"><div align="right">DEBE</div></td>
      <td width="10%" class="style2"><div align="right">HABER</div></td>
    </tr>
	</table>
{/report_header}

<table width="100%" border="1" cellpadding="2" cellspacing="0">
	{report_detail}
    <tr>
      <td width="7%" class="style5">{$rec.txp_num_cta}</td>
      <td width="12%" class="style5">{$rec.txp_banco_nombre}</td>
      <td width="16%" class="style5">{$rec.txp_nombre}</td>
      <td width="16%" class="style5">{$rec.txp_centro}</td>
      <td width="16%" class="style5">{$rec.txp_subcentro}</td>
      <td width="17%"><span class="style5">{$rec.txp_concepto}</span></td>
      <td width="8%" class="style5"><div align="right">{$rec.txp_debe}</div></td>
      <td width="8%" class="style5"><div align="right">{$rec.txp_haber}</div></td>
    </tr>
	{/report_detail}
    <tr>
      <td class="style5" colspan="6"><div align="right" class="style2">TOTAL DEL COMPROBANTE</div></td>
      <td class="style2"><div align="right">{$rec.txp_totaldebe}</div></td>
      <td class="style2"><div align="right">{$rec.txp_totalhaber}</div></td>
    </tr>
  </table>
  <table width="100%"  border="1" cellpadding="2" cellspacing="0">
{report_footer}
    <tr>
      <td class="style2" valign="top"><div align="center">Elaborado</div></td>
      <td class="style2" valign="top"><div align="center">Revisado</div></td>
      <td class="style2" valign="top"><div align="center">Aprobado</div></td>
      <td class="style3"  rowspan="2" valign="top"><div align="center" >Recibido por:</div>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;&nbsp;C.C.</p></td>
    </tr>
    <tr>
      <td>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p class="style4">{$rec.txp_elaborado}</p></td>
      <td>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p class="style4">{$rec.txp_revisado}</p></td>
      </td>
      <td>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p class="style4">{$rec.txp_aprobado}</p>
      </td>
    </tr>
{/report_footer}
  </table>
  <p>&nbsp;</p>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
{/report}
</body>
</html>
