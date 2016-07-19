<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<!--  FRUTIBONI - Quitar columnas: No Comprobante, Cod. Proveedor, No Retencion, estado, usuario/fecha Dig -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="smart" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CUADRO DE LIQUIDACION</title>
  
</head> 

<body id="top" style="font-family:'Arial'">
{assign var=acum value=0}
{assign var=sal value=0}




{report recordset=$agData record=rec resort=false}

{report_header}
    <hr/>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>      
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:6pt;">
      <td class="headerrow" colspan=18 style="background-color: #BDBDBD;">{$subtitulo} {$rec.EGNombre}</td>   
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:6pt;">
      <td class="headerrow" colspan=18 style="background-color: #BDBDBD;">{$subtitulo2}</td>   
    </tr>
    
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:6pt;">
      <td class="headerrow" colspan=2 style="background-color: #CEF6E3;"></td>
      <td class="headerrow" colspan=5 style="background-color: #81F781;">INGRESOS</td>
      <td class="headerrow" colspan=10 style="background-color: #F78181;">DESCUENTOS</td>
      <td class="headerrow" colspan=1 style="background-color: #FE9A2E;"></td> 
    </tr>
    <tr style="font-weight:bold; text-align:justify; vertical-align:middle; font-size:6pt;"> 
	<td class="headerrow" style="background-color: #CEF6E3;">Zona</td>
	<td class="headerrow" style="background-color: #CEF6E3;">Productor</td> 
	<td class="headerrow" style="background-color: #CEF6E3;">#LIQ</td> 
    	<td class="headerrow" style="background-color: #81F781;">Embarcadas</td>
	<td class="headerrow" style="background-color: #81F781;">Precio Real</td>
	<td class="headerrow" style="background-color: #81F781;">Total Fruta</td>
	<td class="headerrow" style="background-color: #81F781;">Empaque Pagado</td>
	<td class="headerrow" style="background-color: #81F781;">Total Ingresos</td>
	
	<td class="headerrow" style="background-color: #F5A9A9;">Rte. 1.00%</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Rte. 1.25%</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Rte. 1.50%</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Rte. 2.00%</td>
        <td class="headerrow" style="background-color: #F5A9A9;">Empaque Cobrado</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Gasto Administrativo</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Anticipo</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Evaluadores TCI</td>
	<td class="headerrow" style="background-color: #F5A9A9;">Otros Descuentos</td>    
	<td class="headerrow" style="background-color: #F5A9A9;">Total Descuentos</td>
	<td class="headerrow" style="background-color: #FAAC58;">NETO A PAGAR</td>
    </tr>
{/report_header}

{report_detail}
  <tr style="vertical-align:middle; font-size:6pt;">
	<td class="coldata" nowrap>{if $rec.tipo == 1}{$rec.txzona}{/if}</td>
	<td class="coldata" nowrap>{if $rec.tipo == 5}<strong>{$rec.productor}</strong>{else} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$rec.productor}{/if}</td>
	<td class="coldata" nowrap>{if $rec.tipo == 5}<strong>{$rec.liq}</strong>{else} &nbsp;{$rec.liq}{/if}</td>
        <td class="colnum ">{if $rec.tipo == 5}<strong>{$rec.cajEmb|number_format:0:".":","}</strong>{else}{$rec.cajEmb|number_format:0:".":","}{/if}</td>
        <td class="colnum ">{if $rec.tipo == 5}<strong>{$rec.pCaj|number_format:2:".":","}</strong>{else}{$rec.pCaj|number_format:2:".":","}{/if}</td>
	<td class="colnum ">{if $rec.tipo == 5}<strong>{$rec.tFruta|number_format:2:".":","}</strong>{else}{$rec.tFruta|number_format:2:".":","}{/if}</td>
	<td class="colnum ">{$rec.R4|number_format:2:".":","}</td>
        {*<td class="colnum ">{$rec.TBenf|number_format:2:".":","}</td>*}
	<td class="colnum ">{if $rec.tipo == 5}{$rec.tFruta+$rec.R4|number_format:2:".":","}{/if}</td>
	
	<!-- DESCUENTOS  -->
	<td class="colnum " style="text-align:right;">{$rec.R16|number_format:2:".":","}</td>
	<td class="colnum " style="text-align:right;">{$rec.R23|number_format:2:".":","}</td>
	<td class="colnum " style="text-align:right;">{$rec.R24|number_format:2:".":","}</td>
	<td class="colnum " style="text-align:right;">{$rec.R22|number_format:2:".":","}</td>
	<td class="colnum " style="text-align:right;">{$rec.R7|number_format:2:".":","}</td>
	<td class="colnum " style="text-align:right;">{$rec.R21|number_format:2:".":","}</td>
	<td class="colnum " style="text-align:right;">{$rec.R17|number_format:2:".":","}</td>
  	<td class="colnum " style="text-align:right;">{$rec.R18|number_format:2:".":","}</td>
	<td class="colnum " style="text-align:right;">{$rec.R14|number_format:2:".":","}</td>
        <td class="colnum " style="text-align:right;">{$rec.TDesc|number_format:2:".":","}</td>
	<!-- BONIFICACIONES  -->
	{*{assign var=PPromLiq  value=$rec.RTotal/$rec.cajEmb}
	<td class="colnum"  style="text-align:right;">{$PPromLiq|number_format:2:".":","}</td>
	<td class="colnum " style="text-align:right; ">{$rec.RTotalFin|number_format:2:".":","}</td>
	*}
	{*{assign var=vPagar  value=$rec.TBenf+$rec.TDesc}*}
	<!-- TOTALES DE CAJAS EMBARCADAS, PRECIOS -->
	{if $rec.tipo == 5}
	  {assign var=vPagar  value=$rec.tFruta+$rec.R4+$rec.TDesc}
	  {assign var=TotvPagar  value=$TotvPagar+$vPagar}
	{/if}
	
	<td class="colnum " style="text-align:right;">{if $rec.tipo == 5}{$vPagar|number_format:2:".":","}{/if}</td>
  
  
  
	<!-- TOTALES DE CAJAS EMBARCADAS, PRECIOS -->
	{if $rec.tipo == 5}
	  {assign var=TotcajEmb  value=$TotcajEmb+$rec.cajEmb}
	  {assign var=TottFruta  value=$TottFruta+$rec.tFruta}
	{/if}
    </tr>
{/report_detail}

{report_footer}
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
      <td colspan=2">TOTALES:</td>
       <td>{$TotcajEmb|number_format:0:".":","}</td>
    
    
       {assign var=tpPcajEmb	value=$TottFruta/$TotcajEmb}
       <td>{$tpPcajEmb|number_format:2:".":","}</td>
    
       <td>{$TottFruta|number_format:2:".":","}</td>
       <td>{$sum.R4|number_format:2:".":","}</td>
       {*<td>{$sum.TBenf|number_format:2:".":","}</td>*}
       <td>{$TottFruta+$sum.R4|number_format:2:".":","}</td>
    
    
       <td>{$sum.R16|number_format:2:".":","}</td>
       <td>{$sum.R23|number_format:2:".":","}</td>
       <td>{$sum.R24|number_format:2:".":","}</td>
       <td>{$sum.R22|number_format:2:".":","}</td>
       <td>{$sum.R7|number_format:2:".":","}</td>
       <td>{$sum.R21|number_format:2:".":","}</td>
       <td>{$sum.R17|number_format:2:".":","}</td>
       <td>{$sum.R18|number_format:2:".":","}</td>
       <td>{$sum.R14|number_format:2:".":","}</td>
       <td>{$sum.TDesc|number_format:2:".":","}</td>
       <td>{$TotvPagar|number_format:2:".":","}</td>
       
       
    
  <tr><td colspan="17" style="text-align:left"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td></tr>
  </tbody>
  </table>
{/report_footer}

{/report}
</body>