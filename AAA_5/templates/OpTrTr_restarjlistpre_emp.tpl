<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
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
{report recordset=$agData record=rec	groups="txp_nombbuque, txp_catorden, tmp_zona, txp_productor" resort=true}

{report_header}
<div id="container" >
	{set var=$rowcls value='rowpar'}
  <div id="tableContainer">
{/report_header}

{report_header group="txp_nombbuque"}
{/report_header}
{report_header group="txp_catorden"}
  <span style="page-break-before:void; page-break-after: right; page-break-inside: avoid">
	<table  cellspacing="0" >
	  <br>
	  <caption> </caption>
	  <thead>
		<tr >
		  <td style="width:100px;"></td>
		  {foreach key=key item=col from=$agCabeGru}
				<td width="80px" class="coldat"></td>
		  {/foreach}
		  <td class="colhead headerrow" ></td>
		</tr>
		<tr ><td class="headerrow" colspan={$agNumC2}  style="text-align: 'center'; height:40px">
			  SEMANA: {$pSem} &nbsp;  &nbsp; &nbsp; &nbsp; VAPOR: {$rec.txp_nombbuque|upper}<br>
			  FECHA: {$asHoy} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{$sgCliente|upper}
			</td>
			<td class="headerrow" colspan={$agNumC3}  style="text-align: 'center'"></td>
		</tr>
		<tr ><td colspan={$agNumCols}  class="colhead headerrow grouphead" style="text-align: 'center'; height:40px">
			{$agCatNom.$cat|upper}</td>
		</tr>
		<tr>
		  <td class="headerrow" style="width:100px;"></td>
		  {assign var="cat" value =$rec.txp_catorden}
		  {assign var="aTit" value=$agCabeGru.$cat}
		  {foreach key=key item=col from=$aTit}		  
		    <td class="colhead headerrow"  colspan={$col.long}>{$key|upper}</td>
		  {/foreach}
		  <td colspan=2 class="colhead headerrow" >TOTAL</td>
		</tr>
		<tr>
		  <td class="headerrow" style="width:100px;" >PRODUCTOR </td>
		  {assign var="aNom" value=$agNombres.$cat}
		  {foreach key=key item=col from=$aNom}
			  <td colspan=2 class="colhead headerrow coldat" >{$col|upper}</td>
		  {/foreach}
		  <td colspan=2 class="colhead headerrow" ></td>
		</tr>
		<tr>
		  <td class="headerrow" style="width:100px;" >&nbsp;</td>
		  {assign var="aNom" value=$agNombres.$cat}
		  {foreach key=key item=col from=$aNom}
			  <td  class="colhead headerrow coldat" >CANT</td>
              <td  class="colhead headerrow coldat" >P.UNIT.</td>
            {/foreach}
		  <td  class="colhead headerrow coldat" >CANT</td>
          <td  class="colhead headerrow coldat" >VALOR USD.</td>
		</tr>

	  </thead>
	  <tbody>
{/report_header}
{report_header group="tmp_zona"}
		<tr ><td colspan={$agNumCols}  class="colhead headerrow grouphead" style="text-align: 'center'">
			  ZONA: {$rec.txt_zona|upper}
		</td></tr>
{/report_header}
{report_detail}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td class="colnom" style="width:200px; " >{$rec.txp_productor|upper}</td>
		  {assign var="aNom" value=$agNombres.$cat}
		  {foreach key=key item=col from=$aNom}
		    {assign var="colCan" value="cnt_"}
		    {assign var="valCan" value="pun_"}
		    {concat var="colCan" value=$key}
		    {concat var="valCan" value=$key}
		    {assign var="prd" value="*"}
		    {separate str=$key sep="_" ele=2 var="prd"}
		    {assign var="pro" value=$rec.txp_embarcador}
		    {assign var="indFle" value=$agFlete.$pro.$prd}
		    {assign var="txt" value=""}
		    {if  $rec.txp_indflete gt 0} {assign var="txt" value="+F"} {else} {/if} 
		      <td class="coldat colnum" >{if  $rec.$colCan eq 0}{else}{$rec.$colCan|number_format:0}{/if}</td>
		      <td class="coldat colnum" >
			{if  $rec.$valCan eq 0}
			      {if  $rec.$colCan eq 0}{else}<span style="background-color:yellow; color:red">* * * * </span>{/if}
			{else}{$rec.$valCan|number_format:2}{$txt}{/if}</td>
		  {/foreach}
		  <td class="coldat colnum col120">{$rec.tmp_SumCantidad|number_format:0}</td>
            <td class="coldat colnum col120" style="border-right-width:2px !important">{$rec.tmp_SumValor|number_format:2}</td>
	    </tr>
{/report_detail}
{report_footer group="tmp_zona"}
		<tr >
			<td style="font-weight:bold !important">SUMA ZONA {$rec.txt_zona|upper}</td>
			{assign var="aNom" value=$agNombres.$cat}
			{foreach key=key item=col from=$aNom}
                {assign var="colCan" value="cnt_"}
                {assign var="valCan" value="pun_"}
                {concat var="colCan" value=$key}
                {concat var="valCan" value=$key}
				<td class="coldat colnum" style="font-weight:bold !important">{if  $sum.$colCan eq 0}{else}{$sum.$colCan|number_format:0}{/if}</td>
                <td class="coldat colnum" style="font-weight:bold !important"></td>
			{/foreach}
			<td class="coldat colnum col120">{$sum.tmp_SumCantidad|number_format:0}</td>
            <td class="coldat colnum col120">{$sum.tmp_SumValor|number_format:2}</td>
		</tr>
{/report_footer}
{report_footer group="txp_catorden"}
		<tr >
			<td style="font-weight:bold !important">SUMA {$agCatNom.$cat|upper}</td>
			{assign var="aNom" value=$agNombres.$cat}
			{foreach key=key item=col from=$aNom}
                {assign var="colCan" value="cnt_"}
                {assign var="valCan" value="pun_"}
                {concat var="colCan" value=$key}
                {concat var="valCan" value=$key}
				<td class="coldat colnum" style="font-weight:bold !important">{if  $sum.$colCan eq 0}{else}{$sum.$colCan|number_format:0}{/if}</td>
                <td class="coldat colnum" style="font-weight:bold !important"></td>
			{/foreach}
			<td class="coldat colnum col120">{$sum.tmp_SumCantidad|number_format:0}</td>
            <td class="coldat colnum col120">{$sum.tmp_SumValor|number_format:2}</td>
		</tr>
	  </tbody>
	</table>
	</span>
	<div style="heigth: 30px"> </div>
{/report_footer}
{report_footer group="txp_nombbuque"}
  <br>
  <br>
  TOTAL VAPOR {$rec.txp_nombbuque|upper}:<br>
  CAJAS: {$sum.tmp_SumCantidad|number_format:0}<br>
  VALOR: {$sum.tmp_SumValor|number_format:2}
{/report_footer}
{/report}
  </div>
</div> <!-- end container -->
</body>



