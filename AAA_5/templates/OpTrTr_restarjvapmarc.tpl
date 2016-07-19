<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Fausto Astudillo" />
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
<!--   
  <link rel="stylesheet" type="text/css" media="print" href="../css/general_print.css" title="CSS parea impresion" />
  <link rel="stylesheet" type="text/css" media="print" href="../css/AAA_tablas_print.css" title="CSS parea impresion" />
-->
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="txt_vapor, txt_producto, txt_zona, txt_productor" resort=true}

{report_header}
<div id="container" >
  <div class="tableContainer">
	<table  cellspacing="0" >
	  <br>
	  <caption> </caption>
	{set var=$rowcls value='rowpar'}
{/report_header}

{report_header group="txt_vapor"}
	  <thead>
		<tr >
		  <td style="width:160px;"/>
		  <td style="width:100px;"></td>
		  {foreach key=key item=col from=$agCabeGru}
				<td width="80px" class="coldat"></td>
		  {/foreach}
		  <td class="colhead headerrow" ></td>
		</tr>
		<tr ><td class="headerrow" colspan={$agNumCols}  style="text-align: 'center'">
			  SEMANA: {$pSem} &nbsp;  &nbsp; &nbsp; &nbsp; VAPOR: {$rec.txt_vapor|upper}<br>
			  FECHA: {$agFecha} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{$sgCliente|upper}
		</td></tr>
		<tr>
		  <td class="headerrow" style="width:160px;"/>
		  <td class="headerrow" style="width:100px;"></td>
		  {foreach key=key item=col from=$agCabeGru}
				<td class="colhead headerrow"  colspan={$col.long}>{$key|upper}</td>
		  {/foreach}
		  <td class="colhead headerrow" >TOTAL</td>
		</tr>
		<tr>
		  <td class="headerrow" style="width:160px;">CODIGOS</td>
		  <td class="headerrow" style="width:180px;" >PRODUCTOR </td>
			{foreach key=key item=col from=$agNombres}
			  <td class="colhead headerrow coldat" >{$col|upper}</td>
		  {/foreach}
		  <td class="colhead headerrow" ></td>
		</tr>
	  </thead>
	  <tfoot>
		  <tr> 
			<td colspan={$agNumCols}></td>
		  </tr>
	  </tfoot>
{/report_header}
{report_header group="txt_producto"}
		<tr ><td colspan={$agNumCols}  class="colhead headerrow grouphead" style="text-align: 'center'">
			  {$rec.txt_producto|upper}
		</td></tr>
{/report_header}
{report_header group="txt_zona"}
		<tr ><td colspan={$agNumCols}  class="colhead headerrow grouphead" style="text-align: 'center'">
			  {$rec.txt_zona|upper}
		</td></tr>
{/report_header}
	  <tbody>
{report_detail}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if} 
		  <td class=" " style="width: 180px;" >{$rec.txt_codigos|upper}</td>
		  <td class="colnom" style="width:180px; " >{$rec.txt_productor|upper}</td>
			{foreach key=key item=col from=$agNombCol}
				<td class="coldat colnum" >{if  $rec.$col eq 0}{else}{$rec.$col|number_format:0}{/if}</td>
			{/foreach}
			<td class="coldat colnum ">{$rec.sumCant|number_format:0}</td>
	    </tr>
{/report_detail}

{report_footer group="txt_producto"}
		<tr ><td>&nbsp;</td>
			<td style="font-weight:bold !important">SUMA {$rec.txt_producto|upper}</td>
			  {foreach key=key item=col from=$agNombCol}
				  <td class="coldat colnum " style="font-weight:bold !important" >{if $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			  {/foreach}
			<td class="coldat colnum ">{$sum.sumCant|number_format:0}</td>
		</tr>
{/report_footer}

{report_footer group="txt_zona"}
		<tr ><td>&nbsp;</td>
			<td style="font-weight:bold !important">SUMA ZONA {$rec.txt_zona|upper}</td>
			  {foreach key=key item=col from=$agNombCol}
				  <td class="coldat colnum " style="font-weight:bold !important" >{if $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			  {/foreach}
			<td class="coldat colnum ">{$sum.sumCant|number_format:0}</td>
		</tr>
{/report_footer}
{report_footer group="txt_vapor"}
		<tr ><td>&nbsp;</td>
			<td style="font-weight:bold !important">SUMA VAPOR {$rec.txt_vapor|upper}</td>
			  {foreach key=key item=col from=$agNombCol}
				  <td class="coldat colnum " style="font-weight:bold !important" >{if $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			  {/foreach}
			<td class="colnum ">{$sum.sumCant|number_format:0}</td>
		</tr>
{/report_footer}
		<tr ><td>&nbsp;</td>
			<td style="font-weight:bold !important">TOTAL {$rec.txt_vapor|upper}</td>
			  {foreach key=key item=col from=$agNombCol}
				  <td class="coldat colnum " style="font-weight:bold !important">{if $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			  {/foreach}
			<td class="colnum ">{$sum.sumCant|number_format:0}</td>
		</tr>
	  </tbody>
	</table>  
{/report}
  </div>
</div> <!-- end container -->
</body>



