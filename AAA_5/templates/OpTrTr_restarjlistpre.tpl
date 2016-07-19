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
{report recordset=$agData record=rec	groups="txp_nombbuque, tmp_zona, txp_productor" resort=true}

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
		<tr >
		  <td style="width:100px;"></td>
		  {foreach key=key item=col from=$agCabeGru}
				<td width="40pt" class="coldat"></td>
		  {/foreach}
		  <td class="colhead headerrow" ></td>
		</tr>
		<tr ><td class="headerrow" colspan={$agNumCols}  style="text-align: 'center'">
			  SEMANA: {$pSem} &nbsp;  &nbsp; &nbsp; &nbsp; VAPOR: {$rec.txp_nombbuque|upper}<br>
			  FECHA: {$asHoy} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{$sgCliente|upper}
		</td></tr>
		<tr>
		  <td class="headerrow" style="width:100px;"></td>
		  {foreach key=key item=col from=$agCabeGru}
				<td class="colhead headerrow"  colspan={$col.long}>{$key|upper}</td>
		  {/foreach}
		  <td colspan=2 class="colhead headerrow" >TOTAL</td>
		</tr>
		<tr>
		  <td class="headerrow" style="width:100px;" >PRODUCTOR </td>
			{foreach key=key item=col from=$agNombres}
			  <td colspan=2 class="colhead headerrow coldat" >{$col|upper}</td>
		  {/foreach}
		  <td colspan=2 class="colhead headerrow" ></td>
		</tr>
		<tr>
		  <td class="headerrow" style="width:100px;" >&nbsp;</td>
			{foreach key=key item=col from=$agNombres}
			  <td  class="colhead headerrow coldat" >CANT</td>
              <td  class="colhead headerrow coldat" >P.UNIT.</td>
            {/foreach}
		  <td  class="colhead headerrow coldat" >CANT</td>
          <td  class="colhead headerrow coldat" >VALOR USD.</td>
		</tr>

	  </thead>
	  <tfoot>
		  <tr> 
			<td colspan={$agNumCols}></td>
		  </tr>
	  </tfoot>
{/report_header}
{report_header group="tmp_zona"}
		<tr ><td colspan={$agNumCols}  class="colhead headerrow grouphead" style="text-align: 'center'">
			  ZONA: {$rec.txt_zona|upper}
		</td></tr>
{/report_header}
	  <tbody>
{report_detail}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td class="colnom" style="width:200px; " >{$rec.txp_productor|upper}</td>
			{foreach key=key item=col from=$agNombCol}
                {assign var="colCan" value="cnt_"}
                {assign var="valCan" value="pun_"}
                {concat var="colCan" value=$col}
                {concat var="valCan" value=$col}
				<td class="coldat colnum" style="width:40 !important">{if  $rec.$colCan eq 0}{else}{$rec.$colCan|number_format:0}{/if}</td>
                <td class="coldat colnum" style="width:40 !important ; border-right-width:2px !important">
				  {if  $rec.$valCan eq 0}
					{if  $rec.$colCan eq 0}{else}<span style="background-color:yellow; color:red">* * * * </span>{/if}
				  {else}{$rec.$valCan|number_format:4}{/if}</td>
			{/foreach}
			<td class="coldat colnum col120">{$rec.tmp_SumCantidad|number_format:0}</td>
            <td class="coldat colnum col120" style="border-right-width:2px !important">{$rec.tmp_SumValor|number_format:2}</td>
	    </tr>
{/report_detail}
{report_footer group="tmp_zona"}
		<tr >
			<td style="font-weight:bold !important">SUMA ZONA {$rec.txt_zona|upper}</td>
			{foreach key=key item=col from=$agNombCol}
                {assign var="colCan" value="cnt_"}
                {assign var="valCan" value="pun_"}
                {concat var="colCan" value=$col}
                {concat var="valCan" value=$col}
				<td class="coldat colnum" style="font-weight:bold !important">{if  $sum.$colCan eq 0}{else}{$sum.$colCan|number_format:0}{/if}</td>
                <td class="coldat colnum" style="font-weight:bold !important"></td>
			{/foreach}
			<td class="coldat colnum col120">{$sum.tmp_SumCantidad|number_format:0}</td>
            <td class="coldat colnum col120">{$sum.tmp_SumValor|number_format:2}</td>
		</tr>
{/report_footer}
{report_footer group="txp_nombbuque"}
		<tr >
			<td style="font-weight:bold !important">SUMA VAPOR {$rec.txp_nombbuque|upper}</td>
			{foreach key=key item=col from=$agNombCol}
                {assign var="colCan" value="cnt_"}
                {assign var="valCan" value="pun_"}
                {concat var="colCan" value=$col}
                {concat var="valCan" value=$col}
				<td class="coldat colnum" style="font-weight:bold !important">{if  $sum.$colCan eq 0}{else}{$sum.$colCan|number_format:0}{/if}</td>
                <td class="coldat colnum" style="font-weight:bold !important"></td>
			{/foreach}
			<td class="coldat colnum col120">{$sum.tmp_SumCantidad|number_format:0}</td>
            <td class="coldat colnum col120">{$sum.tmp_SumValor|number_format:2}</td>
		</tr>
{/report_footer}
		<tr >
			<td style="font-weight:bold !important">TOTAL {$rec.txp_NombVapor|upper}</td>
			  {foreach key=key item=col from=$agNombCol}
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



