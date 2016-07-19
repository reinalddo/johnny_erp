<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  {literal}
  <style type="text/css">
        .tot{background-color:#D9D9D9;}
	.subtotal{font-style:italic; font-weight:bold;}
	.total{font-size:12px; font-weight:bold;margin-top:20px; padding-top:20px;}
	
    </style>
  {/literal}
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>UBICACION DE CHEQUES</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="empresa, banco, cheque" resort=true}
{assign var=acum value=0}
{*assign var=acumula value=0*}
{report_header}
    <!--<input type="button" title="Envia por correo este reporte" value="Correo" onclick="fGenMail()"/>
    <input type="button" title="Presenta los datos Base de reporte para ser exportados a excel" value="Excel" onclick="fGenExcel()"/>-->
    <hr/>
    <!--<div style="font-size:0.8em; text-align:left; float:right;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>-->
    </br>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        UBICACION DE CHEQUES<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 >
	{*<tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">EMPRESA</td>
            <td class="headerrow">BANCO</td>
            <td class="headerrow">CHEQUE</td>
            <td class="headerrow">FECHA</td>
            <td class="headerrow">VALOR</td>
            <td class="headerrow">BENEFICIARIO</td>
            <td class="headerrow">USUARIO</td>
        </tr>*}

{/report_header}

{report_header group="empresa"}
    <tr style="margin-top:20px;margin-bottom:20px;padding-bottom:20px;">
        <td colspan=6 style="padding-top:20px; text-align:center; font-size:14px;"><strong>EMPRESA: </strong>{$rec.empresa}</td>
    </tr>
{/report_header}

{report_header group="banco"}
    <tr style="margin-top:20px;margin-bottom:20px;padding-bottom:20px;">
        <td colspan=7 style="padding-top:20px; text-align:center; font-size:14px;"><strong>BANCO: </strong>{$rec.banco}</td>
    </tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            {*<td class="headerrow">EMPRESA</td>
            <td class="headerrow">BANCO</td>*}
            <td class="headerrow">CHEQUE</td>
            <td class="headerrow">FECHA</td>
            <td class="headerrow">VALOR</td>
            <td class="headerrow">BENEFICIARIO</td>
            <td class="headerrow">USUARIO</td>
	    {if (3 == $opc)}
	      <td class="headerrow">UBICACION</td>
	    {/if}
        </tr>
{/report_header}

{report_detail}
    <tr>
        {*<td>{$rec.empresa}</td>
        <td>{$rec.banco}</td>*}
        <td>{$rec.cheque}</td>
        <td>{$rec.fecha}</td>
        <td>{$rec.valor}</td>
        <td>{$rec.beneficiario}</td>
        <td>{$rec.origen}</td>
	{if (3 == $opc)}
	  <td>{$rec.observacion}</td>
	{/if}
    </tr>
    
{/report_detail}

{report_footer group="banco"}
    <tr class="subtotal">
        <td colspan=2>SUBTOTAL</td>
        <td>{$sum.valor}</td>
        {if (3 == $opc)}
	  <td colspan=3></td>
	{else}
	  <td colspan=2></td>
	{/if}
    </tr>
{/report_footer}

{report_footer}
    <tr class="total">
        <td colspan=2 class="total">TOTAL</td>
        <td class="total">{$sum.valor}</td>
        {if (3 == $opc)}
	  <td colspan=3></td>
	{else}
	  <td colspan=2></td>
	{/if}
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