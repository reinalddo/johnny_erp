<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
  <meta name="author" content="Smart" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Libro de Compras</title>
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="FECHA_D_CONT,ID,Proveedor,RUC" resort=true}
{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        LIBRO DE COMPRAS<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 >
	<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	  <td rowspan=2 class="headerrow"><strong>ID</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Proveedor</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Ruc</strong></td>
	  <td rowspan=2 class="headerrow"><strong>T/D</strong></td>
	  <td class="headerrow" colspan=3 style="text-align:center;"><strong>Comp Venta</strong></td>
	  <td rowspan=2 class="headerrow"><strong>CC</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Fecha Imp.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Fecha Cont.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Fecha Validez</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Aut. SRI</strong></td>
	  <td rowspan=2 class="headerrow"><strong>N/D Aut.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Base Imp. 12%</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Base Imp. 0%</strong></td>
	  <td rowspan=2 class="headerrow"><strong>IVA</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Total Compra</strong></td>
	  <td colspan=6 class="headerrow"><strong>Retencion IVA</strong></td>
	  <!--<td rowspan=2 class="headerrow"><strong>Monto IVA Bienes</strong></td>
	  <td rowspan=2 class="headerrow"><strong>% Ret. Bienes</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Valor Ret. Bienes</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Monto IVA Serv.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>% Ret. Serv.</strong></td>
	  <td rowspan=2 class="headerrow"><strong>Valor Ret. Serv.</strong></td>-->
	  <td colspan=3 class="headerrow"><strong>Ret. Fte.</strong></td>
	  <!--
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 2%</strong></td>
	  
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 5%</strong></td>
	  
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 8%</strong></td>
	  
	  <td colspan=2 class="headerrow"><strong>Ret. Fte. 25%</strong></td>-->
	  
	  <td rowspan=2 class="headerrow"><strong>Total a Pagar</strong></td>
	  <td colspan=3 class="headerrow" style="text-align:center;"><strong>No.Comp.Ret.</strong></td>
	</tr>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
	    
            <td class="headerrow"><strong>Esta blecim</strong></td>
            <td class="headerrow"><strong>Punto Emision</strong></td>
            <td class="headerrow"><strong>Secuen cial</strong></td>
	    
            <td class="headerrow"><strong>Monto IVA Bienes</strong></td>
	    <td class="headerrow"><strong>% Ret. Bienes</strong></td>
	    <td class="headerrow"><strong>Valor Ret. Bienes</strong></td>
	    <td class="headerrow"><strong>Monto IVA Serv.</strong></td>
	    <td class="headerrow"><strong>% Ret. Serv.</strong></td>
	    <td class="headerrow"><strong>Valor Ret. Serv.</strong></td>
	
            <td class="headerrow"><strong>Porcentaje</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <!--<td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>
	    <td class="headerrow"><strong>Valor</strong></td>
	    <td class="headerrow"><strong>Codigo</strong></td>-->
	
	    <td class="headerrow"><strong>Esta blecim</strong></td>
            <td class="headerrow"><strong>Punto Emision</strong></td>
            <td class="headerrow"><strong>Secuen cial</strong></td>
        </tr>
{/report_header}

{report_header group="ID"}
    <tr>
        <td class="colnum ">{$rec.ID}</td>
        <td nowrap>{$rec.Proveedor}</td>
        <td class="colnum ">{$rec.RUC}</td>        
        <td class="colnum ">{$rec.TIPO_DOC}</td>
        <td class="colnum ">{$rec.establecimiento}</td>
	<td class="colnum ">{$rec.puntoEmision}</td>
	<td class="colnum ">{$rec.secuencial}</td>
	<td class="colnum ">{$rec.CC}</td>
	<td class="colnum ">{$rec.FECHA_IMP}</td>
        <td class="colnum ">{$rec.FECHA_D_CONT}</td>
        <td class="colnum ">{$rec.FECHA_VALIDEZ}</td>
        <td class="colnum ">{$rec.AUT_SRI}</td>
        <td class="colnum ">{$rec.N_D_AUT}</td>
        <td class="colnum " style="text-align:right;">{$rec.BASE_12|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.BASE_0|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.IVA|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.TOTAL_COMPRA|number_format:2}</td>
	<td class="colnum " style="text-align:right;">{$rec.montoIvaBienes|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.PIVAB|number_format:0}</td>
	<td class="colnum " style="text-align:right;">{$rec.valorRetBienes|number_format:2}</td>
	<td class="colnum " style="text-align:right;">{$rec.montoIvaServicios|number_format:2}</td>
	<td class="colnum " style="text-align:right;">{$rec.PIVAS|number_format:0}</td>
	<td class="colnum " style="text-align:right;">{$rec.valorRetServicios|number_format:2}</td>
	{assign var=ret0 value=0}
	{assign var=ret1 value=0}
	{assign var=ret11 value=0}
	{assign var=ret12 value=0}
	{assign var=ret2 value=0}
	{assign var=ret5 value=0}
	{assign var=ret8 value=0}
	{assign var=ret25 value=0}
	{if ($rec.RET_1 != 0 || $rec.RET_11 != 0 || $rec.RET_12 != 0 ||$rec.RET_2 != 0) || $rec.RET_5 != 0 || $rec.RET_8 != 0 || $rec.RET_25 != 0}
	  {if ($rec.RET_1 != 0)}
	    {assign var=ret1 value=1}
	    <td class="colnum " style="text-align:right;">1%</td>
	    <td class="colnum " style="text-align:right;">{$rec.RET_1|number_format:2}</td>
            <td class="colnum " style="text-align:right;">{$rec.CRT_1}</td>
	  {elseif ($rec.RET_11 != 0)}
	    {assign var=ret11 value=1}
	    <td class="colnum " style="text-align:right;">1%</td>
	    <td class="colnum " style="text-align:right;">{$rec.RET_11|number_format:2}</td>
            <td class="colnum " style="text-align:right;">{$rec.CRT_11}</td>
	  {elseif ($rec.RET_12 != 0)}
	    {assign var=ret12 value=1}
	    <td class="colnum " style="text-align:right;">1%</td>
	    <td class="colnum " style="text-align:right;">{$rec.RET_12|number_format:2}</td>
            <td class="colnum " style="text-align:right;">{$rec.CRT_12}</td>
	  {elseif ($rec.RET_2 != 0)}
	    {assign var=ret2 value=1}
	    <td class="colnum " style="text-align:right;">2%</td>
	    <td class="colnum " style="text-align:right;">{$rec.RET_2|number_format:2}</td>
            <td class="colnum " style="text-align:right;">{$rec.CRT_2}</td>
	  {elseif ($rec.RET_5 != 0)}
	    {assign var=ret5 value=1}
	    <td class="colnum " style="text-align:right;">5%</td>
	    <td class="colnum " style="text-align:right;">{$rec.RET_5|number_format:2}</td>
            <td class="colnum " style="text-align:right;">{$rec.CRT_5}</td>
	  {elseif ($rec.RET_8 != 0)}
	    {assign var=ret8 value=1}
	    <td class="colnum " style="text-align:right;">8%</td>
	    <td class="colnum " style="text-align:right;">{$rec.RET_8|number_format:2}</td>
            <td class="colnum " style="text-align:right;">{$rec.CRT_8}</td>
	  {elseif ($rec.RET_25 != 0)}
	    {assign var=ret25 value=1}
	    <td class="colnum " style="text-align:right;">25%</td>
	    <td class="colnum " style="text-align:right;">{$rec.RET_25|number_format:2}</td>
            <td class="colnum " style="text-align:right;">{$rec.CRT_25}</td>
	  {else}
   	 <td></td><td></td><td></td>
	  {/if}
	{else}
	    <td class="colnum " style="text-align:right;"></td>
	    <td class="colnum " style="text-align:right;">{$rec.RET_0|number_format:2}</td>
       <td class="colnum " style="text-align:right;">{$rec.CRT_0}</td>
	{/if}
	
		
	{assign var=ret value=$rec.valorRetBienes+$rec.valorRetServicios+$rec.RET_1+$rec.RET_11+$rec.RET_12+$rec.RET_2+$rec.RET_5+$rec.RET_8+$rec.RET_25}
	{assign var=tot value=$rec.TOTAL_COMPRA-$ret}
        <td class="colnum " style="text-align:right;">{$tot|number_format:2}</td>
        <td class="colnum ">{$rec.estabRetencion1|number_format:0}</td>
	<td class="colnum ">{$rec.puntoEmiRetencion1|number_format:0}</td>
	<td class="colnum ">{$rec.secRetencion1|number_format:0:"":""}</td>
    </tr>
{/report_header}

{report_detail}
	
        {if ($rec.RET_1 != 0 && $ret1 != 1)}
            <tr><td class="colnum ">{$rec.ID}</td>
		<td nowrap>{$rec.Proveedor}</td>
		<td class="colnum ">{$rec.RUC}</td>        
		<td class="colnum ">{$rec.TIPO_DOC}</td>
		<td class="colnum ">{$rec.establecimiento}</td>
		<td class="colnum ">{$rec.puntoEmision}</td>
		<td class="colnum ">{$rec.secuencial}</td>
		<td class="colnum ">{$rec.CC}</td>
		<td class="colnum ">{$rec.FECHA_IMP}</td>
		<td class="colnum ">{$rec.FECHA_D_CONT}</td>
		<td class="colnum ">{$rec.FECHA_VALIDEZ}</td>
		<td class="colnum ">{$rec.AUT_SRI}</td>
		<!--<td></td><td></td><td></td><td></td><td></td><td></td><td></td>-->
		<!--<td></td><td></td><td></td><td></td><td></td>--><td></td><td></td>
		<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		<td></td><td></td>
                <td class="colnum " style="text-align:right;">1%</td>
                <td class="colnum " style="text-align:right;">{$rec.RET_1|number_format:2}</td>
                <td class="colnum " style="text-align:right;">{$rec.CRT_1}</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        {/if}
        {if ($rec.RET_11 != 0 && $ret11 != 1)}
            <tr><td class="colnum ">{$rec.ID}</td>
		<td nowrap>{$rec.Proveedor}</td>
		<td class="colnum ">{$rec.RUC}</td>        
		<td class="colnum ">{$rec.TIPO_DOC}</td>
		<td class="colnum ">{$rec.establecimiento}</td>
		<td class="colnum ">{$rec.puntoEmision}</td>
		<td class="colnum ">{$rec.secuencial}</td>
		<td class="colnum ">{$rec.CC}</td>
		<td class="colnum ">{$rec.FECHA_IMP}</td>
		<td class="colnum ">{$rec.FECHA_D_CONT}</td>
		<td class="colnum ">{$rec.FECHA_VALIDEZ}</td>
		<td class="colnum ">{$rec.AUT_SRI}</td>
		<!--<td></td><td></td><td></td><td></td><td></td><td></td><td></td>-->
		<!--<td></td><td></td><td></td><td></td><td></td>--><td></td><td></td>
		<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		<td></td><td></td>
                <td class="colnum " style="text-align:right;">1%</td>
                <td class="colnum " style="text-align:right;">{$rec.RET_11|number_format:2}</td>
                <td class="colnum " style="text-align:right;">{$rec.CRT_11}</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        {/if}
        {if ($rec.RET_12 != 0 && $ret12 != 1)}
            <tr><td class="colnum ">{$rec.ID}</td>
		<td nowrap>{$rec.Proveedor}</td>
		<td class="colnum ">{$rec.RUC}</td>        
		<td class="colnum ">{$rec.TIPO_DOC}</td>
		<td class="colnum ">{$rec.establecimiento}</td>
		<td class="colnum ">{$rec.puntoEmision}</td>
		<td class="colnum ">{$rec.secuencial}</td>
		<td class="colnum ">{$rec.CC}</td>
		<td class="colnum ">{$rec.FECHA_IMP}</td>
		<td class="colnum ">{$rec.FECHA_D_CONT}</td>
		<td class="colnum ">{$rec.FECHA_VALIDEZ}</td>
		<td class="colnum ">{$rec.AUT_SRI}</td>
		<!--<td></td><td></td><td></td><td></td><td></td><td></td><td></td>-->
		<!--<td></td><td></td><td></td><td></td><td></td>--><td></td><td></td>
		<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		<td></td><td></td>
                <td class="colnum " style="text-align:right;">1%</td>
                <td class="colnum " style="text-align:right;">{$rec.RET_12|number_format:2}</td>
                <td class="colnum " style="text-align:right;">{$rec.CRT_12}</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        {/if}
        {if ($rec.RET_2 != 0 && $ret2 != 1)}
            <tr><td class="colnum ">{$rec.ID}</td>
		<td nowrap>{$rec.Proveedor}</td>
		<td class="colnum ">{$rec.RUC}</td>        
		<td class="colnum ">{$rec.TIPO_DOC}</td>
		<td class="colnum ">{$rec.establecimiento}</td>
		<td class="colnum ">{$rec.puntoEmision}</td>
		<td class="colnum ">{$rec.secuencial}</td>
		<td class="colnum ">{$rec.CC}</td>
		<td class="colnum ">{$rec.FECHA_IMP}</td>
		<td class="colnum ">{$rec.FECHA_D_CONT}</td>
		<td class="colnum ">{$rec.FECHA_VALIDEZ}</td>
		<td class="colnum ">{$rec.AUT_SRI}</td>
		<!--<td></td><td></td><td></td><td></td><td></td><td></td><td></td>-->
		<!--<td></td><td></td><td></td><td></td><td></td>--><td></td><td></td>
		<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		<td></td><td></td>
                <td class="colnum " style="text-align:right;">2%</td>
                <td class="colnum " style="text-align:right;">{$rec.RET_2|number_format:2}</td>
                <td class="colnum " style="text-align:right;">{$rec.CRT_2}</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        {/if}
	
        {if ($rec.RET_5 != 0 && $ret5 != 1)}
            <tr><td class="colnum ">{$rec.ID}</td>
		<td nowrap>{$rec.Proveedor}</td>
		<td class="colnum ">{$rec.RUC}</td>        
		<td class="colnum ">{$rec.TIPO_DOC}</td>
		<td class="colnum ">{$rec.establecimiento}</td>
		<td class="colnum ">{$rec.puntoEmision}</td>
		<td class="colnum ">{$rec.secuencial}</td>
		<td class="colnum ">{$rec.CC}</td>
		<td class="colnum ">{$rec.FECHA_IMP}</td>
		<td class="colnum ">{$rec.FECHA_D_CONT}</td>
		<td class="colnum ">{$rec.FECHA_VALIDEZ}</td>
		<td class="colnum ">{$rec.AUT_SRI}</td>
		<!--<td></td><td></td><td></td><td></td><td></td><td></td><td></td>-->
		<!--<td></td><td></td><td></td><td></td><td></td>--><td></td><td></td>
		<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		<td></td><td></td>
                <td class="colnum " style="text-align:right;">5%</td>
                <td class="colnum " style="text-align:right;">{$rec.RET_5|number_format:2}</td>
                <td class="colnum " style="text-align:right;">{$rec.CRT_5}</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        {/if}
        
        {if ($rec.RET_8 != 0 && $ret8 != 1)}
            <tr><td class="colnum ">{$rec.ID}</td>
		<td nowrap>{$rec.Proveedor}</td>
		<td class="colnum ">{$rec.RUC}</td>        
		<td class="colnum ">{$rec.TIPO_DOC}</td>
		<td class="colnum ">{$rec.establecimiento}</td>
		<td class="colnum ">{$rec.puntoEmision}</td>
		<td class="colnum ">{$rec.secuencial}</td>
		<td class="colnum ">{$rec.CC}</td>
		<td class="colnum ">{$rec.FECHA_IMP}</td>
		<td class="colnum ">{$rec.FECHA_D_CONT}</td>
		<td class="colnum ">{$rec.FECHA_VALIDEZ}</td>
		<td class="colnum ">{$rec.AUT_SRI}</td>
		<!--<td></td><td></td><td></td><td></td><td></td><td></td><td></td>-->
		<!--<td></td><td></td><td></td><td></td><td></td>--><td></td><td></td>
		<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		<td></td><td></td>
                <td class="colnum " style="text-align:right;">8%</td>
                <td class="colnum " style="text-align:right;">{$rec.RET_8|number_format:2}</td>
                <td class="colnum " style="text-align:right;">{$rec.CRT_8}</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        {/if}
	
        {if ($rec.RET_25 != 0 && $ret25 != 1)}
            <tr><td class="colnum ">{$rec.ID}</td>
		<td nowrap>{$rec.Proveedor}</td>
		<td class="colnum ">{$rec.RUC}</td>        
		<td class="colnum ">{$rec.TIPO_DOC}</td>
		<td class="colnum ">{$rec.establecimiento}</td>
		<td class="colnum ">{$rec.puntoEmision}</td>
		<td class="colnum ">{$rec.secuencial}</td>
		<td class="colnum ">{$rec.CC}</td>
		<td class="colnum ">{$rec.FECHA_IMP}</td>
		<td class="colnum ">{$rec.FECHA_D_CONT}</td>
		<td class="colnum ">{$rec.FECHA_VALIDEZ}</td>
		<td class="colnum ">{$rec.AUT_SRI}</td>
		<!--<td></td><td></td><td></td><td></td><td></td><td></td><td></td>-->
		<!--<td></td><td></td><td></td><td></td><td></td>--><td></td><td></td>
		<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		<td></td><td></td>
                <td class="colnum " style="text-align:right;">25%</td>
                <td class="colnum " style="text-align:right;">{$rec.RET_25|number_format:2}</td>
                <td class="colnum " style="text-align:right;">{$rec.CRT_25}</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        {/if}
        
	
    
{/report_detail}

{report_footer}
    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td>
    <tr>
        <!--<td colspan=13>&nbsp</td>-->
        <td colspan=13 class="colnum"><strong>TOTALES:</strong></td>
        <td class="colnum headerrow">{$sum.BASE_12|number_format:2}</td>
	<td class="colnum headerrow">{$sum.BASE_0|number_format:2}</td>
	<td class="colnum headerrow">{$sum.IVA|number_format:2}</td>
	<td class="colnum headerrow">{$sum.TOTAL_COMPRA|number_format:2}</td>
	<td class="colnum headerrow">{$sum.montoIvaBienes|number_format:2}</td>
	<td>&nbsp</td>
	<td class="colnum headerrow">{$sum.valorRetBienes|number_format:2}</td>
	<td class="colnum headerrow">{$sum.montoIvaServicios|number_format:2}</td>
	<td>&nbsp</td>
	<td class="colnum headerrow">{$sum.valorRetServicios|number_format:2}</td>
        <td>&nbsp</td>
	<td class="colnum headerrow">{$sum.RET_1+$sum.RET_11+$sum.RET_12+$sum.RET_2+$sum.RET_5+$sum.RET_8+$sum.RET_25|number_format:2}</td>
	
        <td class="colnum " style="text-align:right;"></td>
	{assign var=retGen value=$sum.valorRetBienes+$sum.valorRetServicios+$sum.RET_1+$sum.RET_11+$sum.RET_12+$sum.RET_2+$sum.RET_5+$sum.RET_8+$sum.RET_25}
	{assign var=totGen value=$sum.TOTAL_COMPRA-$retGen}
	<td class="colnum headerrow">{$totGen|number_format:2}</td>
        <td></td>
	<td></td>
	<td></td>
    </tr>
    </table>
{/report_footer}

{/report}
</body>
