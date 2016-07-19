<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Fausto Astudillo" />
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="txt_vapor, txt_producto, txt_nombre" resort=true}

{report_header}
<div id="container"  style="height: 650px !important">
  <div class="tableContainer">
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <caption> RESUMEN DIARIO <br>POR PRODUCTOR Y MARCA </caption>
	{set var=$rowcls value='rowpar'}
{/report_header}

{report_header group="txt_vapor"}
	  <thead>
		<tr >
		  <td style="width:80px;"/>
		  <td style="width:100px;"></td>
		  {foreach key=key item=col from=$agCabeGru}
				<td width="80px" class="coldat"></td>
		  {/foreach}
		  <td class="colhead headerrow" ></td>
		</tr>
		<tr ><td colspan={$agNumCols}  class="colhead headerrow grouphead"  style="font-weight:bold !important; text-align:'center'; height:40px">
			  SEMANA: {$pSem} &nbsp;  &nbsp; &nbsp; &nbsp; VAPOR: {$rec.txt_vapor|upper}
		</td></tr>
		<tr>
		  <td class="headerrow" style="width:80px;"/>
		  <td class="headerrow" style="width:100px;"></td>
		  {foreach key=key item=col from=$agCabeGru}
				<td class="colhead headerrow"  colspan={$col.long}>{$key|upper}</td>
		  {/foreach}
		  <td class="colhead headerrow" >TOTAL</td>
		</tr>
		<tr>
		  <td class="headerrow" style="width:80px;">CODIGOS</td>
		  <td class="headerrow" style="width:100px;" >PRODUCTOR </td>
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
		<tr ><td colspan={$agNumCols}  class="colhead headerrow grouphead" style="font-weight:bold !important; text-align:'center'; height:40px">
			  
			  {$rec.txt_producto|upper}
		</td></tr>
{/report_header}
	  <tbody>
{report_detail}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if} 
		  <td class="colnom " style="width:80px;" >{$rec.txt_codigos|upper}</td>
		  <td class="colnom" style="width:120px; white-space: nowrap; " >{$rec.txt_nombre|upper}</td>
			{foreach key=key item=col from=$agNombCol}
				<td class="coldat colnum" >{if  $rec.$col eq 0}{else}{$rec.$col|number_format:0}{/if}</td>
			{/foreach}
			<td class="coldat colnum col120">{$rec.sumCant|number_format:0}</td>
	    </tr>
{/report_detail}

{report_footer group="txt_producto"}
		<tr ><td>&nbsp;</td>
			<td style="font-weight:bold !important">SUMA {$rec.txt_producto|upper}</td>
			  {foreach key=key item=col from=$agNombCol}
				  <td class="coldat colnum " style="font-weight:bold !important" >{if $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			  {/foreach}
			<td class="coldat colnum col120">{$sum.sumCant|number_format:0}</td>
		</tr>
{/report_footer}

{report_footer group="txt_vapor"}
		<tr ><td>&nbsp;</td>
			<td style="font-weight:bold !important">SUMA {$rec.txt_vapor|upper}</td>
			  {foreach key=key item=col from=$agNombCol}
				  <td class="coldat colnum " style="font-weight:bold !important" >{if $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			  {/foreach}
			<td class="colnum col120">{$sum.sumCant|number_format:0}</td>
		</tr>
{/report_footer}
		<tr ><td>&nbsp;</td>
			<td style="font-weight:bold !important">TOTAL {$rec.txt_vapor|upper}</td>
			  {foreach key=key item=col from=$agNombCol}
				  <td class="coldat colnum " style="font-weight:bold !important">{if $sum.$col eq 0}{else}{$sum.$col|number_format:0}{/if}</td>
			  {/foreach}
			<td class="colnum col120">{$sum.sumCant|number_format:0}</td>
		</tr>
	  </tbody>
	</table>  
{/report}
  </div>
</div> <!-- end container -->
</body>



