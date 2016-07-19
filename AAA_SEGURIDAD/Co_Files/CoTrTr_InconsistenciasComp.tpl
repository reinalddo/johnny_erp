<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para reporte: Cuadro de Cuentas por Pagar
      Consolidado para todas las empresas
      Erika Suárez
      29/Ene/2010
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Cuentas por Pagar - Consolidado</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{assign var=acum value=0}
{assign var=sal value=0}
{assign var=countRec value=0}
{report recordset=$agData record=rec resort=true}
{report_header}

    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        {$subtitulo} <br>
    </p>
   <table border=1 cellspacing=0 style="width:50%;">
    <thead>
    </thead>
    <tfoot>
      </tfoot>
    
    <tbody>
    <tr><td colspan=7 style="font-size: medium; text-align:center;">COMPROBANTES DESCUADRADOS</td></tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">TIPO COMP.</td>
	<td class="headerrow">COMPROBANTE</td>
	<td class="headerrow"><strong>No REGISTRO</strong></td>
	<td class="headerrow"><strong>FECHA</strong></td>
        <td class="headerrow"><strong>DEBITO</strong></td>
	<td class="headerrow"><strong>CREDITO</strong></td>
    	<td class="headerrow"><strong>SALDO<strong></td>
   </tr>
{/report_header}

{report_detail}
  <tr>
	<td class="colnum ">{$rec.tip} </td>
	<td class="colnum ">{$rec.cmp}</td>
	<td class="colnum ">{$rec.reg}</td>
	<td class="colnum ">{$rec.fecha}</td>
	<td class="colnum ">{$rec.deb|number_format:2}</td>
        <td class="colnum ">{$rec.cre|number_format:2}</td>
	<td class="colnum">{$rec.sal|number_format:2}</td>
	
	{assign var=tdeb  value=$tdeb+$rec.deb}
	{assign var=tcre  value=$tcre+$rec.cre}
	{assign var=tsal  value=$tsal+$rec.sal}
	{assign var=countRec value=$countRec+1}
    </tr>
{/report_detail}

{report_footer}
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
       <td></td><td>Total Reg:</td><td>{$countRec}</td><td>Suman:</td>
       <td>{$tdeb}</td>
       <td>{$tcre}</td>
       <td>{$tsal}</td>
    </tr>
  <!-- <td colspan="14" style="text-align:left">{$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
  <td>{$PiePagina}</td> -->
  </tbody>
  </table>
{/report_footer}
{/report}




{assign var=countRec value=0}
<!--  reporte de los comprobantes con estado pendiente -->
{report recordset=$agData3 record=rec resort=true}
{report_header}
<br><br>
   <table border=1 cellspacing=0 style="width:50%;">
    <thead>
    </thead>
    <tfoot>
    </tfoot>
    
    <tbody>
    <tr><td colspan=7 style="font-size: medium; text-align:center;">COMPROBANTES PENDIENTES</td></tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">TIPO COMP.</td>
	<td class="headerrow">COMPROBANTE</td>
	<td class="headerrow"><strong>No REGISTRO</strong></td>
	<td class="headerrow"><strong>FECHA</strong></td>
   </tr>
{/report_header}

{report_detail}
  <tr>
	<td class="colnum ">{$rec.tip} </td>
	<td class="colnum ">{$rec.cmp}</td>
	<td class="colnum ">{$rec.reg}</td>
	<td class="colnum ">{$rec.fecha}</td>

	{assign var=countRec value=$countRec+1}
    </tr>
{/report_detail}

{report_footer}

    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
       <td></td><td>Totales:</td>
       <td>{$countRec}</td><td></td>
    </tr>
  </tbody>
  </table>
{/report_footer}
{/report}






{assign var=countRec value=0}
<!--  reporte de los comprobantes que tienen el periodo incorrecto-->
{report recordset=$agData2 record=rec resort=true}
{report_header}
<br><br>
   <table border=1 cellspacing=0 style="width:50%;">
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="17"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td>
    </tfoot>
    
    <tbody>
    <tr><td colspan=7 style="font-size: medium; text-align:center;">PERIODOS INCORRECTOS</td></tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">TIPO COMP.</td>
	<td class="headerrow">COMPROBANTE</td>
	<td class="headerrow"><strong>No REGISTRO</strong></td>
	<td class="headerrow"><strong>FECHA</strong></td>
        <td class="headerrow"><strong>PERIODO</strong></td>
	<td class="headerrow"><strong>PERIODO COMP</strong></td>
   </tr>
{/report_header}

{report_detail}
  <tr>
	<td class="colnum ">{$rec.tip} </td>
	<td class="colnum ">{$rec.cmp}</td>
	<td class="colnum ">{$rec.reg}</td>
	<td class="colnum ">{$rec.fecha}</td>
	<td class="colnum ">{$rec.periodo|number_format:0}</td>
        <td class="colnum ">{$rec.periodoCmp|number_format:0}</td>
	
	{assign var=countRec value=$countRec+1}
    </tr>
{/report_detail}

{report_footer}

    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
       <td></td><td>Totales:</td>
       <td>{$countRec}</td><td></td>
       <td></td><td></td>
    </tr>
  </tbody>
  </table>
{/report_footer}
{/report}




</body>