<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para el CUADRO DE EMBARQUE (formato de Aplesa) -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <meta name="author" content="Erika Suarez" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>CUADRO DE EMBARQUE</title>
  {assign var=nGrp value=1}
</head> 

<body id="top" style="font-family:'Arial'">
{assign var=tExp value=0}
{assign var=tLoc value=0}


{report recordset=$agData record=rec groups="venta" resort=false}

{report_header}
   <table border=1 cellspacing=0 >
    <thead>
    </thead>
    <tfoot>      
    </tfoot>
    
  <tbody>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:12pt;">
      <td class="headerrow" colspan=13>{$subtitulo} {$rec.EGNombre}</td>   
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:8pt;">
      <td class="headerrow" colspan=13>{$subtitulo2}</td>   
    </tr>
{/report_header}

{report_header group="venta"}
    {assign var=nGrp value=$rec.venta}
    <tr style="font-weight:bold; text-align: left; vertical-align:middle; font-size:12pt; background-color: #BDBDBD;">
      <td class="headerrow" colspan=13>{$rec.venta}</td>
      
    </tr>
    <tr style="font-weight:bold; text-align: center; vertical-align:middle; font-size:10pt;">
      <td class="headerrow" style="background-color: #FE9A2E;">WK</td>
      <td class="headerrow" style="background-color: #FE9A2E;">ID EMBARQUE</td>
      <td class="headerrow" style="background-color: #FE9A2E;">CLIENTE</td>
      <td class="headerrow" style="background-color: #FE9A2E;">VAPOR</td>
      <td class="headerrow" style="background-color: #FE9A2E;">TIPO</td>
      <td class="headerrow" style="background-color: #FE9A2E;">CAJAS</td>
      <td class="headerrow" style="background-color: #FE9A2E;">DESTINO</td>
      <td class="headerrow" style="background-color: #FE9A2E;">FACTURACION</td>
      <td class="headerrow" style="background-color: #FE9A2E;">PRECIO FOB</td>
      <td class="headerrow" style="background-color: #FE9A2E;">PRECIO CIF</td>
      <td class="headerrow" style="background-color: #FE9A2E;">COSTO FOB/CIF</td>
      <td class="headerrow" style="background-color: #FE9A2E;">FRUTA</td>
      <td class="headerrow" style="background-color: #FE9A2E;">TOTAL</td>
    </tr>
    
{/report_header}


{report_detail}
  <tr style="vertical-align:middle; font-size:10pt;">
	<td class="colnum"  nowrap style="width:2cm; text-align:center">{$rec.tac_Semana}</td>
	<td class="colnum"  nowrap style="width:2cm; text-align:center">{$rec.emb_RefOperativa}</td>
	<td class="coldata" nowrap style="width:2cm;">{$rec.cliente}</td>
        <td class="coldata" nowrap style="width:4cm;">{$rec.buq_Descripcion}</td>
        <td class="coldata" nowrap style="width:3cm;">{$rec.paletizado}</td>
	<td class="colnum " nowrap style="width:3cm;">{$rec.Embarcadas|number_format:2:".":","}</td>
        <td class="coldata" nowrap style="width:4cm;">{$rec.pai_Descripcion}</td>
	<td class="coldata" nowrap style="width:3cm;">{$rec.FACTURA_EMB}</td>
	<td class="colnum " nowrap style="width:3cm;">{$rec.PRECIO_FOB|number_format:2:".":","}</td>
	<td class="colnum " nowrap style="width:3cm;">{$rec.PRECIO_CIF|number_format:2:".":","}</td>
	<td class="colnum " nowrap style="width:3cm;">{$rec.COSTO_FOB|number_format:2:".":","}</td>
	<td class="colnum " nowrap style="width:3cm;">{$rec.PRECIO_FRUTA|number_format:2:".":","}</td>
	<td class="colnum " nowrap style="width:3cm;">{$rec.TOTAL_EMBARQUE|number_format:2:".":","}</td>
	
    </tr>
{/report_detail}

{report_footer group="venta"}
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="5">TOTAL {$rec.venta}:</td>
	<td>{$sum.Embarcadas|number_format:2:".":","}<br><br></td>
	<td colspan="5">PROMEDIO VENTA {$rec.venta} ==></td>
	<td >{$sum.TOTAL_EMBARQUE/$sum.Embarcadas|number_format:2:".":","}</td>
	<td >{$sum.TOTAL_EMBARQUE|number_format:2:".":","}</td>
    
	
	{if $rec.venta eq 'VENTAS AL EXTERIOR'}
	  {assign var=tExp value=$sum.Embarcadas}
	{/if}
	{if $rec.venta eq 'VENTAS LOCALES'}
	  {assign var=tLoc value=$sum.Embarcadas}
	{/if}
	
    </tr>
    
{/report_footer}

{report_footer}
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #BDBDBD;">
	<td colspan="5">TOTAL GENERAL:</td>
	<td>{$sum.Embarcadas|number_format:2:".":","}</td>
	<td colspan="5">PROMEDIO VENTA TOTAL ==></td>
	<td >{$sum.TOTAL_EMBARQUE/$sum.Embarcadas|number_format:2:".":","}</td>
	<td >{$sum.TOTAL_EMBARQUE|number_format:2:".":","}</td>
    </tr>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="13"></td>
    </tr>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="13"></td>
    </tr>
    <tr style="font-weight:bold;vertical-align:middle; text-align:right; ">
	<td colspan="13"></td>
    </tr>
  </tbody>
  </table>
   
   
   <TABLE>
    <TR>
      <TD>
	    <!--  TABLA DE RESUMEN DE LO EMBARCADO -->
	    <table>
	     <tr style="font-weight:bold;vertical-align:middle; text-align:right; background-color: #FE9A2E;">
		 <td colspan="2">RESUMEN DE EMBARQUE WK#{$rec.tac_Semana}</td>
	    </tr>
	    <tr style="vertical-align:middle; text-align:right; ">
		 <td style="width:7cm;">EXPORTACION</td>
		 <td style="width:3cm;">{if $tExp eq 0} 0.00 {else} {$tExp|number_format:2:".":","} {/if}</td>
	    </tr>
	    <tr style="vertical-align:middle; text-align:right; ">
		 <td >VENTA LOCAL</td>
		 <td>{if $tLoc eq 0} 0.00 {else} {$tLoc|number_format:2:".":","} {/if}</td>
	    </tr>
	    <tr style="font-weight:bold; vertical-align:middle; text-align:right; ">
		 <td >TOTAL EXPORTACION Y VENTA LOCAL</td>
		 <td>{if $tLoc+$tExp eq 0} 0.00 {else} {$tLoc+$tExp|number_format:2:".":","} {/if}</td>
	    </tr>
	    <tr style="vertical-align:middle; text-align:right; ">
		 <td >TOTAL LIQUIDADO A PRODUCTORES</td>
		 <td>{if $LiqProduc eq 0} 0.00 {else} {$LiqProduc|number_format:2:".":","} {/if}</td>
	    </tr>
	    <tr style="font-weight:bold; vertical-align:middle; text-align:right; ">
		 <td >DIFERENCIA</td>
		 <td>{if $tLoc+$tExp-$LiqProduc eq 0} 0.00 {else} {$tLoc+$tExp-$LiqProduc|number_format:2:".":","} {/if}</td>
	    </tr>
	  </table>
   </TD>
   <TD style="width:50%"> </TD>
   <TD>
   
	  <!--  TABLA DE RESUMEN DE LA RENTABILIDAD -->
	   <table>
	       <tr style="vertical-align:middle; text-align:right; ">
		    <td style="width:7cm;">PROMEDIO DE VENTA TOTAL</td>
		    {assign var=promEmb value=$sum.TOTAL_EMBARQUE/$sum.Embarcadas}
		    <td style="width:3cm;">{$promEmb|number_format:2:".":","}</td>
	       </tr>
	       <tr style="vertical-align:middle; text-align:right; ">
		    <td >PRECIO PROMEDIO DEL TOTAL GENERAL</td>
		    <td>{$PromGeneral|number_format:2:".":","}</td>
	       </tr>
	       <tr style="font-weight:bold; vertical-align:middle; text-align:right; ">
		    <td >RENTABILIDAD POR CAJA</td>
		    {assign var=rentabilidad value=$promEmb-$PromGeneral}
		    <td>{$rentabilidad|number_format:2:".":","}</td>
	       </tr>
	       <tr style="vertical-align:middle; text-align:right; ">
		    <td >EXPORTACION + VENTA LOCAL</td>
		    <td>{$sum.Embarcadas|number_format:2:".":","}</td>
	       </tr>
	       <tr style="font-weight:bold; vertical-align:middle; text-align:right; ">
		    <td >UTILIDAD BRUTA WK#{$rec.tac_Semana}</td>
		    <td>{$sum.Embarcadas*$rentabilidad|number_format:2:".":","}</td>
	       </tr>
	    </table>
	  
    </TD>
   </TR>
   <TR>
      <TD colspan="3">{$smarty.session.g_user} {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} </TD>
   </TR>
    </TABLE>
{/report_footer}

{/report}
</body>