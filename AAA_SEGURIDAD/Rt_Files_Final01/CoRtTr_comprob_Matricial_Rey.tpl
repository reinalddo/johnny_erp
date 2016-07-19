<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla que aplica a la impresion del comprobante de retencion en matricial -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Erika Suarez" />
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
	@page retencion {size:21.5cm 15.5cm;}
	pagina {page: retencion;}
    </style>
  {/literal}
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
  <title>RETENCION</title>
  
</head>
<body id="top" style="font-family:'Arial' !important; font-size:9pt; font-weight:bold " onload="window.print();" >
  <div id="pagina" style="margin-left:0pt; height:7.5cm">
{report recordset=$agData record=rec	groups="TIB" resort=true}
{report_header}
    <!--<hr/>-->
    {*{assign var=numCol value=7}*}
    
        
    <div id = "cuerpo" style="height:6.5cm; border:#FFFFFF solid 1px; margin-top:113pt;">
    <!-- ********************************** C A B E C E R A ***********************************-->  
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'2.5cm'" >
        <tr style="height:0.7cm;">
	    <td colspan=1 class="resalta" nowrap style="padding-top:6pt;width:130pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt">{$rec.NOM}</td>
	    <td colspan=1 class="" style="width:180pt">&nbsp;</td>
	    <td colspan=1 class="" nowrap>{$rec.FEC}</td>
        </tr>
	<tr style="height:0.7cm;">
	    <td colspan=1 class="resalta" nowrap style="padding-top:6pt;width:130pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt">{$rec.RUC}</td>
	    <td colspan=1 class="" style="width:200pt">&nbsp;</td>
	    <td colspan=1 class="" style="padding-left:1.7cm" nowrap>{$rec.TIP}</td>
        </tr>
        <tr style="height:0.7cm;">
	    <td colspan=1 class="resalta" nowrap style="padding-top:6pt;width:130pt;">&nbsp;</td>
	    <td colspan=1 class="" nowrap  style="width:400pt">{$rec.Direcc}</td>
	    <td colspan=1 class="" style="width:200pt">&nbsp;</td>
	    <td colspan=1 class="" style="padding-left:1.5cm" nowrap>{$rec.FAC}</td>
        </tr>
        </table>
      <!-- ********************************** DETALLE ***********************************-->  
	
      <table border=0  cellspacing=0 style="table-layout:fixed; padding-left: 0px; padding-top: 10pt; border:#FFFFFF solid 1px; width:800px; height:'4cm'"  >
        <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:190pt;">&nbsp;<!--<strong>EJERCICIO FISCAL</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:190pt;">&nbsp;<!--<strong>BASE IMPONIBLE</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:245pt;">&nbsp;<!--<strong>IMPUESTO</strong>--></td>
	  <!--<td class="headerrow cabTabla resalta" style="text-align:center; width:115pt;">&nbsp;--><!--<strong>COD RETENCION</strong>--><!--</td>-->
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:130pt;">&nbsp;<!--<strong>% RETENCION</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="width:200pt;">&nbsp;<!--<strong>VALOR RETENIDO</strong>--></td>
	</tr>
{/report_header}

{report_detail}
        { if ($rec.MIB > 0) }
		      <tr style="white-space:nowrap; height:0.62cm;">
			<td class="colnum der" style="text-align:center;"> {$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; ">{$rec.BIB}</td>
			<td class="colnum espacioDer der" style="text-align:center; ">IVA</td>
			<!--<td style="text-align:center; width:75pt;">{$rec.TIB2|number_format:0}</td> -->
			<td class="colnum espacioDer der" style="text-align:center; ">{$rec.PIB|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:center; ">{$rec.MIB|number_format:2}</td>
		      </tr>
	{/if}
			
		      { if ($rec.MIS > 0) }
		      <tr style="white-space:nowrap;height:0.7cm;">
		      <td class="colnum der" style="text-align:center; ">{$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; ">{$rec.BIS}</td>
			<td class="colnum espacioDer der" style="text-align:center;">IVA</td>
			<!--<td style="text-align:center; width:75pt;">{$rec.TIS2|number_format:0}</td>-->
			<td class="colnum espacioDer der" style="text-align:center; ">{$rec.PIS|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:center; ">{$rec.MIS|number_format:2}</td>
			</tr>
		      {/if}
			
		      {if ($rec.MIR>0)}
		      <tr style="white-space:nowrap; height:0.7cm;">
		      <td class="colnum der" style="text-align:center; ">{$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center;">{$rec.BIR}</td>
			<td class="colnum espacioDer der" style="text-align:center;">RENTA</td>
			<!--<td style="text-align:center; width:75pt;">{$rec.TIR|number_format:0}</td>-->
			<td class="colnum espacioDer der" style="text-align:center;">{$rec.PIR|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:center;">{$rec.MIR|number_format:2}</td>
			</tr>
		      {/if}
			
		      {if ($rec.MIR2>0)}
		      <tr style="white-space:nowrap;height:0.7cm;">
		      <td class="colnum der" style="text-align:center;">{$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center;">{$rec.BIR2}</td>
			<td class="colnum espacioDer der" style="text-align:center;">RENTA</td>
			<!--<td style="text-align:center; width:75pt;">{$rec.TIR2|number_format:0}</td>-->
			<td class="colnum espacioDer der" style="text-align:center;">{$rec.PIR2|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:center;">{$rec.MIR2|number_format:2}</td>
			</tr>
		      {/if}
		      {if ($rec.MIR3>0)}
		      <tr style="white-space:nowrap;height:0.7cm;">
		      <td class="colnum der" style="text-align:center; ">{$rec.PER}</td>
			<td nowrap class="coldata espacioIzq" style="text-align:center; ">{$rec.BIR3}</td>
			<td class="colnum espacioDer der" style="text-align:center;">RENTA</td>
			<!--<td style="text-align:center; width:75pt;">{$rec.TIR3|number_format:0}</td>-->
			<td class="colnum espacioDer der" style="text-align:center;">{$rec.PIR3|number_format:2} %</td>
			<td class="colnum espacioDer der" style="text-align:center;">{$rec.MIR3|number_format:2}</td>
		      </tr>
		      {/if}
{/report_detail}

{report_footer}
 </table>
</div>
    <!-- ********************************** SUMATORIA ***********************************-->  
   <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; padding-top:10pt; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'" >    
    <tr style="white-space:nowrap">
        <td class ="resalta" style="width:750pt"><strong></strong></td>
	{assign var=TotalRet value=$rec.MIR3+$rec.MIR2+$rec.MIR+$rec.MIS+$rec.MIB}
        {* <td class="colnum espacioDer der" style="text-align:center; width:200pt;">{math equation="x + y + z + a + b" x=$rec.MIR3 y=$rec.MIR2 z=$rec.MIR a=$rec.MIS b=$rec.MIB}</td> *}
	<td class="colnum espacioDer der" style="text-align:center; width:200pt;">{$TotalRet|number_format:2}</td>
    </tr>
  </table> 
   
    
{/report_footer}

{/report}
</div>
</body>