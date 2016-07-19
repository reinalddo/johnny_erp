<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Fausto Astudillo" />
  <style media="screen, print">{$style_pr}</style><!-- -->
  <style media="screen">{$style_sc}</style>
</head>

<body align:"center" id="top" style="font-family:'Arial'; ">
{report recordset=$agData record=rec	groups="txp_NombBuque, txt_Naviera, txt_Consignatario, txt_DestiFinal, txp_Contenedor, txp_Producto, txp_Productor" resort=false}
{assign var="cntCont" value=0}
{report_header}
  {set var=$rowcls value='rowpar'}
<table id="txp_Contenedor" class="nosaltar" style="align: center; border: 0px solid white ; border-collapse:collapse; padding: 0px">
<tbody >
{/report_header}

{report_header group="txp_Contenedor"}
  {assign var="cntID" value=$rec.txp_Contenedor}
  {if $actu  neq $cntID} {addto var="cnts" value=1} {/if}
<tr>
<td width="100%" class="nosaltar" style="page-break-inside:void; " >																			
	<table style="border:1px solid #d8d8d8; padding:1; cell-spacing:0; border-collapse:collapse; align:center; width:100%">
	  <tbody style="height:auto !important; overflow:hidden !important">
                <tr ><td colspan=8 class="sinlineas"
                  style="border-bottom: 1px solid lightgrey; height: 35px ; font-size:12pt; font-weight:bold; text-align: center; vertical-align: bottom; white-space: nowrap">
                  {addto var="cntCont" value=1} {$agConten.$cntID.txt_Consignatario|upper}&nbsp; &nbsp;<br>
                  {$agConten.$cntID.txt_Naviera|upper} {$agConten.$cntID.cnt_Serial|upper}
                  &nbsp; &nbsp; Sem: {$pSem|substr:1:2} V/ {$rec.txp_NombBuque|upper}</td></tr>
                <tr ><td colspan=8 class="sinlineas" style="font-size: 12px; font-weight:bold; text-align:center">
                  ZARPE:  {$agConten.$cntID.cnt_FecZarpe|date_format:"%b/%d/%y %H:%M"}
                  </td>
                </tr>
                </tr>
		<tr ><td colspan=1 class="sinlineas" style="font-weight:300; white-space: nowrap">SELLO:</td>
                     <td>{$agConten.$cntID.cnt_SelloNav|upper}</td>
                     <td>&nbsp;</td>
		     <td colspan=5 class="sinlineas" style="font-weight:300; white-space: nowrap" ></td></tr>
		<tr ><td colspan=1 class="sinlineas" style="font-weight:300; white-space: nowrap">FECHA:</td>
                     <td style="text-align:left">{$agConten.$cntID.cnt_FecZarpe|date_format:"%b/%d/%y"}</td>
                     <td style="text-align:left">{$agConten.$cntID.cnt_FecZarpe|date_format:" %H:%M"}</td>
		     <td colspan=1 class="sinlineas" style="font-weight:300; white-space: nowrap">DESTINO:</td>
                     <td colspan=3>{$agConten.$cntID.txt_Destino|upper} - {$agConten.$cntID.txt_DestiFinal|upper}</td>
                </tr>
	  <tr ><td colspan=1 class="sinlineas"  style="white-space: nowrap; font-weight:300">INICIO EMB.:</td>
               <td style="white-space: nowrap;" class="sinlineas">{$agConten.$cntID.cnt_FecInicio|date_format:"%b/%d/%y"} </td>
               <td style="text-align:left; white-space: nowrap;" class="sinlineas">{$agConten.$cntID.cnt_FecInicio|date_format:" %H:%M"} </td>
	       <td colspan=1 class="sinlineas"  style="white-space: nowrap;font-weight:300">CTRL. TEMP.:</td>
               <td colspan= 3 style="white-space: nowrap;" class="sinlineas">{$agConten.$cntID.cnt_CtrlTemp|date_format:"%b/%d/%y &nbsp;&nbsp; %H:%M"}&nbsp; &nbsp;{$agConten.$cntID.cnt_Temperatura} ºC</td>
          </tr>
	  <tr ><td colspan=1 class="sinlineas" style="white-space: nowrap; font-weight:300">FIN CARGA: </td>
              <td class="sinlineas">{$agConten.$cntID.cnt_FecFin|date_format:"%b/%d/%y "}</td>
              <td class="sinlineas" style="text-align:left">{$agConten.$cntID.cnt_FecFin|date_format:"%H:%M"}</td>
              <td colspan=1 class="sinlineas" style="white-space: nowrap; font-weight:300">VENTILACION:</td>
              <td colspan= 3 class="sinlineas">{$agConten.$cntID.cnt_Ventilacion} %</td>
          </tr>
	  <tr><td colspan=1 class="sinlineas" style="vertical-align: top; white-space: nowrap; font-weight:300">ENCHUFE PTO.: </td>
              <td class="sinlineas" style="vertical-align: top; ">{$agConten.$cntID.cnt_Enchufe|date_format:"%b/%d/%y"}</td>
              <td class="sinlineas" style="text-align:left; vertical-align: top; ">{$agConten.$cntID.cnt_Enchufe|date_format:" %H:%M"}</td>
	      <td colspan=1 class="sinlineas" style="vertical-align: top; white-space: nowrap; font-weight:300">CHEQUEADOR: </td>
              <td colspan=3 class="sinlineas" style="vertical-align: top; ">{$agConten.$cntID.txt_Chequeador|upper}<br>
              {$agConten.$cntID.txt_Chequeador2|upper}<br>
              {$agConten.$cntID.txt_Chequeador3|upper}</td></tr>
	  <tr><td colspan=1 class="sinlineas" style="white-space: nowrap;  font-weight:300">OBSERVACIONES:</td>
          <td colspan=7 class="sinlineas" style=" height: 35px; vertical-align:top">{$agConten.$cntID.cnt_Observaciones}</td></tr>
		<tr>
		  <td colspan=5 style="width:100px; border-bottom: 0px; border-top:0px; border-left0px; "></td>
		  {assign var="aCabe" value=$agCabeGru.$cntID}
		  {foreach key=key item=col from=$aCabe}
				<td colspan={$col.long} style="text-align:center; border:1px solid lightgrey; width:auto; white-space: nowrap; ">{$key|upper}</td>
		  {/foreach}
		  <td colspan=1  style="border-bottom: 0px; border-top:0px; border-left:1px solid lightgrey; border-right:0px"></td>
		</tr>
		<tr>
		  {assign var="aSubg" value=$agCabeSGr.$cntID}
		  <td colspan=5  class="headerrow" style="width:100px; border-bottom: 0px; border-top:0px; border-left:0px; border-right: 1px solid lightgrey;"></td>
		  {foreach key=key item=col from=$aSubg}
				<td class="colhead headerrow"  colspan={$col.long} style="border:1px solid lightgrey;  text-align:center">{$key|upper}</td>
		  {/foreach}
		  <td colspan=1 class="colhead headerrow" style="border-bottom: 0px; border-top:0px; border-left:1px solid lightgrey; border-right:0px"></td>
		</tr>
	  
		<tr>
		  {assign var="aCols" value=$agNombres.$cntID}
		  <td  colspan=2 style="border:1px solid lightgrey !important;  text-align:center; " >CODIGOS </td>
		  <td  colspan=3 style="border:1px solid lightgrey !important;  text-align:center; " >NOMBRES</td>
			{foreach key=key item=col from=$aCols}
			  <td colspan=1  style="border:1px solid lightgrey; text-align:center">{$col|upper}</td>
		  {/foreach}
		  <td colspan=1  style="border:1px solid lightgrey !important;  text-align:center">TOTAL</td>
		</tr>
	  </thead>
	  <tbody>
{/report_header}
{report_detail}
		<tr class="{$rowcls}">
		{if  $rowcls eq "rowpar"}{set var=$rowcls value='rowimpar'}{/if}
		  <td colspan=2 class="colnom" style=" border:1px solid lightgrey;" >&nbsp;{$rec.vcc_codigosemp|upper}</td>
		  <td colspan=3 class="colnom" style=" border:1px solid lightgrey;" >&nbsp;{$rec.txp_Productor|upper}</td>
			{foreach key=key item=col from=$aCols}
				<td class="coldat colnum" style="border:1px solid lightgrey;">{if  $rec.$key eq 0}{else}{$rec.$key|number_format:0}{/if}</td>
			{/foreach}
			<td class="coldat colnum col120" style="border:1px solid lightgrey;">{$rec.tmp_SumCantidad|number_format:0}</td>
	    </tr>
{/report_detail}
{report_footer group="txp_Contenedor"}
		<tr >
			<td colspan=5 style="border:1px solid lightgrey; font-weight:bold !important; height=25px; vertical-align:top">SUMA {$rec.txp_Contenedor|upper}</td>
			{foreach key=key item=col from=$aCols}
			  <td class="coldat colnum" style="border:1px solid lightgrey; font-weight:bold !important; vertical-align:top">
				{if  $sum.$key eq 0}{else}{$sum.$key|number_format:0}{/if}
			  </td>
			{/foreach}
			<td class="coldat colnum col120"  style="font-weight:bold; border:1px solid lightgrey; vertical-align:top">{$sum.tmp_SumCantidad|number_format:0}</td>
		</tr>
		<tr><td class="" colspan=4  style="font-weight:bold; text-align: 'left' !important">CAJAS TRATADAS: </td></tr>
                <tr > <td class="sinlineas" width=60 style="font-weight:300">CODIGO: </td>
                      <td>{$agConten.$cntID.cnt_CodCajTra}</td>
                       <td>CANTIDAD:</td>
                      <td>{$agConten.$cntID.cnt_CantCajTra}</td>
                        <td class="sinlineas" style="padding-left:20; font-weight:300">TIPO DE CAJA: </td>
                      <td>{$agConten.$cntID.cnt_TipoCajTra}</td>
                </tr >
	  </tbody>
	</table> 
</td>  												
</tr>
{if $cnts eq 3}
  <tr class="sinlineas" style="height:0px;  page-break-after:  always "><td></td></tr>
  {assign var='cnts' value=0}
{else}
  {addto var="cnts" value=1}
  <tr class="sinlineas" style="height:25px;"><td></td></tr>
{/if}
{/report_footer}
{report_footer group="txt_Naviera"}
<tr>
<td>
</td>
</tr>
{/report_footer}
{report_footer}
<tr>
<td>
	<table style="border:2px outset #d8d8d8; padding:1; cell-spacing:0; border-collapse:collapse; width:100%">
	<caption> RESUMEN GENERAL DE EMBARQUE</caption>
	<tbody style="height:auto !important; overflow: hidden !important">
          {assign var="nav" value = 'total'}
          {foreach key=key item=row from=$agResEmp}
            <tr><td class="headerrow" colspan = "4"
                style="height: 35px; text-align: right; vertical-align:bottom; font-size:12pt; font-weight:bold; color:black">{$key|upper}</td></tr>
              <tr ><td colspan=2 style="text-align:center; border:1px solid lightgrey;">MARCA</td>
                <td style="text-align:center;border:1px solid lightgrey;" >CANTIDAD</td>
                <td style="text-align:center;border:1px solid lightgrey;" >SUMATORIA</td>
              </tr>
            {assign var="sumK2" value = 0}
            {foreach key=k2 item=r2 from=$row}
              <tr><td class="headerrow" colspan = 4 style="text-align:center;border:1px solid lightgrey;" ><b>{$k2|upper}</b></td></tr>
              {foreach key=k3 item=r3 from=$r2}
                    {assign var="mar" value = $k3}
                    {assign var="sumK3" value = 0}
                    {foreach key=k4 item=r4 from=$r3}
                      <tr><td style="width:80pt;">{$mar|upper}</td>
                      <td class="sinlineas" style="width:70pt; border: 1px solid lightgrey">{$k4|upper}</td>
                      <td class="sinlineas" style="border: 1px solid lightgrey; text-align:right">{$r4|number_format:0}</td>
                      <td class="sinlineas" style="border: 1px solid lightgrey"></td>                      
                      </tr>
                      {assign var="mar" value = ""}
                      {addto var="sumK3" value = $r4}
                    {/foreach}
                    <tr >
                        <td class="sinlineas" style=""></td>
                        <td class="sinlineas" style=" text-align:right"></td>
                        <td class="sinlineas" style="border-top: 1px solid lightgrey"></td>
                        <td class="sinlineas" style="text-align:right; border-top: 1px solid lightgrey;" >
                            {$sumK3|number_format:0}</td>
                    </tr>
                    {addto var="sumK2" value = $sumK3}
              {/foreach}
              <tr>
                <td class="sinlineas"  colspan = "2" style="text-align: right"></td>
                <td colspan=1 class="sinlineas" style=" vertical-align: top; text-align:right"></td>
                <td colspan=1 class="sinlineas" style=" height:25px; vertical-align: top; font-weight: bold; text-align:right; border-top: 1px solid gray">{$sumK2|number_format:0}</td>
              </tr>
            {/foreach}

          {/foreach}
	</tbody>
	</table> 
</td>
</tr>  
{/report_footer}
</tbody>
</table> 
<div id="container" >
  <div class="tableContainer">
</div>
</div> <!-- end container -->
</body>

{/report}
