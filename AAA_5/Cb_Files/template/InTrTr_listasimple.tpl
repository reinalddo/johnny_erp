<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  {literal}
  <style type="text/css">
        .num{text-align:right;}
	.subtitulo{font-size:10px;}
	.subtotal{font-style:italic; font-weight:bold;}
	.total{font-size:12px; font-weight:bold;margin-top:20px; padding-top:20px;}
        .espacio{margin-left:20px;};
        .espaciogrupo{padding:10px !important;}
    </style>
  {/literal}
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>LISTA SIMPLE</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="BODEG, RECEP, COMPR, SECUE" resort=true}


{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{*$smarty.session.g_empr*}</strong><br>
        <strong>DETALLE DE TRANSACCIONES DE INVENTARIO<strong><br>
        <span class="subtitulo">{$subtitulo}</span>
    </p>
    </div>
    {assign var=cols value=9}
    <table border=1 cellspacing=0 >
	

{/report_header}

{report_header group="BODEG"}
    <tr style="margin-top:20px;margin-bottom:20px;padding-bottom:20px;">
        <td colspan={$cols} style="padding-top:20px; text-align:center; font-size:14px;"><strong>{$rec.BODEG}</strong></td>
    </tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">S</td>
            <td class="headerrow">COD.</td>
            <td class="headerrow">ITEM</td>
            <td class="headerrow">UNI</td>
            <td class="headerrow">CANT.</td>
            <td class="headerrow">COSTO</td>
            <td class="headerrow">VALOR</td>
        </tr>
{/report_header}

{report_header group="COMPR"}
    <tr  class="espaciogrupo">
        <td colspan=7 style="padding:10px !important;"><strong>COMPROB.</strong> {$rec.TIPO} {$rec.COMPR}   <strong class="espacio">FECHA:</strong> {$rec.FECHA} <strong class="espacio">A:</strong> {$rec.RECEP} <strong class="espacio">S:</strong> {$rec.REFOP}</td>
    </tr>
{/report_header}

{report_detail}
    <tr>
        <td>{$rec.SECUE}</td>
        <td>{$rec.CODIT}</td>
        <td>{$rec.ITEM}</td>
        <td>{$rec.UNIDA}</td>
        <td class="num">{$rec.CANTI|number_format:2}</td>
        <td class="num">{$rec.COSTO|number_format:2}</td>
        <td class="num">{$rec.VALOR|number_format:2}</td>	
    </tr>
    
{/report_detail}

{report_footer group="BODEG"}
    <tr class="subtotal">
        <td colspan=4>SUBTOTAL</td>
        <td class="num">{$sum.CANTI|number_format:2}</td>
        <td class="num">{$sum.COSTO|number_format:2}</td>
        <td class="num">{$sum.VALOR|number_format:2}</td>        
    </tr>
{/report_footer}

{report_footer}
    <tr class="total">
        <td class="total" colspan=4>SUMA GENERAL</td>
        <td class="total num">{$sum.CANTI|number_format:2}</td>
        <td class="total num">{$sum.COSTO|number_format:2}</td>
        <td class="total num">{$sum.VALOR|number_format:2}</td>       
    </tr>
    </table>
    <div style="font-size:0.7em; text-align:left; float:left;color:#000000; margin-top:20px;">
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
	<p style="line-height:0.5em;">{$agArchivo}</p>
    </div>
{/report_footer}

{/report}
</body>