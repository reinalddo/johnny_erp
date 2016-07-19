<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla que aplica a la impresion del comprobante de retencion en matricial -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="root" />
  {literal}
  <style type="text/css">
        .padding10{padding:10px;}
        .empresa{height:50px;font-size:20px;font-weight:bold;text-align:center;vertical-align:middle;}
        .titulo{font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;}
        .parrafo{text-align:justify; vertical-align:middle; text-transform:uppercase;}
        .bordeCompleto {border: #FFFFFF solid 1px;}
        .bordeDer {border-right: #FFFFFF solid 1px;}
        .bordeIzq {border-left: #FFFFFF solid 1px;}
        .bordeSup {border-top: #FFFFFF solid 1px;}
        .bordeInf {border-bottom: #FFFFFF solid 1px;}
        .izq{text-align:left;}
        .der{text-align:right;}
        .cen{text-align:center;}
        .espacioCol1{padding-left:70px;}
        .cabTabla{height:45px;}
        .espacioDer{padding-right:24px !important;}
        .espacioIzq{padding-left:10px !important;}
	.resalta {font-weight:900; font-style:italic}
        @media print {
            body { font-size: 9pt; }
        }
	@page retencion {size:21cm 14.5cm;}
	pagina {page: retencion;}
    </style>
  {/literal}
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
  <title>RETENCION</title>
  
</head>
<body id="top" style="font-family:'Arial' !important; font-size:9pt; font-weight:bold " >
  <div id="pagina" style="margin-left:0pt; height:8cm">
{report recordset=$agData record=rec	groups="TIB" resort=true}
{report_header}
    <!--<hr/>-->
    {*{assign var=numCol value=7}*}
<!--JVL    <div id = "cuerpo" style="height:7.5cm; border:#FFFFFF solid 1px; margin-top:95pt;">   JVL Original Aplesa **-->
    <div id = "cuerpo" style="height:7.5cm; border:#FFFFFF solid 1px; margin-top:105pt;">
    <!-- ********************************** C A B E C E R A ***********************************-->  
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'2.5cm'" >
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt">{$rec.NOM}</td>
	    <td colspan=1 class="" style="width:220pt">&nbsp;</td>
	    <td colspan=1 class="" nowrap>{$rec.FEC}</td>
     </tr>
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt">{$rec.RUC}</td>
	    <td colspan=1 class="" style="width:220pt">&nbsp;</td>
	    <td colspan=1 class="" nowrap>{$rec.TIP}</td>
     </tr>
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt">{$rec.Direcc}</td>
	    <td colspan=1 class="" style="width:220pt">&nbsp;</td>
	    <td colspan=1 class="" nowrap>{$rec.FAC}</td>
     </tr>
<!--JVL    La siguiente FILA no existia   JVL Original Aplesa **-->
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
     </tr>
        <!-- <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
	    <td colspan=3 class="" nowrap style="width:800pt;">{$rec.CONCEP}</td>
        </tr> -->
    </table>
      <!-- ********************************** DETALLE ***********************************-->  
	
      <table border=0  cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'4cm'" >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:170pt;">&nbsp;<!--<strong>EJERCICIO FISCAL</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:190pt;">&nbsp;<!--<strong>BASE IMPONIBLE</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:120pt;">&nbsp;<!--<strong>IMPUESTO</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:140pt;">&nbsp;<!--<strong>COD IMPUESTO</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:150pt;">&nbsp;<!--<strong>% RETENCION</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:140pt;">&nbsp;<!--<strong>VALOR RETENIDO</strong>--></td>
	</tr>
{/report_header}

<!--JVL    En el detalle del Reporte todo era align: center excepto columna VALOR RETENIDO  OJO JVL Original Aplesa **-->
{report_detail}
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
     </tr>
        { if ($rec.MIB > 0) }
		      <tr style="white-space:nowrap">
			<td class="colnum der" style="text-align:center; width:200pt;"> {$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:200pt;">{$rec.BIB}</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">IVA</td>
			<td style="text-align:left;">{$rec.TIB2}</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;">{$rec.PIB|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">{$rec.MIB|number_format:2}</td>
		      </tr>
      	{/if}
			
		      { if ($rec.MIS > 0) }
		      <tr style="white-space:nowrap">
		      <td class="colnum der" style="text-align:center; width:200pt;">{$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:200pt;">{$rec.BIS}</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">IVA</td>
			<td style="text-align:left;">{$rec.TIS2}</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;">{$rec.PIS|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">{$rec.MIS|number_format:2}</td>
			</tr>
		      {/if}
			
		      {if ($rec.MIR>0)}
		      <tr style="white-space:nowrap">
		      <td class="colnum der" style="text-align:center; width:200pt;">{$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:200pt;">{$rec.BIR}</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">RENTA</td>
			<td style="text-align:left;">{$rec.TIR}</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;">{$rec.PIR|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">{$rec.MIR|number_format:2}</td>
			</tr>
		      {/if}
			
		      {if ($rec.MIR2>0)}
		      <tr style="white-space:nowrap">
		      <td class="colnum der" style="text-align:center; width:200pt;">{$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:200pt;">{$rec.BIR2}</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">RENTA</td>
			<td style="text-align:left;">{$rec.TIR2}</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;">{$rec.PIR2|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">{$rec.MIR2|number_format:2}</td>
			</tr>
		      {/if}
		      {if ($rec.MIR3>0)}
		      <tr style="white-space:nowrap">
		      <td class="colnum der" style="text-align:center; width:200pt;">{$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; width:200pt;">{$rec.BIR3}</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">RENTA</td>
			<td style="text-align:left;">{$rec.TIR3}</td>
			<td class="colnum espacioDer der" style="text-align:center; width:100pt;">{$rec.PIR3|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:right; width:200pt;">{$rec.MIR3|number_format:2}</td>
		      </tr>
		      {/if}
     <tr>
	    <td colspan=1 class="resalta" nowrap style="padding-top:8pt;width:120pt;">&nbsp;</td>
     </tr>
{/report_detail}

{report_footer}
 </table>
</div>
    <!-- ********************************** SUMATORIA ***********************************-->  
  <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >    
    <tr style="white-space:nowrap">
        <td class ="resalta" style="width:790pt"><strong></strong></td>
        {assign var=TotalRet value=$rec.MIR3+$rec.MIR2+$rec.MIR+$rec.MIS+$rec.MIB}
      {*<td class="colnum espacioDer der" style="text-align:center; width:115pt;">{math equation="x + y + z + a + b" x=$rec.MIR3 y=$rec.MIR2 z=$rec.MIR a=$rec.MIS b=$rec.MIB}</td> *}
	<td class="colnum espacioDer der" style="text-align:center; width:200pt;">{$TotalRet|number_format:2}</td>
    </tr>
  </table>
   
    
{/report_footer}

{/report}
</div>
</body>