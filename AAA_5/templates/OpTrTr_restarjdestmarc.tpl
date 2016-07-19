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
{report recordset=$agData record=rec	groups="txp_nombbuque, txt_Consignatario, txt_Destino, txp_productor" resort=true}

{report_header}
<div id="container" >
  <div class="tableContainer">
	<table  cellspacing="0" >
	  <br>
	  <caption> </caption>
	{set var=$rowcls value='rowpar'}
{/report_header}

{report_header group="txp_nombbuque"}
	  <thead>
		<tr ><td class="headerrow" colspan={$agNumCols}  style="padding-left: 25px; text-align: 'left' !important ">
              REPORTE DE MARCAS  CONSIGNATARIO Y DESTINO <br>
			  SEMANA: {$pSem} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;VAPOR: {$rec.txp_nombbuque|upper}<br>
			  FECHA: {$smarty.now|date_format:"  %b/%d/%y %H:%M:%S"}
		</td></tr>
		<tr><td class="headerrow" ></td>
		  <td class="headerrow" style="width:140px;"></td>
		  {foreach key=key item=col from=$agClien}
				<td class="colhead headerrow"  colspan={$col.long}>{$col.nomb|upper}</td>
		  {/foreach}
		  <td colspan=1 class="colhead headerrow" ></td>
		</tr>
		<tr><td class="headerrow"  ></td>
		  <td class="headerrow" style="width:100px;"></td>
		  {foreach key=key item=col from=$agProduc}
				<td class="colhead headerrow"  colspan={$col.long}>{$col.nomb|upper}</td>
		  {/foreach}
		  <td colspan=1 class="colhead headerrow" ></td>
		</tr>      
		<tr><td class="headerrow" ></td>
		  <td class="headerrow" style="width:100px;"></td>
		  {foreach key=key item=col from=$agMarcas}
				<td class="colhead headerrow"  colspan={$col.long}>{$col.nomb|upper}</td>
		  {/foreach}
		  <td colspan=1 class="colhead headerrow" ></td>
		</tr>      
		<tr>
		  <td class="headerrow"  >CODIGOS </td>
		  <td class="headerrow" style="width:140px;" >PRODUCTOR </td>
			{foreach key=key item=col from=$agEmpaques}
			  <td colspan=1 class="colhead headerrow coldat" >{$col.nomb|upper}</td>
		  {/foreach}
		  <td colspan=1 class="colhead headerrow" >TOTAL</td>
		</tr>
	  </thead>
	  <tfoot>
		  <tr> 
			<td colspan={$agNumCols}></td>
		  </tr>
	  </tfoot>
{/report_header}
{report_header group="txt_Consignatario"}
		<tr >
			  <td colspan={$agNumCols}  class=" colhead headerrow grouphead" style=" line-height: 3; vertical-align: bottom !important; text-align: 'center'">
			  CONSIGNATARIO: {$rec.txt_Consignatario|upper}
		</td></tr>
{/report_header}
{report_header group="txt_Destino"}
		<tr ><td colspan={$agNumCols}  class="colhead headerrow grouphead" style="text-align: 'center'">
			  DESTINO: {$rec.txt_Destino|upper}
		</td></tr>
{/report_header}
	  <tbody>
{report_detail}
		<tr class="{$rowcls}">
		<td class="colnom" style="width:300px;" >{$rec.vce_codigosemp|upper}</td>
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td class="colnom" style="width:140px; overflow:hidden" >{$rec.txp_productor|upper}</td>
			{foreach key=key item=row from=$agEmpaques}
			  {assign var="col" value=$row.id}
			  <td class="coldat colnum" >{if  $rec.$col eq 0}{else}{$rec.$col|number_format:0}{/if}</td>
			{/foreach}
			<td class="coldat colnum col120">{$rec.tmp_SumCantidad|number_format:0}</td>
	    </tr>
{/report_detail}
{report_footer group="txt_Destino"}
		<tr ><td class="colnom"  ></td>
		  	<td style="font-weight:bold !important">SUMA  {$rec.txt_Destino|upper}</td>
			{foreach key=key item=row from=$agEmpaques}
			{assign var="col" value=$row.id}
				<td class="coldat colnum" >{if  $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			{/foreach}
			<td class="coldat colnum col120">{$sum.tmp_SumCantidad|number_format:0}</td>
		</tr>
{/report_footer}
{report_footer group="txt_Consignatario"}
		<tr ><td class="colnom"  ></td>
			<td style="font-weight:bold !important">SUMA  {$rec.txt_Consignatario|upper}</td>
			{foreach key=key item=row from=$agEmpaques}
			{assign var="col" value=$row.id}
              <td class="coldat colnum" >{if  $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			{/foreach}
			<td class="coldat colnum col120">{$sum.tmp_SumCantidad|number_format:0}</td>
		</tr>
{/report_footer}
{report_footer group="txp_nombbuque"}
		<tr ><td class="colnom"  ></td>
			<td style="font-weight:bold !important">SUMA  {$rec.txp_nombbuque|upper}</td>
			{foreach key=key item=row from=$agEmpaques}
			{assign var="col" value=$row.id}
              <td class="coldat colnum" >{if  $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			{/foreach}
			<td class="coldat colnum col120">{$sum.tmp_SumCantidad|number_format:0}</td>
		</tr>
{/report_footer}
		<tr ><td class="colnom"  ></td>
			<td style="font-weight:bold !important">TOTAL {$rec.txp_NombVapor|upper}</td>
			{foreach key=key item=row from=$agEmpaques}
			{assign var="col" value=$row.id}
				  <td class="coldat colnum " style="font-weight:bold !important"></td>
			  {/foreach}
			<td class="colnum col120"></td>
		</tr>
	  </tbody>
	</table>  
{/report}
  </div>
</div> <!-- end container -->
</body>



