<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Movimientos de Cuentas</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="det_CodCuenta, det_CodAuxiliar, com_FecContab,txt_Compr" resort=true}
{assign var=acum value=0}
{*assign var=acumula value=0*}
{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        MOVIMIENTOS DE CUENTAS<br>
        {$subtitulo}
    </p>
    <table border=1 cellspacing=0 >

{/report_header}

{report_header group="det_CodCuenta"}
    <tr ><td colspan=8><strong>CUENTA: </strong>{$rec.det_CodCuenta}: {$rec.txt_NombCuenta}</td>
    </tr>
    {*assign var=acum  value=0*}
{/report_header}

{report_header group="det_CodAuxiliar"}
    <tr style="height:40px; vertical-align:middle;">
      <td colspan=8 style="vertical-align:bottom;"><strong>AUXILIAR: </strong>{$rec.det_CodAuxiliar}: {$rec.txt_NombAuxiliar}</td>
    </tr>
    {*assign var=acum  value=0*}
    {*assign var=acum  value=$sal*}
    {*$acumula = $sal*}
    {*assign var=acum value=$sal*}
    {*math equation="x + 0 " x=$sal*}
    {*$acumula = 0*}
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	<td class="headerrow">COMP</td>
	<td class="headerrow"><strong>CHEQ</strong></td>
        <td class="headerrow"><strong>FECHA</strong></td>
	<td class="headerrow"><strong>BENEFICIARIO</strong></td>
        <td class="headerrow"><strong>GLOSA</strong></td>
        <td class="headerrow"><strong>DEBITO</strong></td>
        <td class="headerrow"><strong>CREDITO</strong></td>
        <td class="headerrow"><strong>SALDO</strong></td>
    </tr>
    <tr>
	<td></td><td></td><td></td><td></td>
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
{/report_header}

{report_detail}
    <tr><td nowrap>{$rec.txt_Compr}</td><td class="colnum ">{$rec.com_Cheque}</td>
        <td class="colnum ">{$rec.com_FecContab}</td>
	<td class="coldata ">{$rec.beneficiario}</td>
        <td class="coldata ">{$rec.com_Concepto}</td>
        <td class="colnum ">{$rec.det_ValorDeb|number_format:2}</td>
        <td class="colnum ">{$rec.det_ValorCre|number_format:2}</td>
	{*assign var=sub value=$rec.txt_Saldo*}
	{*assign var="acumula" value=$acumula+$rec.txt_Saldo*}
	<td class="colnum ">{*math equation="x + y" x=$sal|default:0 y=$rec.txt_Saldo*}
	{*$acumula*}{*$sum.txt_Saldo*}</td>
	<!--<td class="colnum ">{$acum}:{$rec.txt_Saldo}:{$sal}</td>--><!--|number_format:0-->
	{*assign var=acum  value=$acum+$rec.txt_Saldo*}
	{*$acumula = $acumula + $rec.txt_Saldo*}
    </tr>
{/report_detail}

{report_footer group="det_CodAuxiliar"}
    <tr >
        <td></td><td></td><td></td><td></td>
        <td class="colnum">SUBTOTAL AUXILIAR </td>
        {if ($sum.det_ValorDeb > 0)}
            <td class="colnum headerrow">{$sum.det_ValorDeb|number_format:2}</td>
        {else}
            <td></td>
        {/if}
        {if ($sum.det_ValorCre > 0)}
            <td class="colnum headerrow">{$sum.det_ValorCre|number_format:2}</td>
        {else}
            <td></td>
        {/if}
        <td class="colnum headerrow">{$sal+$sum.txt_Saldo|number_format:2}</td>
        <!--<td class="colnum headerrow">{$sum.txt_Saldo}</td>-->
    </tr>
    <tr></tr>
{/report_footer}

{report_footer group="det_CodCuenta"}
    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr >
        <td></td><td></td><td></td><td></td>
        <td class="colnum"><strong>SUBTOTAL CUENTA {*$rec.det_CodCuenta*}{*$rec.txt_NombCuenta*}</strong></td>
        {if ($sum.det_ValorDeb > 0)}
            <td class="colnum headerrow">{$sum.det_ValorDeb|number_format:2}</td>
        {else}
            <td></td>
        {/if}
        {if ($sum.det_ValorCre > 0)}
            <td class="colnum headerrow">{$sum.det_ValorCre|number_format:2}</td>
        {else}
            <td></td>
        {/if}
        <td></td>
        <!--<td class="colnum headerrow">{$sum.txt_Saldo}</td>-->
	{*assign var=acumula value=0*}
	
    </tr>
    
{/report_footer}

{report_footer}
  </table>
{/report_footer}

{/report}
</body>