<html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;" />
<title>{$gsSubTitul}</title>
<link rel="stylesheet" type="text/css" href="http://{$SERVER}{$Path}/../../css/AAA_basico.css">
<link rel="stylesheet" type="text/css" href="http://{$SERVER}{$Path}/../../css/AAA_tablas_print.css">
<link rel="stylesheet" type="text/css" media="screen, print" href="http://{$SERVER}{$Path}/../../css/report.css" title="CSS para pantalla" />
</head>
<body align:"center" id="top" style="font-family:'Arial'; ">
{report recordset=$agData record=rec resort=false groups="NIV"}
<table width="98%" border="1" cellpadding="0" cellspacing="0">
   
{report_header}
<div id="container"  style="height: 98% !important">
  <div class="tableContainer">
    
    <span style="text-shadow: red 0.2em 0.3em 0.2em; font-size:14pt; font-weight:bold">	</span>
	<table  cellspacing="0" >
	  <thead>
			<tr >
			  <td class="colhead" colspan=8 align="center" style="text-align: center; font-weight:bold; ">{$gsEmpresa}</td>
			</tr>
			<tr>
			  <td class="colhead" colspan=8 align="center" style="text-align: center; font-weight:bold; ">BALANCE DE COMPROBACION </td>
			</tr>
			<tr>
			  <td class="colhead"  colspan=8 align="center" style="text-align: center; font-weight:bold; ">Acumulado a: {$rec.PERI} </td>
			</tr>
	      		    
			<tr>
			<td  align="center">Cuenta</td>
			<td  align="center">Auxiliar</td>
			<td  align="center">Descripcion</td>
			<td  align="center">Saldo Anterior</td>
			<td  align="center">VDB</td>
			<td  align="center">VCR</td>
			<td  align="center">SAB</td>
			<td  align="center">SNT</td>

			</tr>
         </thead>	 
         <tbody>
{/report_header}

{report_header group="NIV"}
			<!-- Agrupar valores de activo y pasivo -->
    {if $rec.NIV eq 1}
			  {if $rec.CUE eq 1}
			    {assign var=TActSAN value=$rec.SAN}
			    {assign var=TActVDB value=$rec.VDB}
			    {assign var=TActVCR value=$rec.VCR}
			    {assign var=TActSAB value=$rec.SAB}
			    {assign var=TActSNT value=$rec.SNT}
			  {/if}
			  {if $rec.CUE eq 2}
			    {assign var=TPasSAN value=$rec.SAN}
			    {assign var=TPasVDB value=$rec.VDB}
			    {assign var=TPasVCR value=$rec.VCR}
			    {assign var=TPasSAB value=$rec.SAB}
			    {assign var=TPasSNT value=$rec.SNT}
			  {/if}
			  
			  <!-- Total de la cuenta anterior a la del grupo actual -->
			  {if ($GrpCue != $rec.CUE) && ($GrpCue != "")}
			    <tr style="white-space:nowrap; font-weight:bold;">
			      <td align="Left">&nbsp;</td><td align="Left">&nbsp;</td>
			      <td align="Left">TOTAL DE {$GrpDES}</td>
			      <td align="right">{$GrpSAN|number_format:2}</td>
			      <td align="right">{$GrpVDB|number_format:2}</td>
			      <td align="right">{$GrpVCR|number_format:2}</td>
			      <td align="right">{$GrpSAB|number_format:2}</td>
			      <td align="right">{$GrpSNT|number_format:2}</td>
			    </tr>
			  {/if}
			  {assign var=GrpCue value=$rec.CUE}
			  {assign var=GrpDES value=$rec.DES}
			  
			  {assign var=GrpSAN value=$rec.SAN}
			  {assign var=GrpVDB value=$rec.VDB}
			  {assign var=GrpVCR value=$rec.VCR}
			  {assign var=GrpSAB value=$rec.SAB}
			  {assign var=GrpSNT value=$rec.SNT}
			  
			  {assign var=TSAN value=$TSAN+$rec.SAN}
			  {assign var=TVDB value=$TVDB+$rec.VDB}
			  {assign var=TVCR value=$TVCR+$rec.VCR}
			  {assign var=TSAB value=$TSAB+$rec.SAB}
			  {assign var=TSNT value=$TSNT+$rec.SNT}
    {/if}
{/report_header}

{report_detail}
	<tr style="white-space:nowrap;">
			
			{if (($rec.CUE|substr:0:1) eq ' ')}
			  <td align="LEFT">&nbsp</td>
			{else}
			  <td align="LEFT">{$rec.CUE}</td>
			{/if}
			{if (($rec.CUE|substr:0:1) eq ' ')}
			  <td align="LEFT">{$rec.CUE}</td>
			{else}
			  <td align="LEFT">&nbsp</td>
			{/if}
			
			<td align="Left">{$rec.DES}</td>
			<td align="right">{$rec.SAN|number_format:2}</td>
			<td align="right">{$rec.VDB|number_format:2}</td>
			<td align="right">{$rec.VCR|number_format:2}</td>
			<td align="right">{$rec.SAB|number_format:2}</td>
			<td align="right">{$rec.SNT|number_format:2}</td>
         </tr>
{/report_detail}


{report_footer}
	<!-- Total del ultimo grupo de cuentas -->
	<tr style="white-space:nowrap; font-weight:bold;">
			<td align="Left">&nbsp;</td><td align="Left">&nbsp;</td>
			<td align="Left">TOTAL DE {$GrpDES}</td>
			<td align="right">{$GrpSAN|number_format:2}</td>
			<td align="right">{$GrpVDB|number_format:2}</td>
			<td align="right">{$GrpVCR|number_format:2}</td>
			<td align="right">{$GrpSAB|number_format:2}</td>
			<td align="right">{$GrpSNT|number_format:2}</td>
	</tr>
	 
	<!-- Resultado del Balance -->
	<tr style="white-space:nowrap; font-weight:bold;">
			<td align="Left">&nbsp;</td><td align="Left">&nbsp;</td>
			<td align="Left">TOTALES</td> 
			
			<td align="right">{$TSAN|number_format:2}</td>
			<td align="right">{$TVDB|number_format:2}</td>
			<td align="right">{$TVCR|number_format:2}</td>
			<td align="right">{$TSAB|number_format:2}</td>
			<td align="right">{$TSNT|number_format:2}</td>
	</tr> 
	 
{/report_footer}





</table>

{/report}
</body>
</html>