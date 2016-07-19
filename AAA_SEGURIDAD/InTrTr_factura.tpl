<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
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
        .cabTabla{height:40px;}
        .espacioDer{padding-right:20px !important;}
        .espacioIzq{padding-left:20px !important;}
        @media print {
            body { font-size: 10pt; }

          }

    </style>
  {/literal}
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_tablas_print.css" title="CSS para imprimir" />-->
  
    <title>Factura Comercial</title>
  
</head>
<body id="top" style="font-family:'Arial,Tahoma,Helvetica,sans-serif' !important; font-size:13px;" onload="window.print();">
{report recordset=$agData record=rec	groups="TIPO,COMPR" resort=true}
{report_header}
    <!--<hr/>-->
    {assign var=numCol value=7}
    
    
    {*<div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        PRODUCTORES<br>
        {$subtitulo}
    </p>
    </div>*}
    <table width="850" border=0 cellspacing=0 style="border:#FFFFFF solid 1px; margin-top:70px;">
        <!--<tr>
            <td colspan={$numCol} style="height:50px;font-size:16px;font-weight:bold;text-align:center;vertical-align:middle;">
                {$smarty.session.g_empr}</td>            
        </tr>-->
        <tr>
            <td colspan=2 class="espacioCol1" nowrap>{$rec.RECEP|truncate:32}</td>
            <td colspan=2 class="espacioCol1">{$rec.FECHA}</td>
        </tr>
        <tr>
            <td colspan=2 class="espacioCol1" nowrap>{$rec.direccion|truncate:35}</td>
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr>
            <td colspan=2 class="espacioCol1">{$rec.CIU}</td>
            <td colspan=2 class="espacioCol1">{$rec.telefono}</td>
        </tr>
        <tr>
            <td colspan=2 class="espacioCol1">{$rec.ruc}</td>
            <td colspan=2>&nbsp;</td>
        </tr>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle; text-transform:uppercase;">
	  <td class="headerrow cabTabla" width="30">&nbsp;<!--<strong>CANTIDAD</strong>--></td>
	  <td class="headerrow cabTabla" width="560">&nbsp;<!--<strong>DESCRIPCION</strong>--></td>
	  <td class="headerrow cabTabla" width="110">&nbsp;<!--<strong>V.UNITARIO</strong>--></td>
	  <td class="headerrow cabTabla" width="170">&nbsp;<!--<strong>TOTAL</strong>--></td>
	</tr>
        
{/report_header}

{report_detail}
    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum der" >{$rec.CANTI|number_format:0}</td>  
	<td nowrap class="coldata espacioIzq">{$rec.ITEM}</td>
        <td class="colnum espacioDer der" >{$rec.vunit|number_format:4}</td>        
        <td class="colnum espacioDer der" >{$rec.VALOR|number_format:2}</td>
    </tr>
{/report_detail}

{report_footer}

    <tr><!-- style="border:#000000 solid 1px;">-->
        <td class="colnum der" >&nbsp</td>  
	<td nowrap class="coldata espacioIzq">SEM.: {$rec.REFOP}</td>
        <td class="colnum espacioDer der" >&nbsp</td>        
        <td class="colnum espacioDer der" >&nbsp</td>
    </tr>
    {assign var=cuenta value=$count.CANTI}
    {foreach from=$agFilas item=value}
        {if ($value > $cuenta)}
            <tr>
                <td class="der bordeIzq bordeDer" >&nbsp</td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>
                <td class="izq bordeDer"></td>               
            </tr>
        {/if}
    {/foreach}

    <tr style="padding-top:30px;">
        <td colspan=3 rowspan=6 class="espacioCol1" style="vertical-align:top;"><!--<strong>SON:</strong>--> {$letras}</td>
        <!--<td><strong>SUBTOTAL:</strong></td>-->
        <td class="colnum der">{$sum.VALOR|number_format:2}</td>
    </tr>
    <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
     <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
    <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td>&nbsp;</td>
    </tr>
    <tr>
        <!--<td><strong>IVA:</strong></td>-->
        <td class="colnum der" style="padding-top:0px;">{$iva|number_format:2}</td>
    </tr>
    <tr>
        <!--<td><strong>TOTAL A PAGAR:</strong></td>-->
        <td class="colnum der" style="padding-top:5px;">{$valorTot|number_format:2}{*$sum.VALOR|number_format:2*}</td>
    </tr>
    </table>
    </br>
    <div style="font-size:0.8em; text-align:left;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="font-size:0.8em; text-align:left;">{$rec.COMPR}</div>
    
{/report_footer}

{/report}
</body>