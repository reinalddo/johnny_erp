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

<body id="top" align="center" style="font-family:'Arial'">
{report recordset=$agData  record=rec groups="txp_NombBuque"} 
{report_header}
<div id="container" >
  <div class="tableContainer" align="center" style="margin:0px">
	{set var=$rowcls value='rowpar'}
	<table align="center" style="border:2px outset #d8d8d8; padding:1; cell-spacing:0; border-collapse:collapse;">
	<tbody style="height:auto !important">
	  <tr ><td colspan=2 class="sinlineas" style="font-weight:300; white-space: nowrap">SEMANA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="sinlineas2">{$pSem} </span></td>
		  <td colspan=2 class="sinlineas" style="font-weight:300; white-space: nowrap" >VAPOR:&nbsp;&nbsp;&nbsp<span class="sinlineas2">{$rec.txp_NombBuque|upper} &nbsp; &nbsp;</span></td></tr>
	</tbody>
	</table> 
	<table  cellspacing="0" align:"center"  style="border:2px outset #d8d8d8; padding:1; cell-spacing:0; border-collapse:collapse;">
	  <caption> RESUMEN DE CANTIDADES POR CONTENEDOR
	  </caption>
	  <thead>
		<tr>
		  <td class="grouphead" style="width:100px;">CONTENEDOR</td>
		  <td class="grouphead" style="width:100px;">CANTIDAD DECLARADA</td>
		  <td class="grouphead" style="width:100px;">CANTIDAD EN TARJAS</td>
		  <td class="grouphead" style="width:100px;">DIFERENCIA</td>
		</tr>
	  {set var=$rowcls value='rowimpar'}
	  </thead>
	  <tbody>
{/report_header}
{report_header group="txp_NombBuque"}
	  <tr  >
		<td class="grouphead"  colspan=4 class="{$rowcls}" style="text-align: center" >VAPOR: {$rec.txp_NombBuque|upper}
		</td>
	  </tr >
{/report_header}
{report_detail}
		<tr >
		  <td class="colnom" style="width:200px; " >{$rec.txp_Contenedor|upper}</td>
		  <td class="coldat colnum col120">{$rec.cnt_CantDeclarada|number_format:0}</td>
		  <td class="coldat colnum col120">{$rec.tmp_Cantidad|number_format:0}</td>
		  <td class="coldat colnum col120">{$rec.tmp_CantDeclarada - $rec.tmp_Cantidad|number_format:0}</td>
	    </tr>
{/report_detail}
{report_footer group="txp_NombBuque"}
		<tr >
		  <td class="colnom" style="width:200px; text-align:right " >SUMA {$rec.txp_NombBuque}</td>
		  <td class="coldat colnum col120">{$sum.cnt_CantDeclarada|number_format:0}</td>
		  <td class="coldat colnum col120">{$sum.tmp_Cantidad|number_format:0}</td>
		  <td class="coldat colnum col120">{$sum.tmp_CantDeclarada - $sum.tmp_Cantidad|number_format:0}</td>
		</tr>
{/report_footer}	  

{report_footer}
	</tbody>
	</table> 
{/report_footer}

{/report}
  </div>
</div> <!-- end container -->
</body>



