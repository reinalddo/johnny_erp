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
	.subtotal{font-style:italic; font-weight:bold;padding-top:7px;}
	.total{font-size:12px; font-weight:bold;margin-top:20px; padding-top:20px;}
	.cabecera{font-weight:bold; text-align:center; vertical-align:middle; border-bottom: 1px dotted red;padding:8px !important;}
    </style>
  {/literal}
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>ESTADO DE CHEQUES</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="banco, empresa" resort=true}
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
        <strong>{*$smarty.session.g_empr*}</strong><br>
        ESTADO DE CHEQUES POR EMPRESA<br>
        {$subtitulo}<br>
        INFORMACION EN USD
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

{*report_header group="empresa"}
    <tr style="margin-top:20px;margin-bottom:20px;padding-bottom:20px;">
        <td colspan=6 style="padding-top:20px; text-align:center; font-size:14px;"><strong>EMPRESA: </strong>{$rec.empresa}</td>
    </tr>
{/report_header*}

{report_header group="banco"}
    <tr style="margin-top:20px;margin-bottom:20px;padding-bottom:20px;">
        <td colspan=9 style="padding-top:20px; text-align:center; font-size:14px;"><strong>BANCO: </strong>{$rec.banco}</td>
    </tr>
    <tr class="cabecera">
        <td class="cabecera"></td>
        <td class="cabecera"></td>
        <td class="cabecera" colspan=4>Control de Cheques</td>
        <td class="cabecera" colspan=3>Estado de Banco</td>
    </tr>
    <tr class="cabecera">
            <td class="cabecera">EMPRESA</td>
            {foreach from=$agCab item=curr_id}
                <!--id: {$curr_id}<br />-->
                <td class="cabecera"><strong>{$curr_id}</strong></td>
                {*<td class="headerrow"><strong>Fec.{$curr_id}</strong></td>*}
            {/foreach}
        </tr>
{/report_header}

{report_detail}
    <tr>
        <td>{$rec.empresa}</td>
        <td class="headerrow" style='text-align:center;'>{$rec.NA}</td>
        <td class="headerrow" style='text-align:center;'>{$rec.Emitido}</td>
        <td class="headerrow" style='text-align:center;'>{$rec.Confirmado}</td>
        <td class="headerrow" style='text-align:center;'>{$rec.Reconfirmado}</td>
        <td class="headerrow" style='text-align:center;'>{$rec.Emitido-$rec.Confirmado+$rec.Reconfirmado}</td>
        <td class="headerrow" style='text-align:center;'>{$rec.Confirmado}</td>
        <td class="headerrow" style='text-align:center;'>{$rec.Pagado}</td>
        <td class="headerrow" style='text-align:center;'>{$rec.Confirmado-$rec.Pagado}</td>
        {*foreach from=$agCab item=curr_id}
                
            {assign var=col value=$curr_id|cat:'fecha'}
            
            {if ($rec.$curr_id == null)}
                <td class="headerrow"><strong></strong></td>
            {else}
                <td class="headerrow" style='text-align:center;'>{$rec.$curr_id}</td>
            {/if}
            
        {/foreach*}
    </tr>
    
{/report_detail}

{report_footer group="banco"}
    <tr class="subtotal">
        <td class="subtotal">SUBTOTAL</td>
        <td class="subtotal" style='text-align:center;'>{$sum.NA}</td>
        <td class="subtotal" style='text-align:center;'>{$sum.Emitido}</td>
        <td class="subtotal" style='text-align:center;'>{$sum.Confirmado}</td>
        <td class="subtotal" style='text-align:center;'>{$sum.Reconfirmado}</td>
        <td class="subtotal" style='text-align:center;'>{$sum.Emitido-$sum.Confirmado+$sum.Reconfirmado}</td>
        <td class="subtotal" style='text-align:center;'>{$sum.Confirmado}</td>
        <td class="subtotal" style='text-align:center;'>{$sum.Pagado}</td>
        <td class="subtotal" style='text-align:center;'>{$sum.Confirmado-$sum.Pagado}</td>
        {*foreach from=$agCab item=curr_id}
                <!--id: {$curr_id}<br />-->
            {assign var=col value=$curr_id}
            <td class="headerrow" style='text-align:center;'>{$sum.$curr_id}</td>
             
            
        {/foreach*}
    </tr>
{/report_footer}

{report_footer}
    <tr class="total">
        <td class="total">TOTAL</td>
        <td class="total" style='text-align:center;'>{$sum.NA}</td>
        <td class="total" style='text-align:center;'>{$sum.Emitido}</td>
        <td class="total" style='text-align:center;'>{$sum.Confirmado}</td>
        <td class="total" style='text-align:center;'>{$sum.Reconfirmado}</td>
        <td class="total" style='text-align:center;'>{$sum.Emitido-$sum.Confirmado+$sum.Reconfirmado}</td>
        <td class="total" style='text-align:center;'>{$sum.Confirmado}</td>
        <td class="total" style='text-align:center;'>{$sum.Pagado}</td>
        <td class="total" style='text-align:center;'>{$sum.Confirmado-$sum.Pagado}</td>
        {*foreach from=$agCab item=curr_id}
                <!--id: {$curr_id}<br />-->
            <td class="total" style='text-align:center;'>{$sum.$curr_id}</td>
           
        {/foreach*}
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