<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--  Palntilla para reporte de Tarjas por Vapor, marca:
	  Tabla de doble entrada, en vertcal los productores, agrupados por zona; en la horizontal las marcas agrupadas por producto -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=ISO-8859-1" />
  <meta name="author" content="Gina Franco" />
  
  <!--<link rel="stylesheet" type="text/css" media="screen, print" href="../css/report.css" title="CSS para pantalla" />-->
  <!--<link rel="stylesheet" type="text/css" media="screen" href="../css/AAA_basico.css" title="CSS para pantalla" />-->
    <!--<title>Archivo Pichincha</title>-->
  
</head>
<body id="top" style="font-family:'Arial'">
{report recordset=$agData record=rec	groups="" resort=true}
{report_header}
    <!--<hr/>
    <div style="float:right;font-size:0.8em;">Fecha Imp.: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</div>
    <div style="margin-left:10%;width:70%">
    <p style="text-align: center; font:12; display:block; width=70%; font-size:0.8em;">
        <strong>{$smarty.session.g_empr}</strong><br>
        REPORTE DE MOVIMIENTOS DE CUENTAS<br>
        {$subtitulo}
    </p>
    </div>-->
    <table border=1 cellspacing=0 style="font-size:0.6em;">       
{/report_header}

{report_detail}
    <tr><td nowrap>{$rec.numcta|replace:'-':''}</td>
        <td class="colnum ">{$rec.cheque}</td>
        <td class="colnum ">{$rec.valor|number_format:2:'.':''}</td>
	<td class="coldata ">{$rec.beneficiario}</td>	
    </tr>
{/report_detail}

{report_footer}
    </table>
{/report_footer}

{/report}
</body>