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
    </style>
  {/literal}
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>RESUMEN DE CHEQUES</title>
  
</head>

<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="txt,cheque" resort=true}
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
        NUMERO DE CHEQUE INICIAL Y FINAL GIRADO<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 >
        

{/report_header}

{report_header group="txt"}
    <tr>
        <td colspan=6 style="padding-top:20px;"><strong>BANCO: </strong>{$rec.cod} - {$rec.txt}</td>
    </tr>
    <tr>
        <td colspan=6><strong>CTA.CTE.: </strong>{$rec.ctacte}</td>
    </tr>
    <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
        <td class="headerrow">NUMEROS</td>
        <td class="headerrow"><strong>FECHA</strong></td>
        <td class="headerrow"><strong>BENEFICIARIO</strong></td>
        <td class="headerrow"><strong>VALOR</strong></td>
        <td class="headerrow"><strong>ESTATUS</strong></td>
        <td class="headerrow"><strong>DETALLE</strong></td>
    </tr>
{/report_header}

{report_detail}
    <tr>
        <td>{$rec.cheque}</td>
        <td>{$rec.fecha}</td>
        <td style="padding-left:10px;">{$rec.beneficiario}</td>
        <td>{$rec.det_ValCredito}</td>
        <td>{$rec.estatus}</td>
        <td>{$rec.detalle}</td>
    </tr>
    
{/report_detail}

{report_footer}
    
    </table>
    <div style="font-size:0.7em; text-align:left; float:left;color:#000000; margin-top:20px;">
        <p style="line-height:0.5em;"><strong>Usuario: </strong>{$smarty.session.g_user}</p>
        <p style="line-height:0.5em;"><strong>Fecha Imp.: </strong>{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</p>
	<p style="line-height:0.5em;">{$agArchivo}</p>
    </div>
{/report_footer}

{/report}
</body>