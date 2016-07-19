<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CXC</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{* {assign var=acum value=0}
{assign var=sal value=0}
*}
<!-- resort=true -->
{report recordset=$agData record=rec groups="CCU, CAU, CHE"}

{report_header}
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong>
	<br> {$subtitulo}
	<br> {$rec.cue_Descripcion}
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="16"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td>
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">COMPROBANTE</td>
        <td class="headerrow"><strong>No DOCUMENTO</strong></td>
	<td class="headerrow">FECHA FACTURA</td>
	<td class="headerrow"><strong>CONCEPTO</strong></td>
    	<td class="headerrow"><strong>DEBITO<strong></td>
	<td class="headerrow"><strong>CREDITO<strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>	
    </tr>
{/report_header}


{report_header group="CCU"}
<tr >
    <td colspan=7>
      	<b> CUENTA: </b>
	{$rec.CCU} {$rec.CUE}
    </td>
  </tr>
{/report_header}

{report_header group="CAU"}
<tr >
    <td colspan=7>
      <b> CLIENTE: </b>
	{$rec.CAU} {$rec.DES}
    </td>
  </tr>
{/report_header}

{report_detail}
  <tr style="text-align:center; vertical-align:middle;">
	<td class="coldata "> {$rec.comp}</td>
        <td class="coldata">{$rec.CHE}</td>
	<td class="coldata "> {$rec.com_FecContab}</td>
	<td class="coldata "align="left">{$rec.com_Concepto}</td>
	<td class="colnum " >{$rec.det_ValDebito|number_format:2}</td>
  	<td class="colnum " >{$rec.det_ValCredito|number_format:2}</td>
        <td></td>
	{assign var=TDB value=$TDB+$rec.det_ValDebito}
	{assign var=TCD value=$TCD+$rec.det_ValCredito}
	{assign var=TSL value=$TDB-$TCD}
  </tr>
{/report_detail}


{report_footer group="CHE"}
    <tr >
      <td></td><td></td><td></td>
       <td><strong>Saldo de la factura<strong></td>
       <td></td><td></td>
       <td class="colnum "><strong>{$rec.SAL}</strong></td>       
    </tr>
    <tr></tr>
{/report_footer}


{report_footer}
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
      <td></td><td></td><td></td>
       <td> Totales: </td>
       <td>{$TDB|number_format:2}</td>
       <td>{$TCD|number_format:2}</td>
       <td>{$TSL|number_format:2}</td>       
    </tr>
  </tbody>
  </table>
{/report_footer}

{/report}
</body>