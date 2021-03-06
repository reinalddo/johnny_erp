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
{report recordset=$agData record=rec	groups="fecha,numero" resort=true}
{report_header}
    <hr/>
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        REPORTE DE MOVIMIENTOS DE CUENTAS<br>
        Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
        {$subtitulo}
    </p>
    <table border=1 cellspacing=0>
        <tr style="font-weight:bold; text-align:center; vertical-align:middle;">
            <td class="headerrow">Tipo</td>
            <td class="headerrow"><strong>Numero</strong></td>
            <td class="headerrow"><strong>Fecha</strong></td>
            <td class="headerrow"><strong>Nombre</strong></td>
            <td class="headerrow"><strong>Glosa</strong></td>
            <td class="headerrow"><strong>Debito</strong></td>
            <td class="headerrow"><strong>Credito</strong></td>
        </tr>
{/report_header}

{report_detail}
    <tr><td nowrap>{$rec.tipo}</td>
        <td class="colnum ">{$rec.numero}</td>
        <td class="colnum ">{$rec.fecha}</td>
	<td class="colnum ">{$rec.nombre}</td>
        <td class="colnum ">{$rec.glosa}</td>
        <td class="colnum ">{$rec.debito}</td>
        <td class="colnum ">{$rec.credito}</td>
    </tr>
{/report_detail}

{report_footer}
    <tr><td colspan=7>&nbsp</td></tr>
    <tr>
        <td colspan=4>&nbsp</td>
        <td class="colnum"><strong>TOTALES:</strong></td>
        <td class="colnum headerrow">{$sum.debito|number_format:2}</td>
        <td class="colnum headerrow">{$sum.credito|number_format:2}</td>
    </tr>
    </table>
{/report_footer}

{/report}
</body>