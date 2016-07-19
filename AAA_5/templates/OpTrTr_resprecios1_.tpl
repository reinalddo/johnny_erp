<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Resumen de lista de precios, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Fausto Astudillo" />
  <style media="screen, print">{$style_pr}</style>
  <style media="screen">{$style_sc}</style>
<!--
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
  
  <link rel="stylesheet" type="text/css" media="print" href="../css/general_print.css" title="CSS parea impresion" />
  <link rel="stylesheet" type="text/css" media="print" href="../css/AAA_tablas_print.css" title="CSS parea impresion" />
-->
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="txp_NombBuque, txp_Producto, txp_Marca, txp_PrecUni" resort=true}

{report_header}
<div id="container" >fasdfasdfasdf
  <div class="tableContainer">
	{set var=$rowcls value='rowpar'}
{/report_header}

{report_header group="txp_Contenedor"}
	{assign var="cntID" value=$rec.txp_Contenedor}
<div>
	<table style="border:2px outset #d8d8d8; padding:1; cell-spacing:0; border-collapse:collapse width:100%">
	<tbody style="height:auto !important">
	  <tr ><td colspan=2 class="sinlineas" style="font-weight:300; white-space: nowrap">SEMANA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="sinlineas2">{$pSem} </span></td>
		  <td colspan=2 class="sinlineas" style="font-weight:300; white-space: nowrap" >VAPOR:&nbsp;&nbsp;&nbsp<span class="sinlineas2">{$rec.txp_NombBuque|upper} &nbsp; &nbsp;</span></td></tr>
	  	  <tr><td colspan=2 class="sinlineas" style="font-weight:300">CONSIGNATARIO.:<span colspan=3 class="sinlineas2">{$agConten.$cntID.txt_Consignatario|upper}</span></td></tr>
	</tbody>
	</table> 
	<table  cellspacing="0" >
	  <caption>
	  </caption>
	  <thead>
		<tr>
		  <td class="headerrow" style="width:100px;">DESCRIPCION</td>
		  <td class="headerrow" style="width:100px;">CANTIDAD</td>
		  <td class="headerrow" style="width:100px;">PREC. UNIT</td>
		  <td class="headerrow" style="width:100px;">VALOR</td>
		</tr>
	  </thead>
{/report_header}
	  <tbody>
{report_detail}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td class="colnom" style="width:200px; " >{$rec.caj_CajDescripcion|upper}</td>
		  <td class="coldat colnum col120">{$rec.tmp_Cantidad|number_format:0}</td>
		  <td class="coldat colnum col120">{$rec.txp_PrecUnitario|number_format:4}</td>
		  <td class="coldat colnum col120">{$rec.tmp_Valor|number_format:2}</td>
	    </tr>
{/report_detail}
{report_footer group="txp_Marca"}
		<tr >
		  <td class="colnom" style="width:200px; " ></td>
		  <td class="coldat colnum col120">{$sum.tmp_Cantidad|number_format:0}</td>
		  <td class="coldat colnum col120"></td>
		  <td class="coldat colnum col120">{$sum.tmp_Valor|number_format:2}</td>
		</tr>
{/report_footer}	  
{report_footer group="txp_Producto"}
		<tr >
		  <td class="colnom" style="width:200px; " ></td>
		  <td class="coldat colnum col120">{$sum.tmp_Cantidad|number_format:0}</td>
		  <td class="coldat colnum col120"></td>
		  <td class="coldat colnum col120">{$sum.tmp_Valor|number_format:2}</td>
		</tr>
{/report_footer}	  

{report_footer}
	</table> 
{/report_footer}
{/report}
  </div>
  </div>
</div> <!-- end container -->
</body>



