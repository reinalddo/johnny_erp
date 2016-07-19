<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Plantilla para Factura - Importadora Nieto - Impresión en Matricial, formato pequeño -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
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
        .cabTabla{height:32px;}
        .espacioDer{padding-right:24px !important;}
        .espacioIzq{padding-left:10px !important;}
		.resalta {font-weight:900; font-style:italic}
        @media print {
            body { font-size: 9pt; }

          }
	@page factura {size:16cm 22cm;}
	pagina {page: factura;}

    </style>
  {/literal}
  
 <title>Factura Comercial</title>
  
</head>
<body id="top" style="font-family:'Sans serif' !important; font-size:11pt; " onload="window.print()">
  <div id="pagina" style="margin-left:0pt; height:17.7cm">
{report recordset=$agData record=rec	groups="TIPO,COMPR" resort=true}
{report_header}
    {assign var=numCol value=7}
    <div id = "cuerpo" style="height:12.5cm; border:#FFFFFF solid 1px; margin-top:110pt;">
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:860px; height:'8.5cm'" >
        <tr style="height:0.7cm">
	    <td colspan=1 style="width:320pt;">&nbsp;</td>
	    <td colspan=1 style="width:70pt; text-align:center;">{$rec.LUGAR}</td>
	    <td colspan=1 class="" style="width:40pt; text-align:right;">{$rec.DIA}</td>
	    <td colspan=1 class="" style="width:50pt; text-align:right;">{$rec.MES}</td>
	    <td colspan=1 class="" style="width:40pt; text-align:right;">{$rec.ANIO}</td>
        </tr>
    </table>
    
    
    <table border=0 cellspacing=0 style="table-layout:fixed; border:#FFFFFF solid 1px; width:860px; height:'8.5cm';margin-top:4pt;" >
        <tr style="height:17pt">
	    <td colspan=1 class="resalta" nowrap style="width:80pt;"><strong></strong></td>
            <td colspan=1 class="" nowrap  style="width:470pt">{$rec.RECEP}</td>
	    <td colspan=1>&nbsp;</td>
        </tr>
    </table>
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:860px; height:'8.5cm'" >
        <tr style="height:17pt">
	    <td colspan=1 class="resalta" nowrap style="width:80pt;"><strong></strong></td>
            <td colspan=1 class="" style="width:300pt; padding-left:18pt;">{$rec.ruc}</td>
            <td colspan=1 class="resalta" nowrap style="width:150pt;"><strong></strong></td>
	    <td colspan=1>&nbsp;</td>
        </tr>
	<tr style="height:17pt">
	    <td colspan=1 class="resalta" nowrap style="width:80pt;"><strong></strong></td>
            <td colspan=1 class="" nowrap style="width:300pt; padding-left:15pt;">{$rec.ciudad}-{$rec.direccion|truncate:40}</td>
	    <td colspan=1 class="resalta" nowrap style="width:150pt"><strong></strong></td>
            <td colspan=1 class="" style="vertical-align:middle; padding-left:10pt;">{$rec.telefono}</td>
        </tr>
	</table>
    
	<table border=0  cellspacing=0 style="table-layout:fixed; border:#FFFFFF solid 1px; width:860px;" >
        <tr>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:80pt;">&nbsp;<!--<strong>CANTIDAD</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:370pt;">&nbsp;<!--<strong>DESCRIPCION</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:90pt;">&nbsp;<!--<strong>VALOR UNITARIO</strong>--></td>
	  <td class="headerrow cabTabla resalta" style="text-align:center; width:110pt;">&nbsp;<!--<strong>TOTAL</strong>--></td>
	</tr>
        
{/report_header}

{report_detail}
    <tr style="font-size:11pt; height:18pt;">
        <td class="colnum der" style="text-align:right; padding: 0px 30px 0px 0px; width:80pt;" >{$rec.CANTI|number_format:0}</td>  
	<td nowrap class="coldata espacioIzq" style="text-align:left; width:370pt;">{$rec.ITEM}</td>
        <td class="colnum espacioDer der" style="text-align:right; width:90pt;">{$rec.vunit|number_format:4}</td>        
        <td class="colnum espacioDer der" style="text-align:right; width:110pt;">{$rec.VALOR|number_format:2}</td>
    </tr>
{/report_detail}

{report_footer}
	{*<tr>
	    <td class="izq bordeDer"></td>
	    <td class="coldata espacioIzq" style="text-align:left; width:370pt;" nowrap>{$rec.CONCEP|regex_replace:"/[\n]/":"<br>"}</td> <!--Reemplaza los enter por salto de lineas-->
	    <td class="izq bordeDer"></td>
	    <td class="izq bordeDer"></td>
	</tr>*}
    </table>
</div>
    <table border=0 cellspacing=0 style="table-layout:fixed; padding-left: 0px; border:#FFFFFF solid 1px; width:800px; height:'8.5cm'; padding-top:7pt;" >
    <tr>
        <td colspan=2 rowspan=9 class="espacioCol1" style=" vertical-align: top; width:400pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$letras}</td>
	<td class ="resalta" style="width:90pt"><strong></strong></td>
	{assign var=SubNeto value=$sum.VALOR-$sum.VALDscto}
        <td class="colnum espacioDer der" style="width:95pt">{$SubNeto|number_format:2}</td>
    </tr>
    <tr>
        <td><!--<strong>IVA:</strong>--></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class ="resalta"><strong>&nbsp;</strong></td>
        <td class="colnum espacioDer der" style="padding-top:0px;">&nbsp;</td>
    </tr>
    <tr>
        <td class ="resalta"><!--<strong>IVA:</strong>--></td>
        <td class="colnum espacioDer der" style="padding-top:0px;">&nbsp;{$iva|number_format:2}</td>
    </tr>
    <tr>
        <td class ="resalta" style="width:150px"><!--<strong>TOTAL :</strong>--></td>
        <td class="colnum espacioDer der" style="padding-top:3px;">&nbsp;{$valorTot|number_format:2}{*$sum.VALOR|number_format:2*}</td>
    </tr>
	<tr style="height: 1.5cm">
        <td style="height: 1.5cm;"> &nbsp</td>
    </tr>
	
    <!--	
    <tr >
        <td colspan=2>_______________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________</td>
    </tr>
	<tr style="">
        <td colspan=3 style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Elaborado &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Autorizado</td>
    </tr>
    <tr style="font-size:0.8em; text-align:left;">
	<td colspan=4>{$smarty.session.g_user}, {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'} &nbsp;/ &nbsp;{$rec.COMPR} </td>
    </tr>
    -->
    
   </table>
   
    
{/report_footer}

{/report}
</div>
</body>