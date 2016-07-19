<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Cuadro de Cuentas por Pagar -->
<!--  FRUTIBONI - Quitar columnas: No Comprobante, Cod. Proveedor, No Retencion, estado, usuario/fecha Dig -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CUADRO DE LIQUIDACION</title>
  
</head> 

<body id="top" style="font-family:'Arial'">
{assign var=acum value=0}
{assign var=sal value=0}




{report recordset=$agData record=rec groups="dmarca" resort=false}

{report_header}
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:12pt;">
        <strong>{$smarty.session.g_empr}</strong><br>
        {$subtitulo}<br>
	{$subtitulo2}
    </p>
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>      
    </tfoot>
    
    <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:10pt;">
      <td class="headerrow" colspan=5></td>
      <td class="headerrow" colspan=10 style="background-color: #F78181;">DESCUENTOS(-)</td>
      <td class="headerrow" colspan=5  style="background-color: #04B45F;">CREDITOS(+)</td>
      <td class="headerrow" colspan=4  style="background-color: #FE9A2E;">LIQUIDACION</td>      
    </tr>
    <tr style="font-weight:bold; text-align:justify; vertical-align:middle; font-size:8pt;">
	<td class="headerrow">COD.</td>
	<td class="headerrow"><strong>NOMBRE</strong></td> 
    	<td class="headerrow"><strong>MARCA<strong></td>
	<td class="headerrow"><strong>CAJAS<strong></td>
	<td class="headerrow"><strong>V. UNIT.<strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>TRANS.</strong></td>
        <td class="headerrow" style="background-color: #F5A9A9;"><strong>EMPAQ.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>ADM.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>CXC.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>DIF.OFIC.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>RET. FTE.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>QLTY.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>SPI.</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>ESTIBA</strong></td>
	<td class="headerrow" style="background-color: #F5A9A9;"><strong>TOTAL</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>BONIF.</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>DEV. GAR.</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>EMPAQ.</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>PR.OFIC.</strong></td>
	<td class="headerrow" style="background-color: #A9F5A9;"><strong>TOTAL</strong></td>
	<td class="headerrow" style="background-color: #FAAC58;"><strong>Valor</strong></td>
	<td class="headerrow" style="background-color: #FAAC58;"><strong>Prom</strong></td>
	<td class="headerrow" style="background-color: #FAAC58;"><strong>ANTICIPO</strong></td>
	<td class="headerrow" style="background-color: #FAAC58;"><strong>VR A PAGAR</strong></td>
    </tr>
{/report_header}

{report_detail}
  <tr style="vertical-align:middle; font-size:10pt;">
      <td class="colnum "> {$rec.com_CodReceptor}</td>
	<td class="coldata" nowrap>{$rec.productor}</td>
	<td class="coldata" nowrap>{$rec.dmarca}</td>
        <td class="colnum ">{$rec.cajEmb|number_format:0:",":"."}</td>
        <td class="colnum ">&nbsp;{$rec.pCaj|number_format:2:",":"."}</td>
	<!-- DESCUENTOS  -->
	<td class="colnum " style="text-align:right;">{$rec.R6|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R7|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R7_G|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R9|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R14|number_format:2:",":"."}</td> 
	<td class="colnum " style="text-align:right;">{$rec.R16|number_format:2:",":"."}</td>
  	<td class="colnum " style="text-align:right;">{$rec.R18|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R20|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R21|number_format:2:",":"."}</td>
        <td class="colnum " style="text-align:right;background-color: #F5A9A9;">{$rec.TDesc|number_format:2:",":"."}</td>
	<!-- BONIFICACIONES  -->
	<td class="colnum " style="text-align:right;">{$rec.R2|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R3|number_format:2:",":"."}</td>
        <td class="colnum " style="text-align:right;">{$rec.R4|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R1|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;background-color: #A9F5A9;">{$rec.TBenf|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.RTotal|number_format:2:",":"."}</td>
	{assign var=PPromLiq  value=$rec.RTotal/$rec.cajEmb}
	<td class="colnum"  style="text-align:right;">{$PPromLiq|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right;">{$rec.R17|number_format:2:",":"."}</td>
	<td class="colnum " style="text-align:right; background-color: #FAAC58;">{$rec.RTotalFin|number_format:2:",":"."}</td>
	
	<!-- ACUMULADORES PARA TOTALES - SOLO LOS QUE SEAN DE TIPO=5 (LOS DE LA LIQUIDACION,NO DEL DETALLE DE LAS TARJAS) -->
	{if $rec.tipo == 5}
	  {assign var=tcajEmb	value=$tcajEmb+$rec.cajEmb} <!-- Cajas embarcadas-->
	  {assign var=tvcajEmb	value=$rec.cajEmb*$rec.pCaj} <!-- Valor de las cajas embarcadas-->
	  {assign var=tpcajEmb	value=$tpcajEmb+$tvcajEmb} <!-- Precio promedio cajas embarcadas-->
	  {assign var=tR6	value=$tR6+$rec.R6}
	  {assign var=tR7	value=$tR7+$rec.R7}
	  {assign var=tR7_G	value=$tR7_G+$rec.R7_G}
	  {assign var=tR9	value=$tR9+$rec.R9}
	  {assign var=tR14	value=$tR14+$rec.R14}
	  {assign var=tR16	value=$tR16+$rec.R16}
	  {assign var=tR17	value=$tR17+$rec.R17}
	  {assign var=tR18	value=$tR18+$rec.R18}
	  {assign var=tR20	value=$tR20+$rec.R20}
	  {assign var=tR21	value=$tR21+$rec.R21}
	  {assign var=tTDesc	value=$tTDesc+$rec.TDesc}
	  {assign var=tR2	value=$tR2+$rec.R2}
	  {assign var=tR3	value=$tR3+$rec.R3}
	  {assign var=tR4	value=$tR4+$rec.R4}
	  {assign var=tR1	value=$tR1+$rec.R1}
	  {assign var=tTBenf	value=$tTBenf+$rec.TBenf}
	  {assign var=tRTotal	value=$tRTotal+$rec.RTotal}
	  {assign var=tPPromLiq	value=$tPPromLiq+$PPromLiq}
	  {assign var=tvCajLiq	value=$rec.cajEmb*$PPromLiq} <!-- Precio promedio de la liquidacion X Cajas embarcadas -->
	  {assign var=tpcajLiq	value=$tpcajLiq+$tvCajLiq} <!-- Precio promedio cajas liquidadas-->
	  {assign var=tRTotalFin	value=$tRTotalFin+$rec.RTotalFin}
	{/if}
    </tr>
{/report_detail}

{report_footer}
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
      <td colspan=3">TOTALES:</td>
       <td>{$tcajEmb|number_format:0:",":"."}</td>
       {assign var=tpPcajEmb	value=$tpcajEmb/$tcajEmb}
       <td>{$tpPcajEmb|number_format:2:",":"."}</td>
       <td>{$tR6|number_format:2:",":"."}</td>
       <td>{$tR7|number_format:2:",":"."}</td>
       <td>{$tR7_G|number_format:2:",":"."}</td>
       <td>{$tR9|number_format:2:",":"."}</td>
       <td>{$tR14|number_format:2:",":"."}</td>
       <td>{$tR16|number_format:2:",":"."}</td>
       <td>{$tR18|number_format:2:",":"."}</td>
       <td>{$tR20|number_format:2:",":"."}</td>
       <td>{$tR21|number_format:2:",":"."}</td>
       <td>{$tTDesc|number_format:2:",":"."}</td>
       <td>{$tR2|number_format:2:",":"."}</td>
       <td>{$tR3|number_format:2:",":"."}</td>
       <td>{$tR4|number_format:2:",":"."}</td>
       <td>{$tR1|number_format:2:",":"."}</td>
       <td>{$tTBenf|number_format:2:",":"."}</td>
       <td>{$tRTotal|number_format:2:",":"."}</td>
      {assign var=tpPcajLiq	value=$tpcajLiq/$tcajEmb}
       <td>{$tpPcajLiq|number_format:2:",":"."}</td>
       <td>{$tR17|number_format:2:",":"."}</td>
       <td>{$tRTotalFin|number_format:2:",":"."}</td>
    
  <tr><td colspan="24" style="text-align:left"> {$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </td></tr>
  </tbody>
  </table>
{/report_footer}

{/report}
</body>