<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="root" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Detalle de Cuenta por Pagar</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{assign var=acum value=0}
{assign var=sal value=0}

{report recordset=$agData record=rec resort=true}

{report_header}
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        {$subtitulo} <br>
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan="14"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td>
    </tfoot>
    
    <tbody>
    <tr>
      <td><strong>PROVEEDOR DE FACTURA:</strong></td>
      <td colspan="6">{$rec.det_idauxiliar} - 
      {$pnombreProveedor}</td>
    </tr>
    <tr>
      <td><strong>FACTURA:</strong></td>
      <td colspan="6">{$rec.det_numcheque}</td>
    </tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">{$Encabezado}</td>
	<td class="headerrow">CUENTA CONTABLE.</td>
	<td class="headerrow">TIPO DE COMPROBRANTE.</td>
	<td class="headerrow">No DE COMPROBANTE.</td>
	<td class="headerrow">FECHA CONTABLE</td>
	<td class="headerrow">VALOR.</td>
	<td class="headerrow">GLOSA.</td>
    </tr>
{/report_header}

{report_detail}
  <tr style="text-align:center; vertical-align:middle;">
	<td class="colnum " align="center">{$rec.empresa}</td>
        <td class="colnum " align="center">{$rec.det_codcuenta}</td>
	<td class="colnum " align="center">{$rec.com_tipocomp}</td>
	<td class="colnum " align="center">{$rec.com_NumComp}</td>
	<td class="colnum " align="center">{$rec.com_FecContab}</td>
	<td class="colnum " align="right">{$rec.valor|number_format:2}</td>
	<td align="center">{$rec.det_Glosa}</td>
  
  	{*{assign var=tdebe  value=$tdebe+$rec.det_valdebito}*}
	{assign var=tvalor value=$tvalor+$rec.valor}

    </tr>
{/report_detail}

{report_footer}
 
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
       <td </td>
       <td </td>
       <td </td>
       <td </td>
       <td> Total: </td>
       <td align="right">{$tvalor|number_format:2}</td>
       <td></td>
    </tr> 
  <!-- <td colspan="14" style="text-align:left">{$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
  <td>{$PiePagina}</td> -->
  </tbody>
  </table>
{/report_footer}

{/report}
</body>