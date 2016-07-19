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
{report recordset=$agData record=rec	groups="col1, col2, col3, col4, col5, col6, col7" resort=true}

{report_header}
<div id="container" >
  <div class="tableContainer" align="center" style="margin:0px">
	{set var=$rowcls value='rowpar'}
	<table align="center" style="border:2px outset #d8d8d8; padding:1; cell-spacing:0; border-collapse:collapse;">
	<tbody style="height:auto !important">
	  <tr ><td colspan=2 class="sinlineas" style="font-weight:300; white-space: nowrap">SEMANA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="sinlineas2">{$pSem} </span></td>
		  <td colspan=2 class="sinlineas" style="font-weight:300; white-space: nowrap" >VAPOR:&nbsp;&nbsp;&nbsp<span class="sinlineas2">{$rec.col3|upper} &nbsp; &nbsp;</span></td></tr>
	</tbody>
	</table> 
	<table  cellspacing="0" align:"center"  style="border:2px outset #d8d8d8; padding:1; cell-spacing:0; border-collapse:collapse;">
	  <caption> RESUMEN GENERAL DE EMBARQUE
	  </caption>
	  <tbody style="height:595px !important">
{/report_header}
{report_header group="col1"}
	  <tr ><td class="grouphead"  colspan=4 class="{$rowcls}" style="text-align: center; line-height:2.5" >{$rec.col1|upper}</td></tr >
{/report_header}
{report_header group="col2"}
	  <tr ><td class="grouphead"  colspan=4 class="{$rowcls}" style="text-align: center line-height:2"" >{$rec.col2|upper}</td></tr >
{/report_header}
{report_header group="col3"}
	  <tr ><td class="grouphead"  colspan=4 class="{$rowcls}" style="text-align: center line-height:1.5"" >{$rec.col3|upper}</td></tr >
{/report_header}
{report_header group="col4"}
	  <tr ><td class="grouphead"  colspan=4 class="{$rowcls}" style="text-align: center line-height:1"" >{$rec.col4|upper}</td></tr >
{/report_header}

{report_detail}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td class="colnom " style="width:120px; " >{$rec.col6|upper}</td>
		  <td class="colnom " style="width:120px; " >{$rec.col7|upper}</td>
		  <td class="colnom " style="width:120px; " >{$rec.txt_Contenedores|upper}</td>
		  <td class="colnom colnum" style="width:120px; " >{$rec.txt_SumaCant|number_format:0}</td>
	    </tr>
{/report_detail}
{report_footer group="col1"}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td colspan=2 class="colnom " style="width:120px;" >SUMA {$rec.col1|upper}</td>
		  <td class="colnom " style="width:120px; " ></td>
		  <td class="colnom colnum" style="width:120px; " >{$sum.txt_SumaCant}</td>
	    </tr>
{/report_footer}	  
{report_footer group="col2"}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td colspan=2 class="colnom " style="width:120px; " >SUMA {$rec.col2|upper}</td>
		  <td class="colnom " style="width:120px; " ></td>
		  <td class="colnom colnum" style="width:120px; " >{$sum.txt_SumaCant}</td>
	    </tr>
{/report_footer}	  
{report_footer group="col3"}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td colspan=2 class="colnom " style="width:120px; " >SUMA {$rec.col3|upper}</td>
		  <td class="colnom " style="width:120px; " ></td>
		  <td class="colnom colnum" style="width:120px; " >{$sum.txt_SumaCant}</td>
	    </tr>
{/report_footer}	  
{report_footer group="col4"}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td class="colnom " style="width:120px; " >SUMA {$rec.col4|upper}</td>
		  <td class="colnom " style="width:120px; " ></td>
		  <td class="colnom " style="width:120px; " ></td>
		  <td class="colnom colnum" style="width:120px; " >{$sum.txt_SumaCant}</td>
	    </tr>
{/report_footer}
{report_footer}
	</table> 
{/report_footer}
{/report}
  </div>
</div> <!-- end container -->
</body>



