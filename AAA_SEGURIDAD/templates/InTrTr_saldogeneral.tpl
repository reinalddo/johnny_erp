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
    </style>
  {/literal}
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>SALDO GENERAL</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="desGru, DES" resort=true}


{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{*$smarty.session.g_empr*}</strong><br>
        <strong>SALDO GENERAL DE INVENTARIO<strong><br>
        <span class="subtitulo">{$subtitulo}</span>
    </p>
    </div>
    {assign var=cols value=9}
    <table border=1 cellspacing=0 >
	

{/report_header}

{report_header group="desGru"}
    <tr style="margin-top:20px;margin-bottom:20px;padding-bottom:20px;">
        <td colspan={$cols} style="padding-top:20px; text-align:center; font-size:14px;"><strong>{$rec.desGru}</strong></td>
    </tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">COD.</td>
            <td class="headerrow">ITEM</td>
            <td class="headerrow">U</td>
            <td class="headerrow">SLDO. PREVIO</td>
            <td class="headerrow">CANT. INGRESOS</td>
            <td class="headerrow">CANT. EGRESOS</td>
            <td class="headerrow">SLDO. FINAL</td>
            <td class="headerrow">COSTO FINAL</td>
            <td class="headerrow">COSTO UNIT.</td>
        </tr>
{/report_header}



{report_detail}
    <tr>
        <td>{$rec.ITE}</td>
        <td>{$rec.DES}</td>
        <td>{$rec.UNI}</td>
        <td class="num">{$rec.SAN|number_format:2}</td>
        <td class="num">{$rec.CIN|number_format:2}</td>
        <td class="num">{$rec.CEG|number_format:2}</td>
        <td class="num">{$rec.SAC|number_format:2}</td>
	<td class="num">{$rec.SAC*$rec.PUN|number_format:2}</td>
        <td class="num">{$rec.PUN|number_format:4}</td>
    </tr>
    
{/report_detail}

{report_footer group="desGru"}
    <tr class="subtotal">
        <td>SUBTOTAL</td>
        <td></td>
        <td></td>
        <td class="num"></td>
        <td class="num"></td>
        <td class="num"></td>
        <td class="num"></td>
	<td class="num">{$sum.VAC|number_format:2}</td>
        <td class="num"></td>
    </tr>
{/report_footer}

{report_footer}
    <tr class="total">
        <td class="total">SUMA GENERAL</td>
        <td></td>
        <td></td>
        <td class="num"></td>
        <td class="num"></td>
        <td class="num"></td>
        <td class="num"></td>
	<td class="num total">{$sum.SAC*$sum.PUN|number_format:2}</td>
        <td class="num"></td>
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