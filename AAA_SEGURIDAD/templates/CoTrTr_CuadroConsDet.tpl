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
	<td colspan="18"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td>
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">EMPRESA</td>
	<td class="headerrow">FECHA CONTABL.</td>
	<td class="headerrow"><strong>No COMPROBANTE</strong></td>
        <td class="headerrow"><strong>CODIGO</strong></td>
	<td class="headerrow"><strong>PROVEEDOR</strong></td>
    	<td class="headerrow"><strong>FACTURA<strong></td>
	<td class="headerrow"><strong>No RETENCION</strong></td>
        <td class="headerrow"><strong>FECHA EMISION</strong></td>
	<td class="headerrow"><strong>FECHA VENCIMIENTO</strong></td>
        <td class="headerrow"><strong>DIAS VENCIDOS</strong></td>
        <td class="headerrow"><strong>CONCEPTO</strong></td>
        <td class="headerrow"><strong>VALOR FACTURA</strong></td>
        <td class="headerrow"><strong>IVA</strong></td>
	<td class="headerrow"><strong>TOTAL</strong></td>
	<td class="headerrow"><strong>ESTADO</strong></td>
	<td class="headerrow"><strong>SALDO</strong></td>
	<td class="headerrow"><strong>USUARIO/FECHA DE DIGITACION</strong></td>
	<td class="headerrow"><strong>INFORMACION DEL PAGO</strong></td>
    </tr>
{/report_header}

{report_detail}

  <tr vertical-align:middle;">
	<td class="colnum "> {$rec.empresa} </td>
	<td class="colnum "> {$rec.com_FecContab|date_format:'%Y-%m-%d'}</td>
	<td class="colnum ">{$rec.numComprobante}</td>
	<td class="coldata ">{$rec.idProvFact}</td>
	<td class="colnum ">{$rec.nombreProveedor}</td>
	<td class="colnum ">
	    {if $rec.ID}
	    <a href= "../Co_Files/CoTrTr_CuadroDet.rpt.php?&pidProvFact={$rec.idProvFact}&pnombreProveedor={$rec.nombreProveedor}&psecuencial={$rec.secuencial}&pbase={$rec.base}&pempresa={$rec.empresa}" target="_blank">
	    {/if}
	    &nbsp;{$rec.establecimiento|str_pad:3:'0':STR_PAD_LEFT}-{$rec.puntoEmision|str_pad:3:'0':STR_PAD_LEFT}-{$rec.secuencial|str_pad:7:'0':STR_PAD_LEFT}
	</td>
	<td class="coldata ">&nbsp;{$rec.estabretencion1|str_pad:3:'0':STR_PAD_LEFT}-{$rec.puntoEmiRetencion1|str_pad:3:'0':STR_PAD_LEFT}-{$rec.secretencion1|str_pad:7:'0':STR_PAD_LEFT}</td>
	<td class="coldata ">{$rec.fechaEmision|date_format:'%Y-%m-%d'}</td>
        <td class="coldata ">{$rec.com_FecVencim|date_format:'%Y-%m-%d'}</td>
        <td class="coldata ">{$rec.diasVencidos}</td>
        <td class="coldata ">{$rec.concepto}</td>
	<td class="colnum " >{$rec.valFactura|number_format:2}</td>
        <td class="colnum ">{$rec.iva|number_format:2}</td>
	<td class="colnum ">{$rec.total|number_format:2}</td>
        <td class="coldata ">{$rec.estado}</td>
	<td class="coldata ">{$rec.cpp_saldo}</td>
	<td class="coldata ">{$rec.usuario}</td>
	<td class="coldata ">{$rec.detalle}</td>
	
  	{assign var=tValor  value=$tValor+$rec.valFactura}
	{assign var=tIva  value=$tIva+$rec.iva}
	{assign var=tTotal  value=$tTotal+$rec.total}
	{assign var=tSaldo  value=$tSaldo+$rec.cpp_saldo}
    </tr>
{/report_detail}

{report_footer}
    <tr style="font-weight:bold;text-align:center; vertical-align:middle;">
      <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
      <td></td><td></td>
       <td> Totales: </td>
       <td>{$tValor}</td>
       <td>{$tIva}</td>
       <td>{$tTotal}</td>
       <td></td>
       <td>{$tSaldo}</td><td></td><td></td>
    </tr>
  <!-- <td colspan="14" style="text-align:left">{$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
  <td>{$PiePagina}</td> -->
  </tbody>
  </table>
{/report_footer}

{/report}
</body>