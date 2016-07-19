<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />
    <title>Movimientos de Cuentas</title>
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="aux,fecha,tipo,numComp" resort=true}
{report_header}
    <hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        REPORTE DE MOVIMIENTOS ANULADOS<br>
        {$subtitulo}
    </p>
    </div>
    <table border=1 cellspacing=0 style="font-size:0.6em;">
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">Tipo</td>
            <td class="headerrow"><strong>Numero</strong></td>
            <td class="headerrow"><strong>Cheque</strong></td>
            <td class="headerrow"><strong>Fecha</strong></td>
            <td class="headerrow"><strong>Debito</strong></td>
            <td class="headerrow"><strong>Credito</strong></td>
	    <td class="headerrow"><strong>Glosa</strong></td>
	    <td class="headerrow"><strong>Usuario</strong></td>
        </tr>
{/report_header}

{report_header group="aux"}
    <tr>
        <td colspan=8><strong>Auxiliar: </strong>{$rec.nomAux} ({$rec.aux})</td>
    </tr>
{/report_header}

{report_detail}
    <tr>
        <td >{$rec.tipo}</td>
        <td class="colnum ">{$rec.numComp}</td>
        {if ($rec.cheque != 0)}
	  <td class="colnum" style="text-align:right;">{$rec.cheque}</td>
	{else}
	  <td class="colnum">&nbsp</td>
	{/if}
        <td class="colnum ">{$rec.fecha}</td>
	<td class="colnum " style="text-align:right;">{$rec.debito|number_format:2}</td>
        <td class="colnum " style="text-align:right;">{$rec.credito|number_format:2}</td>
	<td class="coldata ">{$rec.concepto}</td>
	<td class="coldata ">{$rec.usuario}</td>
        
	<!--<td class="colnum">&nbsp</td>-->
    </tr>
{/report_detail}


{report_footer}
    </table>
{/report_footer}

{/report}
</body>