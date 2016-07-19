<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Movimientos de Cuentas - Simple</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{assign var=acum value=0}
{assign var=sal value=0}  
{* {report recordset=$agData record=rec groups="det_CodCuenta, det_CodAuxiliar, com_FecContab,txt_Compr"	 resort=true} *}
{report recordset=$agData record=rec resort=true}

{*assign var=acumula value=0*}
{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        MOVIMIENTOS DE CUENTAS - SIMPLE<br>
        {$subtitulo}
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>
      <tr>
	<td colspan=4>{$SMARTY.script}	  </td>
	<td colspan=4>{$slPiePag}</td>
      </tr>
    </tfoot>
    
    <tbody>

    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">COD. CUENTA</td>
	<td class="headerrow"><strong>NOMBRE DE CUENTA</strong></td>
        <td class="headerrow"><strong>COD. AUXILIAR</strong></td>
	<td class="headerrow"><strong>NOMBRE AUXILIAR</strong></td>
    	<td class="headerrow">COMP</td>
	<td class="headerrow"><strong>CHEQ</strong></td>
        <td class="headerrow"><strong>FECHA</strong></td>
	<td class="headerrow"><strong>COD. BENEFICIARIO</strong></td>
	<td class="headerrow"><strong>BENEFICIARIO</strong></td>
        <td class="headerrow"><strong>GLOSA</strong></td>
        <td class="headerrow"><strong>DEBITO</strong></td>
        <td class="headerrow"><strong>CREDITO</strong></td>
        <td class="headerrow"><strong>SALDO</strong></td>
    </tr>
    
    {*
    <tr>
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td class="coldata ">Saldo Anterior</td>
	<td></td><td></td>
	  {assign var=cue value=$rec.det_CodCuenta}
	  {assign var=aux value=$rec.det_CodAuxiliar}
	  {assign var=sal value=$agSaldos[$cue][$aux]|default:0}
	  {if ($sal == 0)}
	    <td class="colnum ">{0}</td>
	  {else}
	    <td class="colnum ">{$sal|number_format:2}</td>
	  {/if}
    </tr>
    *}

	  

{/report_header}

{report_detail}
{if $cue neq $rec.det_CodCuenta or $aux neq $rec.det_CodAuxiliar}
  {assign var=sal value=0}
  <tr>
	  <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	  <td class="coldata ">Saldo Anterior</td>
	  <td></td><td></td>
	    {assign var=sal value=$agSaldos[$rec.det_CodCuenta][$rec.det_CodAuxiliar]|default:0}
	    {if ($sal == 0)}
	      <td class="colnum ">{0}</td>
	    {else}
	      <td class="colnum ">{$sal|number_format:2}</td>
	    {/if}
  </tr>
{/if}

  <tr>
      <td class="colnum ">{$rec.det_CodCuenta}</td>
        <td class="coldata ">{$rec.txt_NombCuenta}</td>
	<td class="colnum ">{$rec.det_CodAuxiliar}</td>
	<td class="coldata ">{$rec.txt_NombAuxiliar}</td>
	<td nowrap>{$rec.txt_Compr}</td>
	<td class="colnum ">{$rec.com_Cheque}</td>
        <td class="colnum ">{$rec.com_FecContab}</td>
	<td class="coldata ">{$rec.CRE}</td>
	<td class="coldata ">{$rec.beneficiario}</td>
        <td class="coldata ">{$rec.com_Concepto}</td>
        <td class="colnum ">{$rec.det_ValorDeb|number_format:2}</td>
        <td class="colnum ">{$rec.det_ValorCre|number_format:2}</td>
	{*assign var=sub value=$rec.txt_Saldo*}
	{*assign var="acumula" value=$acumula+$rec.txt_Saldo*}
	{* Actualizo el saldo*}
	<td class="colnum ">
	 
	{assign var=sal  value=$sal+$rec.txt_Saldo}
	{$sal = $sal + $rec.txt_Saldo}
	
	{* Para que se acumule el saldo por cuenta o por auxiliar*}	
	{assign var=cue value=$rec.det_CodCuenta}
	{assign var=aux value=$rec.det_CodAuxiliar}
	
	  
	{*math equation="x + y" x=$sal|default:0 y=$rec.txt_Saldo*}
	{*$acumula*}{*$sum.txt_Saldo*}</td>
	<!--<td class="colnum ">{$acum}:{$rec.txt_Saldo}:{$sal}</td>--><!--|number_format:0-->
	{*assign var=acum  value=$acum+$rec.txt_Saldo*}
	{*$acumula = $acumula + $rec.txt_Saldo*}
    </tr>
{/report_detail}

{report_footer}
<td colspan="12" style="text-align:left">{$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</td>
			<td >{$PiePagina}</td>
  </tbody>
  </table>
{/report_footer}

{/report}
</body>